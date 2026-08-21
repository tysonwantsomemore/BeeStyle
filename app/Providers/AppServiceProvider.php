<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Đăng ký các dịch vụ của ứng dụng (Services).
     */
    public function register(): void
    {
        //
    }

    /**
     * Khởi tạo và nạp cấu hình ban đầu cho các dịch vụ ứng dụng.
     */
    public function boot(): void
    {
        // Chia sẻ danh sách thông báo đầy đủ của Shop cho toàn bộ view Client
        View::composer(['layouts.client', 'client.*'], function ($view) {
            $pendingReviewItems = collect();
            $unnotifiedReviewItems = collect();
            $recentCustomerOrders = collect();
            $allShopNotifications = collect();

            if (Auth::check()) {
                $user = Auth::user();
                $pendingReviewItems = $user->getPendingReviewItems();
                $unnotifiedReviewItems = $user->getUnnotifiedPendingReviewItems();
                $recentCustomerOrders = $user->orders()->with('items')->latest()->take(5)->get();

                // 1. Thông báo đánh giá sản phẩm từ các đơn hoàn tất
                foreach ($pendingReviewItems as $item) {
                    $allShopNotifications->push([
                        'id' => 'rev_' . $item->id,
                        'type' => 'review',
                        'icon' => 'fa-solid fa-gift text-warning',
                        'badge' => '+20đ VIP',
                        'badge_class' => 'bg-warning text-dark',
                        'title' => 'Đánh giá sản phẩm nhận quà',
                        'content' => "Đơn hàng {$item->order->order_code} đã hoàn tất. Đánh giá sản phẩm \"{$item->product_name}\" để nhận ngay +20 điểm VIP!",
                        'link' => route('client.products.show', $item->product_id) . '#reviews',
                        'image' => asset($item->image ?? ($item->product->image ?? '/assets/img/products/1.png')),
                        'created_at' => $item->created_at,
                        'time_ago' => $item->created_at ? $item->created_at->diffForHumans() : 'Gần đây',
                        'is_unread' => true,
                        'action_text' => 'Đánh giá (+20đ)',
                    ]);
                }

                // 2. Thông báo trạng thái các đơn hàng vừa mua
                foreach ($recentCustomerOrders as $order) {
                    $statusText = match($order->shipping_status) {
                        'completed' => 'Đơn hàng đã hoàn tất thành công',
                        'delivered' => 'Đơn hàng đã được giao đến bạn',
                        'shipping' => 'Đơn hàng đang trên đường giao',
                        'processing' => 'Đơn hàng đang được đóng gói xuất kho',
                        'cancelled' => 'Đơn hàng đã bị hủy',
                        default => 'Đơn hàng mới đặt thành công',
                    };
                    $iconClass = match($order->shipping_status) {
                        'completed' => 'fa-solid fa-circle-check text-success',
                        'delivered' => 'fa-solid fa-box-open text-success',
                        'shipping' => 'fa-solid fa-truck-fast text-warning',
                        'processing' => 'fa-solid fa-box text-info',
                        'cancelled' => 'fa-solid fa-ban text-danger',
                        default => 'fa-solid fa-receipt text-primary',
                    };

                    $allShopNotifications->push([
                        'id' => 'ord_' . $order->id,
                        'type' => 'order',
                        'icon' => $iconClass,
                        'badge' => $order->shipping_status_label ?? 'Đơn hàng',
                        'badge_class' => 'bg-light text-dark border',
                        'title' => "Cập nhật đơn hàng {$order->order_code}",
                        'content' => "{$statusText}. Tổng thanh toán: " . number_format($order->total_amount, 0, ',', '.') . "₫.",
                        'link' => route('client.order-tracking', ['code' => $order->order_code]),
                        'image' => null,
                        'created_at' => $order->created_at,
                        'time_ago' => $order->created_at ? $order->created_at->diffForHumans() : 'Vừa xong',
                        'is_unread' => false,
                        'action_text' => 'Xem hành trình',
                    ]);
                }

                // 3. Thông báo Mã giảm giá & Ưu đãi thành viên từ Shop
                $activeCoupons = \App\Models\Coupon::where('is_active', true)->take(2)->get();
                foreach ($activeCoupons as $cp) {
                    $allShopNotifications->push([
                        'id' => 'cp_' . $cp->id,
                        'type' => 'promo',
                        'icon' => 'fa-solid fa-tag text-danger',
                        'badge' => 'Voucher Hot',
                        'badge_class' => 'bg-danger-subtle text-danger',
                        'title' => "Ưu đãi độc quyền: Mã {$cp->code}",
                        'content' => "{$cp->title}. Giảm giá cực sốc khi mua sắm thời trang áo nam hôm nay!",
                        'link' => route('client.products.index'),
                        'image' => null,
                        'created_at' => now()->subHours(2),
                        'time_ago' => 'Ưu đãi hôm nay',
                        'is_unread' => false,
                        'action_text' => 'Dùng ngay',
                    ]);
                }

                // 4. Thông báo Điểm thưởng VIP
                if ($user->points > 0) {
                    $allShopNotifications->push([
                        'id' => 'vip_' . $user->id,
                        'type' => 'vip',
                        'icon' => 'fa-solid fa-crown text-warning',
                        'badge' => 'BeeStyle VIP',
                        'badge_class' => 'bg-warning text-dark',
                        'title' => "Bạn đang có " . number_format($user->points) . " Điểm Thưởng VIP",
                        'content' => "Hạng thành viên: {$user->rank}. Bạn có thể dùng điểm đổi các ưu đãi quà tặng và voucher sinh nhật giảm 20%.",
                        'link' => route('client.profile', ['tab' => 'vip']),
                        'image' => null,
                        'created_at' => now()->subDays(1),
                        'time_ago' => 'Quyền lợi VIP',
                        'is_unread' => false,
                        'action_text' => 'Xem điểm VIP',
                    ]);
                }
            }

            $view->with([
                'pendingReviewItems' => $pendingReviewItems,
                'unnotifiedReviewItems' => $unnotifiedReviewItems,
                'recentCustomerOrders' => $recentCustomerOrders,
                'allShopNotifications' => $allShopNotifications,
            ]);
        });
    }
}




