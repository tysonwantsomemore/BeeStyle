<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartData = CartService::getCart();
        $coupons = Coupon::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderBy('min_order_value', 'asc')
            ->get();

        // Lấy danh sách 4 sản phẩm gợi ý mua kèm để gom đơn Freeship
        $cartProductIds = array_column($cartData['items'], 'product_id');
        $crossSellProducts = Product::with(['category', 'variants'])
            ->active()
            ->whereNotIn('id', $cartProductIds)
            ->orderBy('price', 'asc')
            ->take(4)
            ->get();

        return view('client.cart', [
            'cartItems' => $cartData['items'],
            'cartCount' => $cartData['count'],
            'subtotal' => $cartData['subtotal'],
            'discount' => $cartData['discount'],
            'shipping' => $cartData['shipping'],
            'total' => $cartData['total'],
            'appliedCoupon' => $cartData['coupon'],
            'freeShippingThreshold' => $cartData['free_shipping_threshold'],
            'freeShippingNeeded' => $cartData['free_shipping_needed'],
            'freeShippingPercent' => $cartData['free_shipping_percent'],
            'isFreeShipping' => $cartData['is_free_shipping'],
            'hasOutOfStockItems' => $cartData['has_out_of_stock_items'],
            'coupons' => $coupons,
            'crossSellProducts' => $crossSellProducts,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1|max:10',
            'color' => 'nullable|string',
            'size' => 'nullable|string',
        ]);

        $result = CartService::add(
            (int)$request->product_id,
            (int)($request->quantity ?? 1),
            $request->color,
            $request->size,
            $request->variant_id ? (int)$request->variant_id : null
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge($result, [
                'cart' => CartService::getCart()
            ]));
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        if ($request->has('buy_now')) {
            return redirect()->route('client.checkout');
        }

        return back()->with('success', $result['message']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        $result = CartService::update($request->key, (int)$request->quantity);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge($result, [
                'cart' => CartService::getCart()
            ]));
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove(Request $request, $key)
    {
        $result = CartService::remove($key);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge($result, [
                'cart' => CartService::getCart()
            ]));
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function saveForLater(Request $request, $key)
    {
        $result = CartService::saveForLater($key);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        return back()->with($result['success'] ? 'success' : 'error', $result['message']);
    }

    public function clear()
    {
        CartService::clear();
        return redirect()->route('client.cart')->with('success', 'Đã xóa toàn bộ sản phẩm trong giỏ hàng!');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = CartService::applyCoupon($request->code);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge($result, [
                'cart' => CartService::getCart()
            ]));
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function removeCoupon(Request $request)
    {
        CartService::removeCoupon();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã hủy áp dụng mã giảm giá!',
                'cart' => CartService::getCart()
            ]);
        }

        return back()->with('success', 'Đã hủy áp dụng mã giảm giá!');
    }
}
