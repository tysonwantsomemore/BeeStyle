<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\WishlistService;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Hiển thị trang danh sách sản phẩm yêu thích
     */
    public function index()
    {
        $favoriteProducts = WishlistService::getWishlistProducts();
        $wishlistCount = WishlistService::count();

        return view('client.wishlist', [
            'products' => $favoriteProducts,
            'wishlistCount' => $wishlistCount
        ]);
    }

    /**
     * Toggle thêm hoặc bỏ yêu thích sản phẩm qua AJAX
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $result = WishlistService::toggle((int)$request->product_id);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with('success', $result['message']);
    }

    /**
     * Xóa 1 sản phẩm khỏi danh sách yêu thích
     */
    public function remove($id)
    {
        $result = WishlistService::remove((int)$id);
        return back()->with('success', $result['message']);
    }

    /**
     * Xóa toàn bộ danh sách yêu thích
     */
    public function clear()
    {
        $result = WishlistService::clear();
        return back()->with('success', $result['message']);
    }
}
