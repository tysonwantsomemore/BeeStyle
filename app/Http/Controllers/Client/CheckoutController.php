<?php

namespace App\Http\Controllers\Client;
  
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'shipping_address' => 'required|string|max:500',
            'city' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|string|in:cod,vietqr,momo,vnpay',
        ], [
            'customer_name.required' => 'Vui lòng nhập họ và tên người nhận hàng.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại người nhận.',
            'shipping_address.required' => 'Vui lòng cung cấp địa chỉ giao hàng chi tiết.',
            'payment_method.required' => 'Vui lòng chọn hình thức thanh toán.',
        ]);

        // Tạo mã đơn hàng duy nhất theo định dạng BEE-YYYYMMDD-XXXX
        $datePrefix = date('Ymd');
        $randomCode = strtoupper(Str::random(4));
        $orderCode = "BEE-{$datePrefix}-{$randomCode}";

        DB::beginTransaction();
        try {
            $user = Auth::user();

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => $user ? $user->id : null,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'] ?? 'Hồ Chí Minh',
                'district' => $validated['district'] ?? '',
                'notes' => $validated['notes'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => in_array($validated['payment_method'], ['cod', 'vietqr']) ? 'unpaid' : 'paid',
                'shipping_status' => 'pending',

                'status_step' => 1,
                'subtotal' => $cartData['subtotal'],
                'discount_amount' => $cartData['discount'],
                'shipping_fee' => $cartData['shipping'],
                'total_amount' => $cartData['total'],
                'coupon_code' => $cartData['coupon'] ? $cartData['coupon']->code : null,
            ]);

            // Lưu chi tiết từng món hàng trong đơn và trừ tồn kho
            foreach ($cartData['items'] as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'],
                    'color' => $item['color'],
                    'size' => $item['size'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                    'image' => $item['image'],
                ]);

                // Cập nhật tồn kho sản phẩm và tăng số lượng đã bán
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                    $product->increment('sold_count', $item['quantity']);
                }
            }

            // Cập nhật số lượt sử dụng mã giảm giá (nếu có)
            if ($cartData['coupon']) {
                $coupon = Coupon::find($cartData['coupon']->id);
                if ($coupon) {
                    $coupon->increment('used_count');
                }
            }

            // Tích điểm thưởng và cộng dồn tổng tiền mua sắm cho thành viên
            if ($user) {
                $earnedPoints = (int)floor($cartData['total'] / 10000);
                $user->increment('points', $earnedPoints);
                $user->increment('total_spent', $cartData['total']);
            }

            DB::commit();

            // Xóa sạch giỏ hàng trong session sau khi hoàn tất đặt hàng
            CartService::clear();

            return redirect()->route('client.order-tracking', ['code' => $orderCode])
                ->with('success', "Chúc mừng bạn đã đặt hàng thành công tại BeeStyle! Mã đơn hàng của bạn là {$orderCode}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi tạo đơn hàng: ' . $e->getMessage());
        }
    }
}
