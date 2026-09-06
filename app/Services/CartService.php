<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'beestyle_cart';
    const COUPON_SESSION_KEY = 'beestyle_applied_coupon';

    /**
     * Lấy toàn bộ danh sách sản phẩm trong giỏ hàng từ session và tính toán tổng tiền
     */
    public static function getCart(): array
    {
        $rawCart = Session::get(self::CART_SESSION_KEY, []);
        $items = [];
        $subtotal = 0;
        $hasOutOfStockItems = false;

        foreach ($rawCart as $key => $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $itemSubtotal;

            // Kiểm tra tồn kho thực tế realtime từ DB
            $currentStock = $item['stock'] ?? 10;
            if (!empty($item['variant_id'])) {
                $v = \App\Models\ProductVariant::find($item['variant_id']);
                if ($v) $currentStock = $v->stock;
            } elseif (!empty($item['product_id'])) {
                if (!empty($item['color']) && !empty($item['size'])) {
                    $v = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                        ->where('color', $item['color'])
                        ->where('size', $item['size'])
                        ->first();
                    if ($v) {
                        $currentStock = $v->stock;
                        $item['variant_id'] = $v->id;
                    } else {
                        $p = \App\Models\Product::find($item['product_id']);
                        if ($p) $currentStock = $p->stock;
                    }
                } else {
                    $p = \App\Models\Product::find($item['product_id']);
                    if ($p) $currentStock = $p->stock;
                }
            }

            $isOutOfStock = ($currentStock <= 0);
            $hasStockWarning = ($item['quantity'] > $currentStock && !$isOutOfStock);
            if ($isOutOfStock) $hasOutOfStockItems = true;

            $items[$key] = array_merge($item, [
                'subtotal' => $itemSubtotal,
                'subtotal_formatted' => number_format($itemSubtotal, 0, ',', '.') . '₫',
                'price_formatted' => number_format($item['price'], 0, ',', '.') . '₫',
                'current_stock' => $currentStock,
                'is_out_of_stock' => $isOutOfStock,
                'has_stock_warning' => $hasStockWarning,
            ]);
        }

        // Tính toán giảm giá từ mã coupon áp dụng
        $couponData = Session::get(self::COUPON_SESSION_KEY, null);
        $discount = 0;
        $coupon = null;

        if ($couponData && !empty($couponData['code'])) {
            $coupon = Coupon::where('code', $couponData['code'])->first();
            if ($coupon && $coupon->isValidForOrder($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                self::removeCoupon();
                $coupon = null;
            }
        }

        // Phí vận chuyển: Miễn phí giao hàng cho đơn từ 300.000đ trở lên, dưới mức đó phí 30.000đ
        $freeShippingThreshold = 300000;
        $isFreeShipping = ($subtotal >= $freeShippingThreshold || ($coupon && $coupon->discount_type === 'shipping') || $subtotal == 0);
        $shipping = $isFreeShipping ? 0 : 30000;
        $amountNeededForFreeShipping = max(0, $freeShippingThreshold - $subtotal);
        $freeShippingPercent = $subtotal > 0 ? min(100, (int)round(($subtotal / $freeShippingThreshold) * 100)) : 0;

        $total = max(0, $subtotal - $discount + $shipping);

        return [
            'items' => $items,
            'count' => array_sum(array_column($items, 'quantity')),
            'subtotal' => $subtotal,
            'subtotal_formatted' => number_format($subtotal, 0, ',', '.') . '₫',
            'discount' => $discount,
            'discount_formatted' => number_format($discount, 0, ',', '.') . '₫',
            'shipping' => $shipping,
            'shipping_formatted' => $shipping == 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . '₫',
            'total' => $total,
            'total_formatted' => number_format($total, 0, ',', '.') . '₫',
            'coupon' => $coupon,
            'free_shipping_threshold' => $freeShippingThreshold,
            'free_shipping_needed' => $amountNeededForFreeShipping,
            'free_shipping_needed_formatted' => number_format($amountNeededForFreeShipping, 0, ',', '.') . '₫',
            'free_shipping_percent' => $freeShippingPercent,
            'is_free_shipping' => $isFreeShipping,
            'has_out_of_stock_items' => $hasOutOfStockItems,
        ];
    }

    /**
     * Tính tổng số lượng tất cả sản phẩm có trong giỏ hàng
     */
    public static function count(): int
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Thêm sản phẩm hoặc biến thể sản phẩm vào giỏ hàng
     */
    public static function add(int $productId, int $quantity = 1, ?string $color = null, ?string $size = null, ?int $variantId = null): array
    {
        $product = Product::with('variants')->active()->find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'];
        }

        $variant = null;
        if ($variantId) {
            $variant = $product->variants()->where('id', $variantId)->where('status', 'active')->first();
        } elseif ($color && $size) {
            $variant = $product->variants()->where('color', $color)->where('size', $size)->where('status', 'active')->first();
        }

        $price = $variant ? $variant->price : $product->price;
        $originalPrice = $variant ? ($variant->original_price ?? $product->original_price) : $product->original_price;
        $stock = $variant ? $variant->stock : $product->stock;
        $sku = $variant ? $variant->sku : $product->sku;
        $image = ($variant && $variant->image) ? $variant->image : $product->image;
        $selectedColor = $variant ? $variant->color : ($color ?? ($product->colors[0] ?? 'Tiêu chuẩn'));
        $selectedSize = $variant ? $variant->size : ($size ?? ($product->sizes[0] ?? 'Tiêu chuẩn'));

        if ($stock <= 0) {
            return ['success' => false, 'message' => "Phiên bản \"{$selectedColor} - Size {$selectedSize}\" hiện đã hết hàng trong kho."];
        }

        if ($quantity > $stock) {
            return ['success' => false, 'message' => "Phiên bản này trong kho chỉ còn {$stock} sản phẩm. Quý khách vui lòng chọn tối đa {$stock} cái."];
        }

        // Kiểm tra xem sản phẩm có đang trong chương trình Ưu Đãi Trong Ngày (Daily Deal) không
        $runningDeal = \App\Models\DailyDeal::where('product_id', $product->id)->runningNow()->first();
        $isDailyDeal = false;
        $dealId = null;
        $dealDiscount = 0;

        if ($runningDeal) {
            $isDailyDeal = true;
            $dealId = $runningDeal->id;
            $dealDiscount = $runningDeal->discount_percent;
            $originalPrice = $originalPrice ?: $price;
            $price = max(0, (int) round($price * (1 - ($runningDeal->discount_percent / 100))));
        }

        // Giới hạn tối đa 10 sản phẩm mỗi lần mua hoặc không vượt quá tồn kho
        $maxAllowed = min(10, $stock);
        $quantity = min($maxAllowed, max(1, $quantity));

        $cartKey = $variant ? "v_{$variant->id}" : "{$productId}_{$selectedColor}_{$selectedSize}";
        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $maxAllowed) {
                $canAdd = max(0, $maxAllowed - $cart[$cartKey]['quantity']);
                if ($canAdd <= 0) {
                    return ['success' => false, 'message' => "Bạn đã có {$cart[$cartKey]['quantity']} sản phẩm này trong giỏ hàng (đã đạt số lượng tồn kho tối đa cho phép đặt)."];
                }
                return ['success' => false, 'message' => "Bạn đã có {$cart[$cartKey]['quantity']} sản phẩm này trong giỏ. Trong kho chỉ còn {$stock} cái, bạn chỉ có thể thêm tối đa {$canAdd} cái nữa."];
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            $cart[$cartKey] = [
                'key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
                'deal_id' => $dealId,
                'is_daily_deal' => $isDailyDeal,
                'deal_discount' => $dealDiscount,
                'name' => $product->name,
                'sku' => $sku,
                'slug' => $product->slug,
                'image' => $image,
                'price' => $price,
                'original_price' => $originalPrice,
                'color' => $selectedColor,
                'size' => $selectedSize,
                'quantity' => $quantity,
                'stock' => $stock,
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        $dealNotice = $isDailyDeal ? " (⚡ Ưu đãi Flash Sale -{$dealDiscount}%)" : "";
        return [
            'success' => true,
            'message' => "Đã thêm \"{$product->name} ({$selectedColor} - Size {$selectedSize})\"{$dealNotice} (x{$quantity}) vào giỏ hàng!",
            'cart_count' => self::count()
        ];
    }

    /**
     * Cập nhật số lượng của một mặt hàng trong giỏ hàng
     */
    public static function update(string $cartKey, int $quantity): array
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (!isset($cart[$cartKey])) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng.'];
        }

        if ($quantity <= 0) {
            return self::remove($cartKey);
        }

        $item = $cart[$cartKey];
        $realStock = 10;
        if (!empty($item['variant_id'])) {
            $v = \App\Models\ProductVariant::find($item['variant_id']);
            if ($v) $realStock = $v->stock;
        } elseif (!empty($item['product_id'])) {
            $v = \App\Models\ProductVariant::where('product_id', $item['product_id'])
                ->where('color', $item['color'] ?? '')
                ->where('size', $item['size'] ?? '')
                ->first();
            if ($v) $realStock = $v->stock;
            else {
                $p = \App\Models\Product::find($item['product_id']);
                if ($p) $realStock = $p->stock;
            }
        }

        $maxAllowed = min(10, $realStock);
        if ($quantity > $maxAllowed) {
            return ['success' => false, 'message' => "Kho chỉ còn {$realStock} cái, không thể tăng vượt quá tồn kho."];
        }

        $cart[$cartKey]['quantity'] = $quantity;
        Session::put(self::CART_SESSION_KEY, $cart);

        return ['success' => true, 'message' => 'Đã cập nhật số lượng giỏ hàng!'];
    }


    /**
     * Xóa một sản phẩm khỏi giỏ hàng
     */
    public static function remove(string $cartKey): array
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            Session::put(self::CART_SESSION_KEY, $cart);
            return ['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng!'];
        }

        return ['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng.'];
    }

    /**
     * Xóa toàn bộ giỏ hàng và hủy mã giảm giá trong session
     */
    public static function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
        Session::forget(self::COUPON_SESSION_KEY);
    }

    /**
     * Áp dụng mã giảm giá vào đơn hàng
     */
    public static function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', trim(strtoupper($code)))->first();
        if (!$coupon) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn.'];
        }

        $cart = self::getCart();
        if ($cart['subtotal'] < $coupon->min_order_value) {
            $minFormatted = number_format($coupon->min_order_value, 0, ',', '.');
            return ['success' => false, 'message' => "Mã này chỉ áp dụng cho đơn hàng từ {$minFormatted}₫ trở lên."];
        }

        if (!$coupon->isValidForOrder($cart['subtotal'])) {
            return ['success' => false, 'message' => 'Mã giảm giá không hợp lệ hoặc đã hết lượt sử dụng.'];
        }

        Session::put(self::COUPON_SESSION_KEY, [
            'code' => $coupon->code,
            'title' => $coupon->title,
        ]);

        return ['success' => true, 'message' => "Đã áp dụng mã giảm giá \"{$coupon->code}\" thành công!"];
    }

    /**
     * Hủy áp dụng mã giảm giá
     */
    public static function removeCoupon(): void
    {
        Session::forget(self::COUPON_SESSION_KEY);
    }

    /**
     * Chuyển một sản phẩm từ giỏ hàng sang danh sách Yêu thích (Save for later)
     */
    public static function saveForLater(string $cartKey): array
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        if (!isset($cart[$cartKey])) {
            return ['success' => false, 'message' => 'Sản phẩm không có trong giỏ hàng.'];
        }

        $item = $cart[$cartKey];
        $productId = (int)($item['product_id'] ?? 0);

        if ($productId > 0) {
            // Thêm vào danh sách yêu thích nếu chưa có
            if (!WishlistService::isFavorite($productId)) {
                WishlistService::toggle($productId);
            }
        }

        // Xóa khỏi giỏ hàng
        unset($cart[$cartKey]);
        Session::put(self::CART_SESSION_KEY, $cart);

        return [
            'success' => true,
            'message' => "Đã lưu \"{$item['name']}\" vào danh sách yêu thích để mua sau!",
            'cart' => self::getCart(),
            'wishlist_count' => WishlistService::count(),
        ];
    }
}
