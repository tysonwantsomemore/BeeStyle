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
     * Store a product review from a verified buyer.
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

        // 3. Lưu hoặc Cập nhật đánh giá của user đối với sản phẩm này
        Review::updateOrCreate(
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

        // 4. Tính toán lại điểm Rating trung bình & Tổng số lượt đánh giá của sản phẩm
        $approvedReviews = Review::where('product_id', $product->id)->where('status', 'approved');
        $avgRating = $approvedReviews->avg('rating') ?: 5.0;
        $reviewsCount = $approvedReviews->count();

        $product->update([
            'rating' => round($avgRating, 1),
            'reviews_count' => $reviewsCount,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã gửi đánh giá & nhận xét về sản phẩm "' . $product->name . '"!');
    }
}
