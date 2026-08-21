<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Lấy dữ liệu chi tiết sản phẩm và toàn bộ đánh giá của khách hàng
     */
    public function getProductReviewsData($id)
    {
        $product = Product::active()->findOrFail($id);
        $user = Auth::user();

        $userReview = null;
        $userHasPurchased = false;

        if ($user) {
            $userHasPurchased = Order::where('user_id', $user->id)
                ->whereHas('items', fn($q) => $q->where('product_id', $product->id))
                ->exists();

            $userReview = Review::where('product_id', $product->id)
                ->where('user_id', $user->id)
                ->first();
        }

        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->with('user')
            ->latest()
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->id,
                    'user_name' => $r->user_name,
                    'user_avatar' => asset($r->user->avatar ?? '/assets/img/team/40x40/58.webp'),
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'created_at' => $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                    'time_ago' => $r->created_at ? $r->created_at->diffForHumans() : 'Vừa xong',
                ];
            });

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'image' => asset($product->image),
                'price' => number_format($product->price, 0, ',', '.') . '₫',
                'raw_price' => $product->price,
                'rating' => (float)$product->rating,
                'reviews_count' => (int)$product->reviews_count,
                'category_name' => $product->category->name ?? 'Thời Trang Nam',
                'url' => route('client.products.show', $product->id),
            ],
            'user_has_purchased' => $userHasPurchased,
            'user_review' => $userReview ? [
                'rating' => $userReview->rating,
                'comment' => $userReview->comment,
                'updated_at' => $userReview->updated_at ? $userReview->updated_at->format('d/m/Y H:i') : '',
            ] : null,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Store or update a product review from a verified buyer.
     */
    public function store(Request $request, $id)
    {
        $product = Product::active()->findOrFail($id);
        $user = Auth::user();

        // 1. Kiểm tra khách hàng đã từng mua sản phẩm này hay chưa
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereHas('items', function ($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        if (!$hasPurchased) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chức năng đánh giá chỉ dành cho khách hàng đã từng đặt mua sản phẩm này tại BeeStyle!'
                ], 403);
            }
            return back()->with('error', 'Chức năng đánh giá chỉ dành cho khách hàng đã từng đặt mua sản phẩm này tại BeeStyle!');
        }

        // 2. Validate dữ liệu đánh giá
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:6|max:1000',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá (1 - 5 sao).',
            'rating.min' => 'Số sao đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Số sao đánh giá tối đa là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung nhận xét của bạn.',
            'comment.min' => 'Nội dung nhận xét tối thiểu từ 6 ký tự.',
            'comment.max' => 'Nội dung nhận xét tối đa 1000 ký tự.',
        ]);

        // 3. Kiểm tra xem đã từng đánh giá sản phẩm này chưa
        $existingReview = Review::where('product_id', $product->id)->where('user_id', $user->id)->first();
        $isFirstTime = !$existingReview;

        // Lưu hoặc Cập nhật đánh giá của user đối với sản phẩm này
        $review = Review::updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            [
                'user_name' => $user->name,
                'rating' => (int)$validated['rating'],
                'comment' => $validated['comment'],
                'status' => 'approved',
            ]
        );

        // Cộng 20 điểm thưởng VIP nếu đánh giá lần đầu
        if ($isFirstTime) {
            $user->increment('points', 20);
        }

        // 4. Tính toán lại điểm Rating trung bình & Tổng số lượt đánh giá của sản phẩm
        $approvedReviews = Review::where('product_id', $product->id)->where('status', 'approved');
        $avgRating = $approvedReviews->avg('rating') ?: 5.0;
        $reviewsCount = $approvedReviews->count();

        $product->update([
            'rating' => round($avgRating, 1),
            'reviews_count' => $reviewsCount,
        ]);

        $msg = $isFirstTime 
            ? 'Cảm ơn bạn đã đánh giá sản phẩm "' . $product->name . '"! Bạn nhận được +20 điểm thưởng VIP.' 
            : 'Đã cập nhật đánh giá & nhận xét của bạn về sản phẩm "' . $product->name . '" thành công!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_first_time' => $isFirstTime,
                'rating' => (int)$validated['rating'],
                'comment' => $validated['comment'],
                'product_rating' => round($avgRating, 1),
                'product_reviews_count' => $reviewsCount,
            ]);
        }

        return back()->with('success', $msg);
    }

    /**
     * Đánh dấu các đơn hàng đã được hiển thị thông báo đánh giá 1 lần duy nhất
     */
    public function dismissNotification(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        if (Auth::check() && !empty($orderIds)) {
            Auth::user()->markOrdersAsReviewNotified((array)$orderIds);
        }

        return response()->json(['success' => true]);
    }
}



