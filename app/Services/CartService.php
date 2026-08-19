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
     * Lấy toàn bộ danh sách sản phẩm trong giỏ hàng từ session và tính toán tổng tiền
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
            return ['success' => false, 'message' => 'Phiên bản sản phẩm đã chọn hiện đã hết hàng trong kho.'];
        }

        $cartKey = $variant ? "v_{$variant->id}" : "{$productId}_{$selectedColor}_{$selectedSize}";
        $cart = Session::get(self::CART_SESSION_KEY, []);

        if (isset($cart[$cartKey])) {
            $newQuantity = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQuantity > $stock) {
                return ['success' => false, 'message' => "Số lượng trong kho chỉ còn {$stock} sản phẩm."];
            }
            $cart[$cartKey]['quantity'] = $newQuantity;
        } else {
            if ($quantity > $stock) {
                return ['success' => false, 'message' => "Số lượng trong kho chỉ còn {$stock} sản phẩm."];
            }
            $cart[$cartKey] = [
                'key' => $cartKey,
                'product_id' => $product->id,
                'variant_id' => $variant ? $variant->id : null,
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
        return [
            'success' => true,
            'message' => "Đã thêm \"{$product->name} ({$selectedColor} - Size {$selectedSize})\" vào giỏ hàng!",
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

        $product = Product::find($cart[$cartKey]['product_id']);
        if ($product && $quantity > $product->stock) {
            return ['success' => false, 'message' => "Số lượng vượt quá tồn kho (còn {$product->stock} sản phẩm)."];
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
}
