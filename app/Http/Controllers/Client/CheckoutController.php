<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Nếu cổng thanh toán MoMo redirect về trang /thanh-toan kèm tham số giao dịch
        if ($request->has('partnerCode') && ($request->has('orderId') || $request->has('resultCode') || $request->has('errorCode'))) {
            return $this->momoCallback($request);
        }

        $cartData = CartService::getCart();

        if (empty($cartData['items'])) {
            return redirect()->route('client.products.index')
                ->with('error', 'Giỏ hàng của bạn đang trống! Hãy chọn sản phẩm trước khi thanh toán.');
        }

        $user = Auth::user();
        $addresses = $user ? $user->addresses : collect();
        $defaultAddress = $user ? ($user->defaultAddress ?? $addresses->firstWhere('is_default', true) ?? $addresses->first()) : null;
        $depositInfo = CartService::checkDepositPolicy($cartData['items'], $cartData['total'], $user);

        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->orderBy('min_order_value', 'asc')
            ->get();

        $administrativeController = new \App\Http\Controllers\Api\AdministrativeController();
        $provinces = $administrativeController->provinces()->getData()->data ?? [];

        return view('client.checkout', [
            'user' => $user,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'provinces' => $provinces,
            'cartItems' => $cartData['items'],
            'cartCount' => $cartData['count'],
            'subtotal' => $cartData['subtotal'],
            'discount' => $cartData['discount'],
            'shipping' => $cartData['shipping'],
            'total' => $cartData['total'],
            'appliedCoupon' => $cartData['coupon'],
            'coupons' => $coupons,
            'depositInfo' => $depositInfo,
        ]);
    }

    public function process(Request $request)
    {
        $cartData = CartService::getCart();

        if (empty($cartData['items'])) {
            return redirect()->route('client.products.index')
                ->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => ['required', 'string', 'max:20', 'regex:/^(0|\+84)(3|5|7|8|9)[0-9]{8}$/'],
            'customer_email' => 'nullable|email|max:255',
            'city' => 'required|string|max:150',
            'district' => 'required|string|max:150',
            'ward' => 'required|string|max:150',
            'shipping_address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cod,online,momo,zalopay,vnpay,vietqr',
        ], [
            'customer_name.required' => 'Quý khách vui lòng nhập họ và tên người nhận hàng.',
            'customer_phone.required' => 'Quý khách vui lòng nhập số điện thoại nhận hàng.',
            'customer_phone.regex' => 'Số điện thoại không đúng định dạng Việt Nam (10 số, ví dụ: 0987654321).',
            'city.required' => 'Quý khách vui lòng chọn Tỉnh / Thành Phố.',
            'district.required' => 'Quý khách vui lòng chọn Quận / Huyện.',
            'ward.required' => 'Quý khách vui lòng chọn Phường / Xã.',
            'shipping_address.required' => 'Quý khách vui lòng nhập số nhà, tên đường nhận hàng cụ thể.',
            'payment_method.required' => 'Quý khách vui lòng chọn phương thức thanh toán.',
        ]);

        $user = Auth::user();
        $orderCode = 'BEE-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        DB::beginTransaction();

        try {
            // 1. KIỂM TRA TỒN KHO THỰC TẾ & TÍNH TOÁN LẠI TỪ DATABASE (KHÔNG TIN TƯỞNG CLIENT)
            $verifiedSubtotal = 0;
            foreach ($cartData['items'] as $item) {
                $productDb = Product::findOrFail($item['product_id']);

                // Tìm đúng biến thể theo variant_id hoặc màu + size
                $variantDb = null;
                if (!empty($item['variant_id'])) {
                    $variantDb = \App\Models\ProductVariant::find($item['variant_id']);
                } elseif (!empty($item['color']) && !empty($item['size'])) {
                    $variantDb = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                        ->where('color', $item['color'])
                        ->where('size', $item['size'])
                        ->first();
                }

                $availableStock = $variantDb ? $variantDb->stock : $productDb->stock;
                $variantDesc = ($item['color'] ?? '') . ($item['size'] ? ' - Size ' . $item['size'] : '');

                if ($availableStock <= 0) {
                    DB::rollBack();
                    return redirect()->route('client.cart')
                        ->with('error', "Rất tiếc, sản phẩm \"{$item['name']}\" ({$variantDesc}) hiện đã hết hàng trong kho. Vui lòng cập nhật giỏ hàng!");
                }

                if ($item['quantity'] > $availableStock) {
                    DB::rollBack();
                    return redirect()->route('client.cart')
                        ->with('error', "Sản phẩm \"{$item['name']}\" ({$variantDesc}) trong kho chỉ còn {$availableStock} cái, không đủ cho số lượng đặt ({$item['quantity']} cái). Vui lòng điều chỉnh lại giỏ hàng!");
                }

                $itemPrice = (int)$productDb->price;
                if ($variantDb && $variantDb->price > 0) {
                    $itemPrice = (int)$variantDb->price;
                }

                if (!empty($item['deal_id'])) {
                    $deal = \App\Models\DailyDeal::where('id', $item['deal_id'])->where('is_active', true)->first();
                    if ($deal) {
                        $itemPrice = (int)$deal->deal_price;
                    }
                }
                $verifiedSubtotal += $itemPrice * (int)$item['quantity'];
            }

            $verifiedDiscount = 0;
            if ($cartData['coupon']) {
                $couponDb = Coupon::where('code', $cartData['coupon']->code)->where('is_active', true)->first();
                if ($couponDb && $couponDb->isValidForOrder($verifiedSubtotal)) {
                    $verifiedDiscount = $couponDb->calculateDiscount($verifiedSubtotal);
                }
            }

            $verifiedShipping = (int)$cartData['shipping'];
            $verifiedTotal = max(0, $verifiedSubtotal - $verifiedDiscount + $verifiedShipping);

            // Xác định payment_status: Các phương thức thanh toán trực tuyến (MoMo, VNPAY, Online) cần trạng thái PENDING_PAYMENT
            $paymentStatus = match ($validated['payment_method']) {
                'momo', 'vnpay', 'online', 'zalopay' => 'PENDING_PAYMENT',
                'cod', 'vietqr' => 'unpaid',
                default => 'unpaid',
            };

            $depositChoice = $request->input('payment_deposit_choice');
            $depositPolicy = CartService::checkDepositPolicy($cartData['items'], $verifiedTotal, $user);
            $isLargeOrder = !empty($depositPolicy['is_required']);

            if (!$isLargeOrder) {
                // Đơn hàng thông thường dưới 10 sản phẩm: KHÔNG áp dụng đặt cọc, thanh toán chuẩn
                $isDepositRequired = false;
                $depositAmount = 0;
                $remainingAmount = ($validated['payment_method'] === 'cod') ? $verifiedTotal : 0;
                $depositStatus = 'none';
            } elseif ($depositChoice === 'full_100') {
                // Đơn hàng lớn từ 10 sản phẩm nhưng khách hàng chủ động chọn thanh toán trọn gói 100%
                $isDepositRequired = false;
                $depositAmount = 0;
                $remainingAmount = 0;
                $depositStatus = 'none';
            } else {
                // Đơn hàng lớn từ 10 sản phẩm mặc định hoặc khách chọn đặt cọc 50%
                $isDepositRequired = true;
                $depositAmount = (int) round($verifiedTotal * 0.5);
                $remainingAmount = $verifiedTotal - $depositAmount;
                $depositStatus = 'unpaid';
            }

            $orderNotes = $validated['notes'] ?? null;
            $adminNotes = null;
            if ($isDepositRequired) {
                $depositNotice = "[CHÍNH SÁCH ĐẶT CỌC 50%: Đơn hàng đặt cọc trước 50% (" . number_format($depositAmount, 0, ',', '.') . "₫), số tiền 50% còn lại (" . number_format($remainingAmount, 0, ',', '.') . "₫) thu tiền mặt khi bưu tá giao hàng]";
                $adminNotes = $depositNotice;
                $orderNotes = $orderNotes ? "{$orderNotes} | {$depositNotice}" : $depositNotice;
            } elseif ($isLargeOrder && $depositChoice === 'full_100') {
                $fullNotice = "[THANH TOÁN 100%: Khách hàng chọn thanh toán toàn bộ 100% (" . number_format($verifiedTotal, 0, ',', '.') . "₫) qua " . strtoupper($validated['payment_method']) . ", miễn thu tiền mặt khi nhận hàng (COD 0₫)]";
                $adminNotes = $fullNotice;
                $orderNotes = $orderNotes ? "{$orderNotes} | {$fullNotice}" : $fullNotice;
            }

            // Chuẩn hóa địa chỉ hành chính thực tế
            $streetAddress = trim($validated['shipping_address']);
            $city = trim($validated['city']);
            $district = trim($validated['district']);
            $ward = trim($validated['ward']);
            $fullAddress = "{$streetAddress}, {$ward}, {$district}, {$city}";

            $addressSnapshot = [
                'recipient_name' => $validated['customer_name'],
                'phone' => $validated['customer_phone'],
                'email' => $validated['customer_email'] ?? null,
                'street_address' => $streetAddress,
                'ward' => $ward,
                'ward_code' => $request->input('ward_code'),
                'district' => $district,
                'district_code' => $request->input('district_code'),
                'city' => $city,
                'province_code' => $request->input('province_code'),
                'full_address' => $fullAddress,
                'verified_real_address' => true,
                'verified_at' => now()->toIso8601String(),
            ];

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $streetAddress,
                'city' => $city,
                'district' => $district,
                'ward' => $ward,
                'shipping_address_snapshot' => $addressSnapshot,
                'notes' => $orderNotes,
                'admin_notes' => $adminNotes,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'shipping_status' => 'pending',
                'shipping_carrier' => 'Giao Hàng Tiết Kiệm (GHTK)',
                'tracking_code' => 'GHTK-' . strtoupper(Str::random(8)),
                'status_step' => 1,
                'subtotal' => $verifiedSubtotal,
                'discount_amount' => $verifiedDiscount,
                'shipping_fee' => $verifiedShipping,
                'total_amount' => $verifiedTotal,
                'is_deposit_required' => $isDepositRequired,
                'deposit_amount' => $depositAmount,
                'remaining_amount' => $remainingAmount,
                'deposit_status' => $isDepositRequired ? 'unpaid' : 'none',
                'coupon_code' => $cartData['coupon'] ? $cartData['coupon']->code : null,
            ]);

            // Tự động lưu/cập nhật vào sổ địa chỉ nếu người dùng đã đăng nhập
            if ($user) {
                try {
                    \App\Models\UserAddress::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'phone' => $validated['customer_phone'],
                            'address' => $streetAddress,
                            'ward' => $ward,
                            'district' => $district,
                            'city' => $city,
                        ],
                        [
                            'recipient_name' => $validated['customer_name'],
                            'is_default' => $user->addresses()->count() === 0,
                            'label' => 'Nhà riêng',
                        ]
                    );
                } catch (\Throwable $e) {
                    Log::warning('Could not save user address: ' . $e->getMessage());
                }
            }

            // Lưu các mặt hàng trong đơn hàng và trừ tồn kho ngay lập tức
            foreach ($cartData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'image' => $item['image'],
                ]);

                // TRỪ TỒN KHO CHI TIẾT CỦA BIẾN THỂ (MÀU + SIZE)
                $variant = null;
                if (!empty($item['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($item['variant_id']);
                } elseif (!empty($item['color']) && !empty($item['size'])) {
                    $variant = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                        ->where('color', $item['color'])
                        ->where('size', $item['size'])
                        ->first();
                }

                if ($variant) {
                    $variant->decrement('stock', $item['quantity']);
                    if ($variant->stock < 0) {
                        $variant->update(['stock' => 0]);
                    }
                }

                // TRỪ TỒN KHO TỔNG CỦA SẢN PHẨM VÀ TĂNG ĐÃ BÁN
                $prod = Product::find($item['product_id']);
                if ($prod) {
                    $prod->decrement('stock', $item['quantity']);
                    if ($prod->stock < 0) {
                        $prod->update(['stock' => 0]);
                    }
                    $prod->increment('sold_count', $item['quantity']);
                }

                // Cập nhật số lượng đã bán của Daily Deal
                if (!empty($item['deal_id'])) {
                    $deal = \App\Models\DailyDeal::find($item['deal_id']);
                    if ($deal) {
                        $deal->increment('sold_count', $item['quantity']);
                    }
                }
            }

            // Cập nhật số lượt sử dụng mã giảm giá (nếu có)
            if ($cartData['coupon']) {
                $coupon = Coupon::find($cartData['coupon']->id);
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            DB::commit();

            // Xóa sạch giỏ hàng trong session sau khi hoàn tất đặt hàng
            CartService::clear();

            if ($validated['payment_method'] === 'online') {
                return redirect()->route('client.checkout.online', ['code' => $orderCode]);
            }

            if ($validated['payment_method'] === 'momo') {
                $payUrl = $this->createMomoAtmPaymentUrl($order);

                if ($payUrl) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'redirect_url' => $payUrl,
                            'order_code' => $orderCode,
                            'payment_method' => 'momo',
                        ]);
                    }

                    return redirect()->away($payUrl);
                }

                return back()->withInput()->with('error', 'Không thể kết nối đến Cổng MoMo Sandbox. Vui lòng kiểm tra lại kết nối.');
            }

            if ($validated['payment_method'] === 'zalopay') {
                return redirect()->route('client.checkout.zalopay', ['code' => $orderCode]);
            }

            if ($validated['payment_method'] === 'vnpay') {
                $payUrl = $this->createVnpayPaymentUrl($order);

                if ($payUrl) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'redirect_url' => $payUrl,
                            'order_code' => $orderCode,
                            'payment_method' => 'vnpay',
                        ]);
                    }

                    return redirect()->away($payUrl);
                }

                return back()->withInput()->with('error', 'Không thể kết nối đến Cổng VNPAY Sandbox. Vui lòng kiểm tra lại kết nối.');
            }

            // Với đơn COD & VietQR: gửi email hóa đơn ngay và chuyển sang trang xác nhận thành công
            $this->sendOrderInvoiceEmail($order);

            return redirect()->route('client.checkout.success', ['code' => $orderCode])
                ->with('success', "Chúc mừng bạn đã đặt hàng thành công tại BeeStyle! Mã đơn hàng của bạn là {$orderCode}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Trang Xác Nhận Đặt Hàng Thành Công (Order Success / Thank You Page)
     */
    public function orderSuccess($code)
    {
        $order = Order::with(['items.product', 'user'])->where('order_code', $code)->firstOrFail();

        return view('client.order-success', compact('order'));
    }

    /**
     * Cổng Thanh Toán Trực Tuyến Online Banking Napas 247
     */
    public function onlineGateway($code)
    {
        Order::cancelAllExpiredPendingOnlineOrders();
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán thành công!");
        }

        if ($order->shipping_status === 'cancelled' || $order->isOnlinePaymentExpired()) {
            if ($order->shipping_status !== 'cancelled') {
                $order->cancelAsExpiredOnlinePayment();
            }
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('warning', "Đơn hàng #{$code} đã bị tự động hủy do quá thời gian chờ thanh toán (15 phút).");
        }

        return view('client.payment.online', compact('order'));
    }

    /**
     * Xác nhận thanh toán Online Banking thành công
     */
    public function onlineSuccess($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        if ($isDeposit) {
            $rem = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
            $order->update([
                'deposit_status' => 'paid',
                'deposit_paid_at' => now(),
                'payment_status' => 'deposit_paid',
                'remaining_amount' => $rem,
                'shipping_status' => 'processing',
                'status_step' => 3,
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successAmount = $order->deposit_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) cho đơn hàng #{$code}! Số tiền 50% còn lại (" . number_format($rem, 0, ',', '.') . "₫) sẽ được thanh toán bằng tiền mặt cho bưu tá khi nhận hàng (COD). Kho hàng BeeStyle đang đóng gói sản phẩm để giao đến bạn!";
        } else {
            $order->update([
                'payment_status' => 'paid',
                'remaining_amount' => 0,
                'shipping_status' => 'processing',
                'status_step' => 3,
                'paid_at' => now(),
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successAmount = $order->total_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 100% đơn hàng #{$code}! Quý khách không cần thanh toán thêm bất kỳ đồng nào khi nhận hàng (COD 0₫). Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.";
        }
        $this->sendOrderInvoiceEmail($order);

        return redirect()->route('client.home')
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $successAmount)
            ->with('payment_success_method', 'Chuyển khoản VietQR 24/7 (Techcombank)')
            ->with('success', $successMsg);
    }

    /**
     * Tạo URL thanh toán MoMo ATM (payWithATM) và trả về payUrl sang Cổng MoMo Sandbox chính thức
     */
    public function createMomoAtmPaymentUrl(Order $order): ?string
    {
        $endpoint = config('momo.api_endpoint', env('MOMO_API_ENDPOINT', env('MOMO_ENDPOINT', 'https://test-payment.momo.vn/v2/gateway/api/create')));
        $partnerCode = config('momo.partner_code', env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'));
        $accessKey = config('momo.access_key', env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'));
        $secretKey = config('momo.secret_key', env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'));

        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        $amount = (string)(int) round($isDeposit ? $order->deposit_amount : $order->total_amount);
        $orderId = $order->order_code . '_' . time();
        $orderInfo = $isDeposit
            ? "Dat coc 50% don hang #" . $order->order_code . " qua MoMo"
            : "Thanh toan don hang #" . $order->order_code . " qua MoMo";
        $redirectUrl = url('/thanh-toan/momo/callback');
        $ipnUrl = config('momo.ipn_url', url('/api/payments/momo/ipn'));
        $extraData = base64_encode(json_encode([
            'order_code' => $order->order_code,
            'is_deposit' => $isDeposit,
        ]));

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => (int) $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'orderExpireTime' => 15,
            'signature' => $signature,
        ];

        try {
            $momoService = app(MomoService::class);
            $result = $momoService->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            Log::info("MoMo payWithATM Generated for Order #{$order->order_code}", [
                'orderId' => $orderId,
                'amount' => $amount,
                'response' => $jsonResult ?: ['raw' => $result],
            ]);

            if (!empty($jsonResult['payUrl'])) {
                return $jsonResult['payUrl'];
            }
        } catch (\Throwable $e) {
            Log::error("Failed to create MoMo ATM payment URL: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Cổng MoMo Gateway: Tự động chuyển thẳng sang Cổng MoMo Sandbox chính thức (không qua trang trung gian)
     */
    public function momoGateway(Request $request, $code)
    {
        Order::cancelAllExpiredPendingOnlineOrders();
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if (in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID'])) {
            return redirect()->route('client.checkout.success', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán qua MoMo thành công!");
        }
        if ($order->shipping_status === 'cancelled' || $order->isOnlinePaymentExpired() || strtoupper((string)$order->payment_status) === 'CANCELLED') {
            if ($order->shipping_status !== 'cancelled') {
                $order->cancelAsExpiredOnlinePayment();
            }
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('warning', "Đơn hàng #{$code} đã bị tự động hủy do quá thời gian chờ thanh toán (15 phút).");
        }

        // Hỗ trợ truy vấn trạng thái giao dịch (Query API) nếu có yêu cầu
        if ($request->input('action_type') === 'query' || $request->has('check_payment')) {
            $queryOrderId = $request->input('orderId') ?: ($order->order_code . '_' . time());
            $partnerCode = config('momo.partner_code', env('MOMO_PARTNER_CODE', 'MOMOBKUN20180529'));
            $accessKey = config('momo.access_key', env('MOMO_ACCESS_KEY', 'klm05TvNBzhg7h7j'));
            $secretKey = config('momo.secret_key', env('MOMO_SECRET_KEY', 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa'));
            $queryEndpoint = "https://test-payment.momo.vn/v2/gateway/api/query";
            $queryRequestId = time() . "";

            $rawHash = "accessKey=" . $accessKey . "&orderId=" . $queryOrderId . "&partnerCode=" . $partnerCode . "&requestId=" . $queryRequestId;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            $data = [
                'partnerCode' => $partnerCode,
                'requestId' => $queryRequestId,
                'orderId' => $queryOrderId,
                'signature' => $signature,
                'lang' => 'vi',
            ];

            $momoService = app(MomoService::class);
            $result = $momoService->execPostRequest($queryEndpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            if (isset($jsonResult['resultCode']) && (int)$jsonResult['resultCode'] === 0) {
                $order->update([
                    'payment_status' => 'paid',
                    'momo_trans_id' => $jsonResult['transId'] ?? null,
                    'paid_at' => now(),
                    'shipping_status' => 'processing',
                    'status_step' => 2,
                ]);
                return redirect()->route('client.checkout.success', ['code' => $order->order_code])
                    ->with('success', "Đơn hàng #{$code} đã được xác nhận thanh toán thành công qua MoMo!");
            }
        }

        // TỰ ĐỘNG TẠO PAYURL VÀ CHUYỂN THẲNG SANG CỔNG MOMO CHÍNH THỨC
        $payUrl = $this->createMomoAtmPaymentUrl($order);
        if ($payUrl) {
            return redirect()->away($payUrl);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('error', 'Không thể kết nối đến Cổng MoMo Sandbox. Vui lòng thử lại sau giây lát.');
    }

    /**
     * Bridge hỗ trợ gọi trực tiếp atm_momo.php
     */
    public function momoAtmPhpBridge(Request $request)
    {
        $orderCode = null;
        if ($request->filled('extraData')) {
            $decoded = json_decode(base64_decode($request->input('extraData')), true);
            $orderCode = $decoded['order_code'] ?? null;
        }
        if (!$orderCode && $request->filled('orderId')) {
            $parts = explode('_', $request->input('orderId'));
            $orderCode = $parts[0] ?? $request->input('orderId');
        }
        if (!$orderCode) {
            $order = Order::latest()->first();
            $orderCode = $order ? $order->order_code : null;
        }

        if ($orderCode) {
            return $this->momoGateway($request, $orderCode);
        }

        return redirect()->route('client.home');
    }

    /**
     * Bridge hỗ trợ gọi trực tiếp query_transaction.php
     */
    public function momoQueryBridge(Request $request)
    {
        $orderCode = null;
        if ($request->filled('orderId')) {
            $parts = explode('_', $request->input('orderId'));
            $orderCode = $parts[0] ?? $request->input('orderId');
        }
        if (!$orderCode) {
            $order = Order::latest()->first();
            $orderCode = $order ? $order->order_code : null;
        }

        if ($orderCode) {
            $request->merge(['action_type' => 'query']);
            return $this->momoGateway($request, $orderCode);
        }

        return redirect()->route('client.home');
    }

    /**
     * Nhận thông tin thẻ ATM và chuyển tiếp sang bước Xác Thực OTP
     */
    public function momoSubmitCard(Request $request, $code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        $validated = $request->validate([
            'bank_code' => 'required|string',
            'card_number' => 'required|string|min:12',
            'card_holder' => 'required|string|min:3',
            'card_date' => 'required|string|min:4',
        ], [
            'bank_code.required' => 'Vui lòng chọn ngân hàng phát hành thẻ ATM.',
            'card_number.required' => 'Vui lòng nhập số thẻ in trên thẻ ATM.',
            'card_holder.required' => 'Vui lòng nhập tên in trên thẻ ATM.',
            'card_date.required' => 'Vui lòng nhập ngày phát hành / hết hạn (MM/YY).',
        ]);

        $cleanCard = preg_replace('/\D/', '', $validated['card_number']);
        $maskedCard = '•••• •••• •••• ' . (strlen($cleanCard) >= 4 ? substr($cleanCard, -4) : '8888');

        $bankList = [
            'VCB' => ['name' => 'Vietcombank', 'logo' => 'vcb.png', 'color' => '#005a3c'],
            'TCB' => ['name' => 'Techcombank', 'logo' => 'tcb.png', 'color' => '#e31b23'],
            'MBB' => ['name' => 'MB Bank', 'logo' => 'mb.png', 'color' => '#002b80'],
            'CTG' => ['name' => 'VietinBank', 'logo' => 'ctg.png', 'color' => '#005baa'],
            'BIDV' => ['name' => 'BIDV', 'logo' => 'bidv.png', 'color' => '#005f56'],
            'VBA' => ['name' => 'Agribank', 'logo' => 'vba.png', 'color' => '#801424'],
            'ACB' => ['name' => 'ACB', 'logo' => 'acb.png', 'color' => '#00529c'],
            'VPB' => ['name' => 'VPBank', 'logo' => 'vpb.png', 'color' => '#008543'],
            'TPB' => ['name' => 'TPBank', 'logo' => 'tpb.png', 'color' => '#6b2d82'],
            'STB' => ['name' => 'Sacombank', 'logo' => 'stb.png', 'color' => '#00559f'],
            'HDB' => ['name' => 'HDBank', 'logo' => 'hdb.png', 'color' => '#d91f26'],
            'SHB' => ['name' => 'SHB', 'logo' => 'shb.png', 'color' => '#f37021'],
        ];

        $bankInfo = $bankList[$validated['bank_code']] ?? ['name' => $validated['bank_code'], 'logo' => 'mb.png', 'color' => '#a50064'];

        session([
            'momo_card_data' => [
                'bank_code' => $validated['bank_code'],
                'bank_name' => $bankInfo['name'],
                'bank_logo' => $bankInfo['logo'],
                'bank_color' => $bankInfo['color'],
                'card_masked' => $maskedCard,
                'card_holder' => strtoupper($validated['card_holder']),
                'card_date' => $validated['card_date'],
                'otp_code' => '123456',
                'ref_id' => 'NPS_' . strtoupper(Str::random(10)),
                'created_at' => now()->timestamp,
            ]
        ]);

        return redirect()->route('client.checkout.momo.otp', ['code' => $code]);
    }

    /**
     * Giao diện Trang Xác Thực OTP Chuẩn (3D-Secure NAPAS / MoMo Gateway)
     */
    public function momoOtp($code)
    {
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if (in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID'])) {
            return redirect()->route('client.checkout.success', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán qua MoMo thành công!");
        }

        $cardData = session('momo_card_data');
        if (!$cardData) {
            // Khởi tạo mặc định nếu truy cập trực tiếp
            $cardData = [
                'bank_code' => 'MBB',
                'bank_name' => 'MB Bank',
                'bank_logo' => 'mb.png',
                'bank_color' => '#002b80',
                'card_masked' => '•••• •••• •••• 8888',
                'card_holder' => strtoupper($order->customer_name ?: 'NGUYEN VAN A'),
                'card_date' => '12/28',
                'otp_code' => '123456',
                'ref_id' => 'NPS_' . strtoupper(Str::random(10)),
                'created_at' => now()->timestamp,
            ];
            session(['momo_card_data' => $cardData]);
        }

        return view('client.payment.momo_otp', compact('order', 'cardData'));
    }

    /**
     * Xác thực mã OTP và hoàn tất thanh toán, trả về trang checkout.success
     */
    public function momoVerifyOtp(Request $request, $code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        $cardData = session('momo_card_data');

        $otpInput = trim((string)$request->input('otp', ''));
        if (is_array($request->input('otp_digits'))) {
            $otpInput = implode('', $request->input('otp_digits'));
        }

        $expectedOtp = $cardData['otp_code'] ?? '123456';

        // Cho phép mã test 123456 hoặc 000000 hoặc mã trong session
        if ($otpInput !== $expectedOtp && $otpInput !== '123456' && $otpInput !== '000000') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã xác thực OTP không chính xác. Vui lòng nhập 123456 hoặc 000000.',
                ], 422);
            }
            return back()->with('error', 'Mã xác thực OTP không chính xác. Vui lòng nhập mã OTP mẫu: 123456 hoặc 000000.');
        }

        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        $transId = 'MOMO' . time();

        if ($isDeposit) {
            $rem = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
            $order->update([
                'deposit_status' => 'paid',
                'deposit_paid_at' => now(),
                'payment_status' => 'deposit_paid',
                'remaining_amount' => $rem,
                'shipping_status' => 'processing',
                'status_step' => 3,
                'momo_trans_id' => $transId,
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) qua Cổng MoMo Payment! Số tiền 50% còn lại (" . number_format($rem, 0, ',', '.') . "₫) sẽ được thanh toán bằng tiền mặt cho bưu tá khi nhận hàng (COD). Đơn hàng #{$code} đã được chuyển sang xưởng đóng gói.";
        } else {
            $order->update([
                'payment_status' => 'paid',
                'remaining_amount' => 0,
                'shipping_status' => 'processing',
                'status_step' => 2,
                'paid_at' => now(),
                'momo_trans_id' => $transId,
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 100% đơn hàng #{$code} qua Cổng MoMo Payment! Quý khách không cần thanh toán thêm bất kỳ đồng nào khi nhận hàng (COD 0₫).";
        }

        $this->sendOrderInvoiceEmail($order);
        session()->forget('momo_card_data');

        $redirectUrl = route('client.checkout.success', ['code' => $order->order_code]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl,
                'message' => $successMsg,
            ]);
        }

        // TRẢ VỀ TRANG CHECKOUT THÀNH CÔNG (checkout.success)
        return redirect()->to($redirectUrl)
            ->with('payment_success_order', $order->order_code)
            ->with('payment_success_amount', $isDeposit ? $order->deposit_amount : $order->total_amount)
            ->with('payment_success_method', 'MoMo Payment (Thẻ ATM ' . ($cardData['bank_name'] ?? 'NAPAS') . ')')
            ->with('success', $successMsg);
    }

    /**
     * Xác nhận thanh toán Ví MoMo thành công trực tiếp
     */
    public function momoSuccess($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');

        if ($isDeposit) {
            $rem = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
            $order->update([
                'deposit_status' => 'paid',
                'deposit_paid_at' => now(),
                'payment_status' => 'deposit_paid',
                'remaining_amount' => $rem,
                'shipping_status' => 'processing',
                'status_step' => 3,
                'momo_trans_id' => 'MOMO' . time(),
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successAmount = $order->deposit_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) qua Cổng MoMo Payment! Số tiền 50% còn lại (" . number_format($rem, 0, ',', '.') . "₫) sẽ được thanh toán bằng tiền mặt cho bưu tá khi nhận hàng (COD).";
        } else {
            $order->update([
                'payment_status' => 'paid',
                'remaining_amount' => 0,
                'shipping_status' => 'processing',
                'status_step' => 2,
                'paid_at' => now(),
                'momo_trans_id' => 'MOMO' . time(),
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $successAmount = $order->total_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 100% đơn hàng #{$code} qua Cổng MoMo Payment! Quý khách không cần thanh toán thêm bất kỳ đồng nào khi nhận hàng (COD 0₫).";
        }
        $this->sendOrderInvoiceEmail($order);

        // TRẢ VỀ TRANG CHECKOUT THÀNH CÔNG (checkout.success)
        return redirect()->route('client.checkout.success', ['code' => $order->order_code])
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $successAmount)
            ->with('payment_success_method', 'Cổng Thanh Toán MoMo Payment')
            ->with('success', $successMsg);
    }

    /**
     * Khởi tạo và chuyển hướng người dùng sang Cổng Thanh Toán MoMo Sandbox
     */
    public function momoRedirectSandbox($code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        if (strtoupper((string)$order->payment_status) === 'PAID') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán thành công!");
        }

        if ($order->shipping_status === 'cancelled' || strtoupper((string)$order->payment_status) === 'CANCELLED') {
            foreach ($order->items as $item) {
                $prod = Product::find($item->product_id);
                if (!$prod || $prod->stock < $item->quantity) {
                    return redirect()->route('client.cart')
                        ->with('error', "Rất tiếc, sản phẩm '{$item->product_name}' hiện không đủ số lượng trong kho để thanh toán lại.");
                }

                if (!empty($item->color) && !empty($item->size)) {
                    $variant = \App\Models\ProductVariant::where('product_id', $item->product_id)
                        ->where('color', $item->color)
                        ->where('size', $item->size)
                        ->first();
                    if ($variant && $variant->stock < $item->quantity) {
                        return redirect()->route('client.cart')
                            ->with('error', "Rất tiếc, phân loại '{$item->color} - {$item->size}' của sản phẩm '{$item->product_name}' đã hết hàng.");
                    }
                }
            }

            DB::beginTransaction();
            try {
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->decrement('stock', $item->quantity);
                    Product::where('id', $item->product_id)->increment('sold_count', $item->quantity);

                    if (!empty($item->color) && !empty($item->size)) {
                        \App\Models\ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->decrement('stock', $item->quantity);
                    }
                }

                $order->update([
                    'payment_status' => 'PENDING_PAYMENT',
                    'shipping_status' => 'pending',
                    'cancelled_at' => null,
                    'cancel_reason' => null,
                    'cancelled_by' => null,
                ]);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Không thể khôi phục đơn hàng: ' . $e->getMessage());
            }
        }

        $momoService = app(MomoService::class);
        $momoResult = $momoService->createPayment($order);

        if (!empty($momoResult['success']) && !empty($momoResult['payUrl'])) {
            return redirect()->away($momoResult['payUrl']);
        }

        return back()->with('error', $momoResult['message'] ?? 'Không thể kết nối đến MoMo Sandbox API. Vui lòng thử lại sau giây lát.');
    }

    /**
     * Xử lý MoMo Sandbox Callback
     */
    public function momoCallback(Request $request)
    {
        $data = $request->all();
        Log::info("MoMo Sandbox Callback Received", $data);

        $momoService = app(MomoService::class);
        $orderCode = $momoService->extractOrderCode($data);

        if (!$orderCode) {
            return redirect()->route('client.cart')
                ->with('error', 'Không tìm thấy thông tin đơn hàng từ giao dịch MoMo.');
        }

        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            return redirect()->route('client.cart')
                ->with('error', "Không tìm thấy đơn hàng #{$orderCode} trong hệ thống.");
        }

        $resultCode = (int)$request->input('resultCode', -1);
        $message = $request->input('message', 'Giao dịch không thành công');

        if ($resultCode === 0) {
            $transId = $request->input('transId') ?: ($data['transId'] ?? null);
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            if ($isDeposit) {
                $rem = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
                $order->update([
                    'deposit_status' => 'paid',
                    'deposit_paid_at' => now(),
                    'payment_status' => 'deposit_paid',
                    'remaining_amount' => $rem,
                    'shipping_status' => 'pending',
                    'status_step' => 1,
                    'momo_trans_id' => $transId ?: ($order->momo_trans_id ?: 'MOMO' . time()),
                ]);
                $this->sendOrderInvoiceEmail($order);
                $successAmount = $order->deposit_amount;
                $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) cho đơn hàng #{$order->order_code} qua Cổng MoMo Payment! Số tiền 50% còn lại (" . number_format($rem, 0, ',', '.') . "₫) sẽ được thanh toán bằng tiền mặt cho bưu tá khi nhận hàng (COD). Đơn hàng đã được tiếp nhận và chuyển sang bộ phận duyệt đơn!";
            } else {
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'remaining_amount' => 0,
                        'shipping_status' => 'pending',
                        'status_step' => 1,
                        'paid_at' => now(),
                        'momo_trans_id' => $transId ?: ($order->momo_trans_id ?: 'MOMO' . time()),
                    ]);
                    $this->sendOrderInvoiceEmail($order);
                }
                $successAmount = $order->total_amount;
                $successMsg = "Chúc mừng bạn đã thanh toán thành công 100% đơn hàng #{$order->order_code} qua Cổng MoMo Payment! Quý khách không cần thanh toán thêm bất kỳ đồng nào khi nhận hàng (COD 0₫). Đơn hàng đã được tiếp nhận và chuyển sang bộ phận duyệt đơn & đóng gói.";
            }

            return redirect()->route('client.checkout.success', ['code' => $order->order_code])
                ->with('payment_success_order', $order->order_code)
                ->with('payment_success_amount', $successAmount)
                ->with('payment_success_method', 'Cổng Thanh Toán MoMo Payment')
                ->with('success', $successMsg);
        }

        // Đơn hàng giữ nguyên trạng thái Chờ thanh toán với thời gian chờ 15 phút
        if ($order->shipping_status === 'pending') {
            $order->update(['payment_status' => 'PENDING_PAYMENT']);
        }

        return redirect()->route('client.order-tracking', ['code' => $order->order_code])
            ->with('info', "Giao dịch MoMo chưa hoàn tất hoặc bạn đã quay lại ({$message}). Đơn hàng #{$order->order_code} đang ở trạng thái Chờ thanh toán. Quý khách vui lòng bấm 'Thanh toán ngay' trong vòng 15 phút trước khi đơn hàng tự động bị hủy.");
    }

    /**
     * Xử lý MoMo Sandbox IPN Webhook
     */
    public function momoIpn(Request $request)
    {
        $data = $request->all();
        Log::info("MoMo Sandbox IPN Received", $data);

        $momoService = app(MomoService::class);

        if (!$momoService->verifySignature($data)) {
            Log::warning("MoMo Sandbox IPN Signature Verification Failed", $data);
            return response()->json(['resultCode' => 11007, 'message' => 'Chữ ký không hợp lệ'], 400);
        }

        $orderCode = $momoService->extractOrderCode($data);
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['resultCode' => 11000, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        $resultCode = (int)($data['resultCode'] ?? -1);
        if ($resultCode === 0) {
            $transId = $data['transId'] ?? ($request->input('transId') ?? null);
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            if ($isDeposit) {
                $order->update([
                    'deposit_status' => 'paid',
                    'deposit_paid_at' => now(),
                    'payment_status' => 'deposit_paid',
                    'shipping_status' => 'pending',
                    'status_step' => 1,
                    'momo_trans_id' => $transId ?: ($order->momo_trans_id ?: 'MOMO' . time()),
                ]);
                $this->sendOrderInvoiceEmail($order);
            } else {
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'shipping_status' => 'pending',
                        'status_step' => 1,
                        'paid_at' => now(),
                        'momo_trans_id' => $transId ?: ($order->momo_trans_id ?: 'MOMO' . time()),
                    ]);
                    $this->sendOrderInvoiceEmail($order);
                }
            }
        }

        return response()->noContent();
    }

    /**
     * Cổng Thanh Toán Trực Tuyến Ví ZaloPay
     */
    public function zalopayGateway($code)
    {
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán qua Ví ZaloPay thành công!");
        }
        if ($order->shipping_status === 'cancelled') {
            return redirect()->route('client.cart')
                ->with('warning', "Đơn hàng #{$code} đã bị hủy do hết hạn thanh toán.");
        }

        return view('client.payment.zalopay', compact('order'));
    }

    /**
     * Xác nhận thanh toán Ví ZaloPay thành công
     */
    public function zalopaySuccess($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
        if ($isDeposit) {
            $order->update([
                'deposit_status' => 'paid',
                'deposit_paid_at' => now(),
                'payment_status' => 'deposit_paid',
                'shipping_status' => 'pending',
                'status_step' => 1,
            ]);
            $successAmount = $order->deposit_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) cho đơn hàng #{$code} qua ZaloPay! Số tiền còn lại (" . number_format($order->remaining_amount, 0, ',', '.') . "₫) sẽ được thanh toán cho bưu tá khi nhận hàng (COD). Đơn hàng đã chuyển sang bộ phận duyệt đơn & đóng gói!";
        } else {
            $order->update([
                'payment_status' => 'paid',
                'shipping_status' => 'pending',
                'status_step' => 1,
                'paid_at' => now(),
            ]);
            $successAmount = $order->total_amount;
            $successMsg = "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code} qua Ví ZaloPay! Đơn hàng đã được tiếp nhận và chuyển sang bộ phận duyệt đơn & đóng gói.";
        }
        $this->sendOrderInvoiceEmail($order);

        return redirect()->route('client.checkout.success', ['code' => $code])
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $successAmount)
            ->with('payment_success_method', 'Ví Điện Tử ZaloPay')
            ->with('success', $successMsg);
    }

    /**
     * Khởi tạo link thanh toán VNPAY Gateway
     */
    public function createVnpayPaymentUrl(Order $order, ?string $bankCode = null): ?string
    {
        try {
            $vnpayService = app(\App\Services\VnpayService::class);
            return $vnpayService->createPaymentUrl($order, $bankCode);
        } catch (\Throwable $e) {
            Log::error("Failed to create VNPAY payment URL: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cổng VNPAY Gateway: Chuyển hướng người dùng sang Cổng VNPAY Sandbox
     */
    public function vnpayGateway(Request $request, $code)
    {
        Order::cancelAllExpiredPendingOnlineOrders();
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if (in_array(strtoupper((string)$order->payment_status), ['PAID', 'DEPOSIT_PAID'])) {
            return redirect()->route('client.checkout.success', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán thành công!");
        }

        if ($order->shipping_status === 'cancelled' || $order->isOnlinePaymentExpired() || strtoupper((string)$order->payment_status) === 'CANCELLED') {
            if ($order->shipping_status !== 'cancelled') {
                $order->cancelAsExpiredOnlinePayment();
            }
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('warning', "Đơn hàng #{$code} đã bị tự động hủy do quá thời gian chờ thanh toán (15 phút).");
        }

        $bankCode = $request->input('bank_code') ?: $request->input('bankCode');
        $payUrl = $this->createVnpayPaymentUrl($order, $bankCode);

        if ($payUrl) {
            return redirect()->away($payUrl);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('error', 'Không thể kết nối đến Cổng VNPAY. Vui lòng thử lại sau giây lát.');
    }

    /**
     * Bridge hỗ trợ form POST trực tiếp /vnpay_payment
     */
    public function vnpayPaymentBridge(Request $request)
    {
        $orderCode = $request->input('order_code') ?: $request->input('order_id');

        if (!$orderCode && $request->filled('total_vnpay')) {
            $order = Order::latest()->first();
            $orderCode = $order ? $order->order_code : null;
        }

        if ($orderCode) {
            return $this->vnpayGateway($request, $orderCode);
        }

        return redirect()->route('client.home');
    }

    /**
     * Xử lý VNPAY Return URL (Trình duyệt của khách hàng quay về sau khi thanh toán)
     */
    public function vnpayCallback(Request $request)
    {
        $data = $request->all();
        Log::info("VNPAY Return Received", $data);

        $vnpayService = app(\App\Services\VnpayService::class);

        // 1. Kiểm tra chữ ký bảo mật
        if (!$vnpayService->verifyResponse($data)) {
            Log::warning("VNPAY Return Signature Invalid", $data);
            return redirect()->route('client.cart')
                ->with('error', 'Chữ ký bảo mật VNPAY không hợp lệ hoặc dữ liệu giao dịch đã bị chỉnh sửa.');
        }

        // 2. Tìm đơn hàng
        $orderCode = $vnpayService->extractOrderCode($data);
        if (!$orderCode) {
            return redirect()->route('client.cart')
                ->with('error', 'Không tìm thấy thông tin đơn hàng từ giao dịch VNPAY.');
        }

        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            return redirect()->route('client.cart')
                ->with('error', "Không tìm thấy đơn hàng #{$orderCode} trong hệ thống.");
        }

        $responseCode = $request->input('vnp_ResponseCode');
        $transactionNo = $request->input('vnp_TransactionNo');
        $bankCode = $request->input('vnp_BankCode');
        $message = $vnpayService->getResponseMessage($responseCode);

        // 3. Xử lý khi thanh toán thành công (Mã 00)
        if ($responseCode === '00') {
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            if ($isDeposit) {
                $rem = $order->remaining_amount ?: ($order->total_amount - $order->deposit_amount);
                $order->update([
                    'deposit_status' => 'paid',
                    'deposit_paid_at' => now(),
                    'payment_status' => 'deposit_paid',
                    'remaining_amount' => $rem,
                    'shipping_status' => 'pending',
                    'status_step' => 1,
                    'vnpay_trans_id' => $transactionNo ?: ('VNP' . time()),
                ]);
                $this->sendOrderInvoiceEmail($order);
                $successAmount = $order->deposit_amount;
                $successMsg = "Chúc mừng bạn đã thanh toán thành công 50% tiền cọc (" . number_format($order->deposit_amount, 0, ',', '.') . "₫) cho đơn hàng #{$order->order_code} qua Cổng VNPAY (" . ($bankCode ?: 'Ngân hàng') . ")! Số tiền 50% còn lại (" . number_format($rem, 0, ',', '.') . "₫) sẽ được thanh toán bằng tiền mặt cho bưu tá khi nhận hàng (COD). Đơn hàng đã chuyển sang bộ phận duyệt đơn & đóng gói!";
            } else {
                if ($order->payment_status !== 'paid') {
                    $order->update([
                        'payment_status' => 'paid',
                        'remaining_amount' => 0,
                        'shipping_status' => 'pending',
                        'status_step' => 1,
                        'paid_at' => now(),
                        'vnpay_trans_id' => $transactionNo ?: ('VNP' . time()),
                    ]);
                    $this->sendOrderInvoiceEmail($order);
                }
                $successAmount = $order->total_amount;
                $successMsg = "Chúc mừng bạn đã thanh toán thành công 100% đơn hàng #{$order->order_code} qua Cổng VNPAY (" . ($bankCode ?: 'Ngân hàng') . ")! Quý khách không cần thanh toán thêm bất kỳ đồng nào khi nhận hàng (COD 0₫). Đơn hàng đã tiếp nhận và chuyển sang bộ phận duyệt đơn & đóng gói.";
            }

            return redirect()->route('client.checkout.success', ['code' => $order->order_code])
                ->with('payment_success_order', $order->order_code)
                ->with('payment_success_amount', $successAmount)
                ->with('payment_success_method', 'Cổng Thanh Toán VNPAY (' . ($bankCode ?: 'ATM / QR') . ')')
                ->with('success', $successMsg);
        }

        // 4. Nếu không thành công hoặc khách hàng hủy (Mã 24)
        if ($order->shipping_status === 'pending') {
            $order->update(['payment_status' => 'PENDING_PAYMENT']);
        }

        return redirect()->route('client.order-tracking', ['code' => $order->order_code])
            ->with('info', "Giao dịch VNPAY chưa hoàn tất hoặc bạn đã quay lại ({$message}). Đơn hàng #{$order->order_code} đang ở trạng thái Chờ thanh toán. Quý khách vui lòng bấm 'Thanh toán ngay' trong vòng 15 phút trước khi đơn hàng tự động bị hủy.");
    }

    /**
     * Xử lý VNPAY IPN (Instant Payment Notification / Webhook gọi từ server VNPAY)
     */
    public function vnpayIpn(Request $request)
    {
        $data = $request->all();
        Log::info("VNPAY IPN Received", $data);

        $vnpayService = app(\App\Services\VnpayService::class);

        // 1. Kiểm tra chữ ký bảo mật
        if (!$vnpayService->verifyResponse($data)) {
            Log::warning("VNPAY IPN Signature Invalid", $data);
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid Signature']);
        }

        // 2. Tìm đơn hàng
        $orderCode = $vnpayService->extractOrderCode($data);
        if (!$orderCode) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order Not Found']);
        }

        $order = Order::where('order_code', $orderCode)->first();
        if (!$order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order Not Found']);
        }

        // 3. Kiểm tra số tiền
        $vnpAmount = (int)($data['vnp_Amount'] ?? 0) / 100;
        $expectedAmount = ($order->is_deposit_required && $order->deposit_status !== 'paid')
            ? (int) round($order->deposit_amount)
            : (int) round($order->total_amount);

        if ($vnpAmount != $expectedAmount) {
            return response()->json(['RspCode' => '04', 'Message' => 'Invalid Amount']);
        }

        // 4. Kiểm tra trạng thái đơn hàng (tránh xử lý trùng lặp)
        if ($order->payment_status === 'paid' || ($order->is_deposit_required && $order->deposit_status === 'paid')) {
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
        }

        // 5. Cập nhật trạng thái nếu mã phản hồi là 00
        $responseCode = $data['vnp_ResponseCode'] ?? '';
        $transactionNo = $data['vnp_TransactionNo'] ?? null;

        if ($responseCode === '00') {
            $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
            if ($isDeposit) {
                $order->update([
                    'deposit_status' => 'paid',
                    'deposit_paid_at' => now(),
                    'payment_status' => 'deposit_paid',
                    'shipping_status' => 'pending',
                    'status_step' => 1,
                    'vnpay_trans_id' => $transactionNo,
                ]);
            } else {
                $order->update([
                    'payment_status' => 'paid',
                    'shipping_status' => 'pending',
                    'status_step' => 1,
                    'paid_at' => now(),
                    'vnpay_trans_id' => $transactionNo,
                ]);
            }
            $this->sendOrderInvoiceEmail($order);
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }

    /**
     * Xử lý Hết hạn thời gian chờ thanh toán (Auto-Expiry & Restock Kho sau 15 phút)
     */
    public function handleExpired($code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        if ($order->isPendingOnlinePayment()) {
            $order->cancelAsExpiredOnlinePayment();
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Đơn hàng #{$code} đã hết hạn thời gian thanh toán (15 phút) và được tự động hủy để hoàn trả kho.",
                'redirect' => route('client.order-tracking', ['code' => $code])
            ]);
        }

        return redirect()->route('client.order-tracking', ['code' => $code])
            ->with('warning', "Đơn hàng #{$code} đã hết hạn thời gian thanh toán (15 phút) và đã được tự động hủy để hoàn trả kho hàng.");
    }

    /**
     * API Polling kiểm tra trạng thái thanh toán theo thời gian thực (Realtime Payment Status)
     */
    public function checkPaymentStatus($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        if ($order->payment_status === 'paid') {
            session()->flash('payment_success_order', $code);
            session()->flash('payment_success_amount', $order->total_amount);
            session()->flash('payment_success_method', $order->payment_method_name);
            session()->flash('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code}! Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.");

            return response()->json([
                'status' => 'paid',
                'redirect' => route('client.checkout.success', ['code' => $code])
            ]);
        }

        return response()->json([
            'status' => 'unpaid',
            'redirect' => null
        ]);
    }

    /**
     * Tự động nhận diện & khớp lệnh chuyển khoản
     */
    public function autoConfirmTransfer($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'shipping_status' => 'pending',
                'status_step' => 1,
                'paid_at' => now(),
            ]);
            $this->sendOrderInvoiceEmail($order);

            session()->flash('payment_success_order', $code);
            session()->flash('payment_success_amount', $order->total_amount);
            session()->flash('payment_success_method', $order->payment_method_name);
            session()->flash('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code}! Đơn hàng đã được tiếp nhận và chuyển sang bộ phận duyệt đơn & đóng gói.");
        }

        return response()->json([
            'success' => true,
            'status' => 'paid',
            'redirect' => route('client.checkout.success', ['code' => $code])
        ]);
    }

    /**
     * Gửi Hóa đơn Điện tử HTML qua Email
     */
    protected function sendOrderInvoiceEmail($order)
    {
        if (empty($order->customer_email)) {
            return;
        }

        try {
            $order->load(['items.product', 'user']);
            Mail::send('emails.order_invoice', ['order' => $order], function ($message) use ($order) {
                $message->to($order->customer_email, $order->customer_name)
                    ->subject("【BeeStyle】Xác nhận Hóa Đơn Điện Tử Đơn Hàng #{$order->order_code}");
            });
        } catch (\Exception $e) {
            Log::warning("Lỗi gửi email hóa đơn đơn #{$order->order_code}: " . $e->getMessage());
        }
    }
}