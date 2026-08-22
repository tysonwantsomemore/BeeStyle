<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    const WISHLIST_SESSION_KEY = 'beestyle_wishlist';

    /**
     * Lấy danh sách ID các sản phẩm yêu thích từ Session
     */
    public static function getWishlistIds(): array
    {
        return Session::get(self::WISHLIST_SESSION_KEY, []);
    }

    /**
     * Lấy danh sách các Product model yêu thích
     */
    public static function getWishlistProducts()
    {
        $ids = self::getWishlistIds();
        if (empty($ids)) {
            return collect();
        }

        return Product::with(['category', 'brand', 'variants'])
            ->whereIn('id', $ids)
            ->where('status', 'active')
            ->get();
    }

    /**
     * Đếm số lượng sản phẩm yêu thích
     */
    public static function count(): int
    {
        return count(self::getWishlistIds());
    }

    /**
     * Kiểm tra xem sản phẩm có trong danh sách yêu thích không
     */
    public static function isFavorite(int $productId): bool
    {
        $ids = self::getWishlistIds();
        return in_array($productId, $ids);
    }

    /**
     * Thêm hoặc xóa sản phẩm khỏi danh sách yêu thích (Toggle)
     */
    public static function toggle(int $productId): array
    {
        $product = Product::active()->find($productId);
        if (!$product) {
            return [
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc đã ngừng kinh doanh.',
                'is_favorite' => false,
                'count' => self::count()
            ];
        }

        $ids = self::getWishlistIds();
        $isFavorite = false;

        if (in_array($productId, $ids)) {
            // Đã thích -> Bỏ thích
            $ids = array_values(array_diff($ids, [$productId]));
            $isFavorite = false;
            $message = "Đã bỏ yêu thích \"{$product->name}\"";
        } else {
            // Chưa thích -> Thêm thích
            $ids[] = $productId;
            $isFavorite = true;
            $message = "Đã thêm \"{$product->name}\" vào mục yêu thích!";
        }

        Session::put(self::WISHLIST_SESSION_KEY, $ids);

        return [
            'success' => true,
            'is_favorite' => $isFavorite,
            'count' => count($ids),
            'message' => $message,
            'product_name' => $product->name
        ];
    }

    /**
     * Xóa 1 sản phẩm khỏi danh sách yêu thích
     */
    public static function remove(int $productId): array
    {
        $ids = self::getWishlistIds();
        if (in_array($productId, $ids)) {
            $ids = array_values(array_diff($ids, [$productId]));
            Session::put(self::WISHLIST_SESSION_KEY, $ids);
        }

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.',
            'count' => count($ids)
        ];
    }

    /**
     * Xóa toàn bộ danh sách yêu thích
     */
    public static function clear(): array
    {
        Session::forget(self::WISHLIST_SESSION_KEY);
        return [
            'success' => true,
            'message' => 'Đã xóa toàn bộ danh sách sản phẩm yêu thích.',
            'count' => 0
        ];
    }
}
