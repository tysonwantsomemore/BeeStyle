<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Event;
use App\Events\PasswordChangedEvent;
use App\Events\AccountRegisteredEvent;
use App\Events\ContactVerificationRequestedEvent;
use App\Listeners\SendPasswordChangedNotification;
use App\Listeners\SendVerificationCodeNotification;

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
        // 1. Đăng ký các Event & Listeners bảo mật và OTP
        Event::listen(PasswordChangedEvent::class, SendPasswordChangedNotification::class);
        Event::listen(AccountRegisteredEvent::class, [SendVerificationCodeNotification::class, 'handleRegistered']);
        Event::listen(ContactVerificationRequestedEvent::class, [SendVerificationCodeNotification::class, 'handleContactChange']);
        // Chia sẻ danh sách thông báo đầy đủ của Shop cho toàn bộ view Client
        View::composer(['layouts.client', 'client.*'], function ($view) {
            $pendingReviewItems = collect();
            $unnotifiedReviewItems = collect();
            $recentCustomerOrders = collect();
            $allShopNotifications = collect();

            $deliveringOrders = collect();
            $pendingReviewOrders = collect();

            if (Auth::check()) {
                $user = Auth::user();
                $pendingReviewItems = $user->getPendingReviewItems();
                $unnotifiedReviewItems = $user->getUnnotifiedPendingReviewItems();
                $recentCustomerOrders = $user->orders()->with(['items.product'])->latest()->take(10)->get();

                // Các đơn hàng đang ở trạng thái đã giao (delivered) cần khách hàng xác nhận hoặc đổi trả
                $deliveringOrders = $user->orders()->with(['items.product'])
                    ->where('shipping_status', 'delivered')
                    ->latest()
                    ->get();

                // Các đơn hàng đã hoàn tất (completed) nhưng chưa từng được đánh giá
                $pendingReviewOrders = $user->orders()->with(['items.product'])
                    ->where('shipping_status', 'completed')
                    ->where('review_notified', false)
                    ->latest()
                    ->get();

                // 1. ƯU TIÊN HÀNG ĐẦU: Thông báo đơn hàng bưu tá vừa phát tới nơi (Cần xác nhận nhận hàng hoặc đổi trả)
                foreach ($deliveringOrders as $dOrder) {
                    $allShopNotifications->push([
                        'id' => 'deliv_' . $dOrder->id,
                        'type' => 'delivery_action',
                        'order_id' => $dOrder->id,
                        'order_code' => $dOrder->order_code,
                        'total_amount' => $dOrder->total_amount,
                        'carrier' => $dOrder->shipping_carrier ?: 'GHTK',
                        'icon' => 'fa-solid fa-box-open text-emerald-600',
                        'badge' => 'Bưu tá đã phát',
                        'badge_class' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                        'title' => "Bưu tá đã giao kiện hàng #{$dOrder->order_code}!",
                        'content' => "Bưu tá đã phát bưu phẩm tới địa chỉ của bạn. Vui lòng đồng kiểm và xác nhận nhận hàng hoặc yêu cầu đổi trả/hoàn tiền nếu có vấn đề.",
                        'link' => route('client.order-tracking', ['code' => $dOrder->order_code]),
                        'image' => asset($dOrder->items->first()->image ?? ($dOrder->items->first()->product->thumbnail ?? 'assets/img/products/1.png')),
                        'created_at' => $dOrder->delivered_at ?: $dOrder->updated_at,
                        'time_ago' => $dOrder->delivered_at ? $dOrder->delivered_at->diffForHumans() : 'Vừa xong',
                        'is_unread' => true,
                        'action_type' => 'confirm_or_return',
                        'first_product_id' => $dOrder->items->first()->product_id ?? 1,
                        'first_product_name' => $dOrder->items->first()->product_name ?? 'Sản phẩm',
                    ]);
                }

                // 2. Thông báo đánh giá sản phẩm từ các đơn đã hoàn tất
                foreach ($pendingReviewItems as $item) {
                    $allShopNotifications->push([
                        'id' => 'rev_' . $item->id,
                        'type' => 'review',
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'order_code' => $item->order->order_code ?? '',
                        'icon' => 'fa-solid fa-star text-amber-500',
                        'badge' => 'Chờ đánh giá',
                        'badge_class' => 'bg-amber-100 text-amber-900 border border-amber-300',
                        'title' => 'Cảm ơn bạn đã mua sắm tại BeeStyle!',
                        'content' => "Đơn hàng #{$item->order->order_code} đã hoàn tất. Hãy chia sẻ cảm nhận của bạn về sản phẩm \"{$item->product_name}\" để nhận ngay Voucher ưu đãi nhé!",
                        'link' => route('client.products.show', $item->product_id) . '#reviews-section',
                        'image' => asset($item->image ?? ($item->product->thumbnail ?? 'assets/img/products/1.png')),
                        'created_at' => $item->created_at,
                        'time_ago' => $item->created_at ? $item->created_at->diffForHumans() : 'Gần đây',
                        'is_unread' => true,
                        'action_type' => 'review',
                        'action_text' => 'Đánh giá ngay (Tặng voucher)',
                    ]);
                }

                // 3. Thông báo trạng thái các đơn hàng khác
                foreach ($recentCustomerOrders->whereNotIn('shipping_status', ['delivered']) as $order) {
                    $statusText = match($order->shipping_status) {
                        'completed' => 'Đơn hàng đã hoàn tất thành công',
                        'shipping' => 'Đơn hàng đang trên đường bưu tá giao tới',
                        'processing' => 'Đơn hàng đang được kho kiểm tra & đóng gói',
                        'cancelled' => 'Đơn hàng đã hủy',
                        default => 'Đơn hàng mới tạo thành công',
                    };
                    $iconClass = match($order->shipping_status) {
                        'completed' => 'fa-solid fa-circle-check text-emerald-600',
                        'shipping' => 'fa-solid fa-truck-fast text-amber-500',
                        'processing' => 'fa-solid fa-box text-sky-600',
                        'cancelled' => 'fa-solid fa-ban text-rose-600',
                        default => 'fa-solid fa-receipt text-neutral-800',
                    };

                    $allShopNotifications->push([
                        'id' => 'ord_' . $order->id,
                        'type' => 'order',
                        'icon' => $iconClass,
                        'badge' => $order->shipping_status_label ?? 'Đơn hàng',
                        'badge_class' => 'bg-neutral-100 text-neutral-800 border border-neutral-200',
                        'title' => "Cập nhật đơn hàng #{$order->order_code}",
                        'content' => "{$statusText}. Tổng thanh toán: " . number_format($order->total_amount, 0, ',', '.') . "₫.",
                        'link' => route('client.order-tracking', ['code' => $order->order_code]),
                        'image' => null,
                        'created_at' => $order->created_at,
                        'time_ago' => $order->created_at ? $order->created_at->diffForHumans() : 'Vừa xong',
                        'is_unread' => false,
                        'action_type' => 'view_order',
                        'action_text' => 'Xem hành trình',
                    ]);
                }

                // 4. Thông báo Mã giảm giá & Ưu đãi thành viên từ Shop
                $activeCoupons = \App\Models\Coupon::where('is_active', true)->take(2)->get();
                foreach ($activeCoupons as $cp) {
                    $allShopNotifications->push([
                        'id' => 'cp_' . $cp->id,
                        'type' => 'promo',
                        'icon' => 'fa-solid fa-tag text-rose-600',
                        'badge' => 'Voucher Ưu Đãi',
                        'badge_class' => 'bg-rose-100 text-rose-800 border border-rose-200',
                        'title' => "Ưu đãi độc quyền: Mã {$cp->code}",
                        'content' => "{$cp->title}. Áp dụng ngay khi thanh toán các sản phẩm thời trang Atelier!",
                        'link' => route('client.products.index'),
                        'image' => null,
                        'created_at' => now()->subHours(2),
                        'time_ago' => 'Ưu đãi hôm nay',
                        'is_unread' => false,
                        'action_type' => 'promo',
                        'action_text' => 'Dùng ngay',
                    ]);
                }
            }

            $view->with([
                'pendingReviewItems' => $pendingReviewItems,
                'unnotifiedReviewItems' => $unnotifiedReviewItems,
                'recentCustomerOrders' => $recentCustomerOrders,
                'deliveringOrders' => $deliveringOrders,
                'pendingReviewOrders' => $pendingReviewOrders,
                'allShopNotifications' => $allShopNotifications,
            ]);
        });
    }
}




