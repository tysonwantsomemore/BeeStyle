<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'title' => 'required|string|max:255',
            'discount_type' => 'required|string|in:fixed,percent,shipping',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'total_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ]);

        Coupon::create([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_order_value' => $validated['min_order_value'] ?? 0,
            'max_discount_value' => $validated['max_discount_value'] ?? null,
            'total_limit' => $validated['total_limit'] ?? 1000,
            'used_count' => 0,
            'expires_at' => $validated['expires_at'] ?? now()->addMonths(6),
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Đã tạo mã giảm giá mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'title' => 'required|string|max:255',
            'discount_type' => 'required|string|in:fixed,percent,shipping',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'total_limit' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $coupon->update([
            'code' => strtoupper($validated['code']),
            'title' => $validated['title'],
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'min_order_value' => $validated['min_order_value'] ?? 0,
            'total_limit' => $validated['total_limit'] ?? $coupon->total_limit,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : $coupon->is_active,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Đã cập nhật mã giảm giá thành công!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Đã xóa mã voucher thành công!');
    }
}
