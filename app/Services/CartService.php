<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    const CART_SESSION_KEY = 'beestyle_cart';
    const COUPON_SESSION_KEY = 'beestyle_applied_coupon';

    /**
     * Get all cart items from session
     */
    public static function getCart(): array
    {
        $rawCart = Session::get(self::CART_SESSION_KEY, []);
        $items = [];
        $subtotal = 0;

        foreach ($rawCart as $key => $item) {
            $itemSubtotal = $item['price'] * $item['quantity'];
            $subtotal += $itemSubtotal;
            $items[$key] = array_merge($item, [
                'subtotal' => $itemSubtotal
            ]);
        }

        // Coupon calculation
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

        // Shipping fee: Free ship for orders >= 300,000đ, otherwise 30,000đ
        $shipping = ($subtotal >= 300000 || $subtotal == 0) ? 0 : 30000;
        if ($coupon && $coupon->discount_type === 'shipping') {
            $shipping = 0;
        }

        $total = max(0, $subtotal - $discount + $shipping);

        return [
            'items' => $items,
            'count' => array_sum(array_column($items, 'quantity')),
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
            'coupon' => $coupon,
        ];
    }

    /**
     * Total quantity of items in cart
     */
    public static function count(): int
    {
        $cart = Session::get(self::CART_SESSION_KEY, []);
        return array_sum(array_column($cart, 'quantity'));
    }

    /**
     * Add product to cart
     */
    public static function add(int $productId, int $quantity = 1, ?string $color = null, ?string $size = null): array
    {
        $product = Product::active()->find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.'];
        }

        if ($product->stock <= 0) {
            return ['success' => false, 'message' => 'Sản phẩm hiện đã hết hàng trong kho.'];
        }

        $selectedColor = $color ?? ($product->colors[0] ?? 'Mặc định');
        $selectedSize = $size ?? ($product->sizes[0] ?? 'Free Size');
        $cartKey = "{$productId}_{$selectedColor}_{$selectedSize}";

        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $product->stock) {
                return ['success' => false, 'message' => "Số lượng trong kho chỉ còn {$product->stock} sản phẩm."];
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $product->stock) {
                return ['success' => false, 'message' => "Số lượng trong kho chỉ còn {$product->stock} sản phẩm."];
            }
            $cart[$cartKey] = [
                'key' => $cartKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'slug' => $product->slug,
                'image' => $product->image,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'color' => $selectedColor,
                'size' => $selectedSize,
                'quantity' => $quantity,
                'stock' => $product->stock,
            ];
        }

        Session::put(self::CART_SESSION_KEY, $cart);
        return [
            'success' => true,
            'message' => "Đã thêm \"{$product->name}\" vào giỏ hàng!",
            'cart_count' => self::count()
        ];
    }

    /**
     * Update quantity of an item
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

        $product = Product::find($cart[$cartKey]['product_id']);
        if ($product && $quantity > $product->stock) {
            return ['success' => false, 'message' => "Số lượng vượt quá tồn kho (còn {$product->stock} sản phẩm)."];
        }

        $cart[$cartKey]['quantity'] = $quantity;
        Session::put(self::CART_SESSION_KEY, $cart);

        return ['success' => true, 'message' => 'Đã cập nhật số lượng giỏ hàng!'];
    }

    /**
     * Remove item from cart
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
     * Clear the whole cart
     */
    public static function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
        Session::forget(self::COUPON_SESSION_KEY);
    }

    /**
     * Apply coupon
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
     * Remove coupon
     */
    public static function removeCoupon(): void
    {
        Session::forget(self::COUPON_SESSION_KEY);
    }
}
