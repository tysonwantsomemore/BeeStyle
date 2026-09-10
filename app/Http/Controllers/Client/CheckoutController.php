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
            'ward' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cod,online,momo,zalopay,vnpay,vietqr',
        ]);

        $user = Auth::user();
        $orderCode = 'BEE-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        // Tạo Snapshot địa chỉ bất biến tại thời điểm chốt đơn
        $addressService = app(\App\Services\Address\AddressService::class);
        $addressSnapshot = $addressService->createOrderAddressSnapshot($validated);

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

            $depositPolicy = CartService::checkDepositPolicy($cartData['items'], $verifiedTotal, $user);
            $isDepositRequired = $depositPolicy['is_required'];
            $depositAmount = $depositPolicy['deposit_amount'];
            $remainingAmount = $depositPolicy['remaining_amount'];

            $orderNotes = $validated['notes'] ?? null;
            $adminNotes = null;
            if ($isDepositRequired) {
                $depositNotice = "[CHÍNH SÁCH ĐẶT CỌC 50%: {$depositPolicy['reason']} - Tiền cọc: " . number_format($depositAmount, 0, ',', '.') . "₫, Còn lại thu COD: " . number_format($remainingAmount, 0, ',', '.') . "₫]";
                $adminNotes = $depositNotice;
                $orderNotes = $orderNotes ? "{$orderNotes} | {$depositNotice}" : $depositNotice;
            }

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'] ?? 'Hồ Chí Minh',
                'district' => $validated['district'] ?? '',
                'ward' => $validated['ward'] ?? '',
                'shipping_address_snapshot' => $addressSnapshot,
                'notes' => $orderNotes,
                'admin_notes' => $adminNotes,
                'payment_method' => $validated['payment_method'],
                'payment_status' => $paymentStatus,
                'shipping_status' => 'pending',
                'shipping_carrier' => 'Giao Hàng Tiết Kiệm (GHTK)',
                'tracking_code' => 'GHTK-' . strtoupper(\Illuminate\Support\Str::random(8)),
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