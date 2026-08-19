<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
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
            ->get();

        return view('client.cart', [
            'cartItems' => $cartData['items'],
            'cartCount' => $cartData['count'],
            'subtotal' => $cartData['subtotal'],
            'discount' => $cartData['discount'],
            'shipping' => $cartData['shipping'],
            'total' => $cartData['total'],
            'appliedCoupon' => $cartData['coupon'],
            'coupons' => $coupons,
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'variant_id' => 'nullable|integer|exists:product_variants,id',
            'quantity' => 'nullable|integer|min:1',
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
            return response()->json($result);
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
            'quantity' => 'required|integer|min:0',
        ]);

        $result = CartService::update($request->key, (int)$request->quantity);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove($key)
    {
        $result = CartService::remove($key);
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

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function removeCoupon()
    {
        CartService::removeCoupon();
        return back()->with('success', 'Đã hủy áp dụng mã giảm giá!');
    }
}
