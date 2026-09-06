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
    public function index()
    {
        $cartData = CartService::getCart();

        if (empty($cartData['items'])) {
            return redirect()->route('client.products.index')
                ->with('error', 'Giỏ hàng của bạn đang trống! Hãy chọn sản phẩm trước khi thanh toán.');
        }

        $user = Auth::user();
        $addresses = $user ? $user->addresses : collect();
        $defaultAddress = $user ? ($user->defaultAddress ?? $addresses->first()) : null;

        return view('client.checkout', [
            'cartItems' => $cartData['items'],
            'cartCount' => $cartData['count'],
            'subtotal' => $cartData['subtotal'],
            'discount' => $cartData['discount'],
            'shipping' => $cartData['shipping'],
            'total' => $cartData['total'],
            'appliedCoupon' => $cartData['coupon'],
            'user' => $user,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
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
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cod,online,momo,zalopay,vnpay,vietqr',
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
                    $deal = \App\Models\DailyDeal::where('id', $item['deal_id'])->where('status', 'active')->first();
                    if ($deal) {
                        $itemPrice = (int)$deal->deal_price;
                    }
                }
                $verifiedSubtotal += $itemPrice * (int)$item['quantity'];
            }

            $verifiedDiscount = 0;
            if ($cartData['coupon']) {
                $couponDb = Coupon::where('code', $cartData['coupon']->code)->where('status', 'active')->first();
                if ($couponDb && $couponDb->isValidForOrder($verifiedSubtotal)) {
                    $verifiedDiscount = $couponDb->calculateDiscount($verifiedSubtotal);
                }
            }

            $verifiedShipping = (int)$cartData['shipping'];
            $verifiedTotal = max(0, $verifiedSubtotal - $verifiedDiscount + $verifiedShipping);

            // Xác định payment_status: MoMo cần đợi webhook/callback, COD & VietQR là chưa trả, còn lại tùy cấu hình
            $paymentStatus = match ($validated['payment_method']) {
                'momo' => 'PENDING_PAYMENT',
                'cod', 'vietqr', 'online', 'zalopay' => 'unpaid',
                default => 'unpaid',
            };

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'] ?? 'Hồ Chí Minh',
                'district' => $validated['district'] ?? '',
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'shipping_status' => 'pending',
                'status_step' => 1,
                'subtotal' => $verifiedSubtotal,
                'discount_amount' => $verifiedDiscount,
                'shipping_fee' => $verifiedShipping,
                'total_amount' => $verifiedTotal,
                'coupon_code' => $cartData['coupon'] ? $cartData['coupon']->code : null,
            ]);

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

                // Cập nhật số lượng đã bán của chương trình Ưu Đãi Trong Ngày (Daily Deal)
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

            // Nếu chọn Thanh toán Online (Napas / Visa) -> Chuyển sang Cổng Online Gateway
            if ($validated['payment_method'] === 'online') {
                return redirect()->route('client.checkout.online', ['code' => $orderCode]);
            }

            // Nếu chọn Thanh toán trực tuyến qua MoMo -> Tạo giao dịch và chuyển hướng Deep Link / payUrl
            if ($validated['payment_method'] === 'momo') {
                $momoService = app(MomoService::class);
                $momoResult = $momoService->createPayment($order);

                if (!empty($momoResult['success']) && !empty($momoResult['payUrl'])) {
                    if ($request->ajax() || $request->wantsJson()) {
                        return response()->json([
                            'success' => true,
                            'order_code' => $orderCode,
                            'deeplink' => $momoResult['deeplink'] ?? null,
                            'payUrl' => $momoResult['payUrl'],
                        ]);
                    }

                    $userAgent = $request->userAgent() ?? '';
                    $isMobile = preg_match('/(android|iphone|ipad|ipod|mobile)/i', $userAgent);

                    if ($isMobile && !empty($momoResult['deeplink'])) {
                        return redirect()->away($momoResult['deeplink']);
                    }

                    return redirect()->away($momoResult['payUrl']);
                }

                return redirect()->route('client.checkout')
                    ->with('error', $momoResult['message'] ?? 'Không thể khởi tạo giao dịch MoMo Sandbox. Vui lòng thử lại sau giây lát.');
            }

            // Nếu chọn Ví ZaloPay -> Chuyển sang Cổng Thanh Toán ZaloPay Gateway
            if ($validated['payment_method'] === 'zalopay') {
                return redirect()->route('client.checkout.zalopay', ['code' => $orderCode]);
            }

            // Với đơn COD & VietQR: gửi email hóa đơn ngay và chuyển sang trang tra cứu
            $this->sendOrderInvoiceEmail($order);

            return redirect()->route('client.order-tracking', ['code' => $orderCode])
                ->with('success', "Chúc mừng bạn đã đặt hàng thành công tại BeeStyle! Mã đơn hàng của bạn là {$orderCode}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Cổng Thanh Toán Trực Tuyến Online Banking Napas 247
     */
    public function onlineGateway($code)
    {
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán thành công!");
        }
        if ($order->shipping_status === 'cancelled') {
            return redirect()->route('client.cart')
                ->with('warning', "Đơn hàng #{$code} đã bị hủy do hết hạn thanh toán.");
        }

        return view('client.payment.online', compact('order'));
    }

    /**
     * Xác nhận thanh toán Online Banking thành công
     */
    public function onlineSuccess($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 3,
            'paid_at' => now(),
            'confirmed_at' => $order->confirmed_at ?: now(),
            'processing_at' => now(),
        ]);
        $this->sendOrderInvoiceEmail($order);

        return redirect()->route('client.home')
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $order->total_amount)
            ->with('payment_success_method', 'Thanh toán Online (Techcombank Napas 247)')
            ->with('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code}! Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.");
    }

    /**
     * Cổng Thanh Toán Trực Tuyến Ví MoMo
     */
    public function momoGateway($code)
    {
        $order = Order::with(['items.product'])->where('order_code', $code)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán qua Ví MoMo thành công!");
        }
        if ($order->shipping_status === 'cancelled') {
            return redirect()->route('client.cart')
                ->with('warning', "Đơn hàng #{$code} đã bị hủy do hết hạn thanh toán.");
        }

        return view('client.payment.momo', compact('order'));
    }

    /**
     * Xác nhận thanh toán Ví MoMo thành công
     */
    public function momoSuccess($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 3,
            'paid_at' => now(),
            'confirmed_at' => $order->confirmed_at ?: now(),
            'processing_at' => now(),
        ]);
        $this->sendOrderInvoiceEmail($order);

        return redirect()->route('client.home')
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $order->total_amount)
            ->with('payment_success_method', 'Ví Điện Tử MoMo')
            ->with('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code} qua Ví MoMo! Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.");
    }

    /**
     * Khởi tạo và chuyển hướng người dùng sang Cổng Thanh Toán MoMo Sandbox
     * Hỗ trợ thanh toán lại (Retry) kể cả khi đơn đã bị hủy trước đó
     */
    public function momoRedirectSandbox($code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        if (strtoupper((string)$order->payment_status) === 'PAID') {
            return redirect()->route('client.order-tracking', ['code' => $code])
                ->with('success', "Đơn hàng #{$code} đã được thanh toán thành công!");
        }

        // Nếu đơn hàng đã bị hủy, kiểm tra lại tồn kho trước khi cho phép thanh toán lại
        if ($order->shipping_status === 'cancelled' || strtoupper((string)$order->payment_status) === 'CANCELLED') {
            // Kiểm tra tồn kho từng sản phẩm
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

            // Tái đặt chỗ kho hàng
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
     * Xử lý MoMo Sandbox Callback (Khách hàng quay lại từ cổng MoMo)
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

        // MoMo resultCode 0 = Thành công
        if ($resultCode === 0) {
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'shipping_status' => 'processing',
                    'status_step' => 3,
                    'paid_at' => now(),
                    'confirmed_at' => $order->confirmed_at ?: now(),
                    'processing_at' => now(),
                ]);
                $this->sendOrderInvoiceEmail($order);
            }

            return redirect()->route('client.home')
                ->with('payment_success_order', $order->order_code)
                ->with('payment_success_amount', $order->total_amount)
                ->with('payment_success_method', 'Cổng Thanh Toán MoMo Sandbox')
                ->with('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$order->order_code} qua Cổng MoMo Sandbox! Kho hàng BeeStyle đã tiếp nhận và đang xử lý đóng gói sản phẩm.");
        }

        // Khách hàng hủy giao dịch trên MoMo hoặc giao dịch thất bại
        return redirect()->route('client.checkout.momo', ['code' => $order->order_code])
            ->with('error', "Giao dịch MoMo chưa hoàn tất hoặc bạn đã hủy ({$message}). Bạn có thể quét mã thanh toán lại hoặc chọn phương thức khác.");
    }

    /**
     * Xử lý MoMo Sandbox IPN Webhook (MoMo gửi thông báo trạng thái bất đồng bộ)
     */
    public function momoIpn(Request $request)
    {
        $data = $request->all();
        Log::info("MoMo Sandbox IPN Received", $data);

        $momoService = app(MomoService::class);

        // Kiểm tra chữ ký số từ MoMo
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
            if ($order->payment_status !== 'paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'shipping_status' => 'processing',
                    'status_step' => 3,
                    'paid_at' => now(),
                    'confirmed_at' => $order->confirmed_at ?: now(),
                    'processing_at' => now(),
                ]);
                $this->sendOrderInvoiceEmail($order);
            }
        }

        // Phản hồi cho MoMo IPN (HTTP 204 No Content theo chuẩn MoMo v2)
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
        $order->update([
            'payment_status' => 'paid',
            'shipping_status' => 'processing',
            'status_step' => 3,
            'paid_at' => now(),
            'confirmed_at' => $order->confirmed_at ?: now(),
            'processing_at' => now(),
        ]);
        $this->sendOrderInvoiceEmail($order);

        return redirect()->route('client.home')
            ->with('payment_success_order', $code)
            ->with('payment_success_amount', $order->total_amount)
            ->with('payment_success_method', 'Ví Điện Tử ZaloPay')
            ->with('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code} qua Ví ZaloPay! Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.");
    }

    /**
     * Xử lý Hết hạn thời gian chờ thanh toán (Auto-Expiry & Restock Kho)
     */
    public function handleExpired($code)
    {
        $order = Order::with('items')->where('order_code', $code)->firstOrFail();

        if ($order->payment_status !== 'paid' && $order->shipping_status === 'pending') {
            DB::transaction(function () use ($order) {
                $order->update([
                    'shipping_status' => 'cancelled',
                    'status_step' => 0,
                    'cancelled_at' => now(),
                    'cancelled_by' => 'system',
                    'cancel_reason' => 'Đơn hàng tự động hủy do hết hạn thời gian chờ thanh toán (10 phút)',
                ]);

                // Hoàn trả số lượng tồn kho sản phẩm & biến thể
                foreach ($order->items as $item) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                    Product::where('id', $item->product_id)->decrement('sold_count', $item->quantity);

                    if (!empty($item->color) && !empty($item->size)) {
                        \App\Models\ProductVariant::where('product_id', $item->product_id)
                            ->where('color', $item->color)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);
                    }
                }

                // Hoàn lại lượt dùng coupon nếu có
                if ($order->coupon_code) {
                    $coupon = Coupon::where('code', $order->coupon_code)->first();
                    if ($coupon && $coupon->used_count > 0) {
                        $coupon->decrement('used_count');
                    }
                }
            });
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Đơn hàng #{$code} đã hết hạn thời gian thanh toán và được tự động hủy để hoàn trả kho.",
                'redirect' => route('client.cart')
            ]);
        }

        return redirect()->route('client.cart')
            ->with('warning', "Đơn hàng #{$code} đã hết hạn thời gian thanh toán (10 phút) và đã được tự động hủy để hoàn trả kho hàng.");
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
                'redirect' => route('client.home')
            ]);
        }

        return response()->json([
            'status' => 'unpaid',
            'redirect' => null
        ]);
    }

    /**
     * Tự động nhận diện & khớp lệnh chuyển khoản (Webhook / Realtime Banking Auto-Match)
     */
    public function autoConfirmTransfer($code)
    {
        $order = Order::where('order_code', $code)->firstOrFail();

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'shipping_status' => 'processing',
                'status_step' => 3,
                'paid_at' => now(),
                'confirmed_at' => $order->confirmed_at ?: now(),
                'processing_at' => now(),
            ]);
            $this->sendOrderInvoiceEmail($order);

            session()->flash('payment_success_order', $code);
            session()->flash('payment_success_amount', $order->total_amount);
            session()->flash('payment_success_method', $order->payment_method_name);
            session()->flash('success', "Chúc mừng bạn đã thanh toán thành công đơn hàng #{$code}! Kho hàng BeeStyle đã tiếp nhận và đang đóng gói sản phẩm để chuyển đến bạn sớm nhất.");
        }

        return response()->json([
            'success' => true,
            'status' => 'paid',
            'redirect' => route('client.home')
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