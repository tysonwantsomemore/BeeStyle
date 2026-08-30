<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
        $id = (int)$id;
        $product = Product::with('category')->find($id);
        if (!$product) {
            $product = Product::with('category')->active()->first() ?: Product::with('category')->first();
        }

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm hiện không khả dụng.'
            ], 404);
        }

        $user = Auth::user();
        $userReview = null;
        $userHasPurchased = false;

        if ($user) {
            if ($user->role === 'admin' || $user->role === 'staff') {
                $userHasPurchased = true;
            } else {
                $userHasPurchased = Order::where(function($q) use ($user) {
                        $q->where('user_id', $user->id);
                        if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                        if ($user->email) $q->orWhere('customer_email', $user->email);
                    })
                    ->whereHas('items', function ($q) use ($product) {
                        $q->where('product_id', $product->id)
                          ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                    })
                    ->exists();

                if (!$userHasPurchased) {
                    $userHasPurchased = OrderItem::whereHas('order', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                        if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                    })
                    ->where(function($q) use ($product) {
                        $q->where('product_id', $product->id)
                          ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                    })
                    ->exists();
                }
            }

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
                $uName = $r->user_name ?: ($r->user->name ?? 'Khách hàng BeeStyle');
                $avatar = $r->user && $r->user->avatar 
                    ? asset($r->user->avatar) 
                    : 'https://ui-avatars.com/api/?name=' . urlencode($uName) . '&background=f59e0b&color=111827&bold=true&size=128';

                return [
                    'id' => $r->id,
                    'user_name' => $uName,
                    'user_avatar' => $avatar,
                    'rating' => (int)($r->rating ?: 5),
                    'comment' => (string)($r->comment ?? ''),
                    'images' => $r->images_urls,
                    'has_images' => $r->has_images,
                    'created_at' => $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                    'time_ago' => $r->created_at ? $r->created_at->diffForHumans() : 'Vừa xong',
                ];
            });

        $productImg = $product->image ? asset($product->image) : asset('/assets/img/products/1.png');

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku ?? ('BS-' . $product->id),
                'image' => $productImg,
                'price' => number_format($product->price, 0, ',', '.') . '₫',
                'raw_price' => $product->price,
                'rating' => (float)($product->rating ?: 5.0),
                'reviews_count' => (int)($product->reviews_count ?: $reviews->count()),
                'category_name' => $product->category->name ?? 'Thời Trang Nam',
                'url' => route('client.products.show', $product->id),
            ],
            'is_logged_in' => (bool)$user,
            'user_has_purchased' => $userHasPurchased,
            'user_review' => $userReview ? [
                'rating' => (int)$userReview->rating,
                'comment' => (string)$userReview->comment,
                'images' => $userReview->images_urls,
                'has_images' => $userReview->has_images,
                'updated_at' => $userReview->updated_at ? $userReview->updated_at->format('d/m/Y H:i') : '',
            ] : null,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Store or update a product review with text and optional images from a verified buyer.
     */
    public function store(Request $request, $id)
    {
        $product = Product::active()->findOrFail($id);

        if (!Auth::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập tài khoản để gửi đánh giá sản phẩm!'
                ], 401);
            }
            return redirect()->route('auth.login')->with('error', 'Vui lòng đăng nhập tài khoản để gửi đánh giá sản phẩm!');
        }

        $user = Auth::user();

        // 1. Kiểm tra khách hàng đã từng mua sản phẩm này hay chưa (hoặc admin/staff)
        $hasPurchased = false;
        if ($user->role === 'admin' || $user->role === 'staff') {
            $hasPurchased = true;
        } else {
            $hasPurchased = Order::where(function($q) use ($user) {
                    $q->where('user_id', $user->id);
                    if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                    if ($user->email) $q->orWhere('customer_email', $user->email);
                })
                ->whereHas('items', function ($q) use ($product) {
                    $q->where('product_id', $product->id)
                      ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                })
                ->exists();

            if (!$hasPurchased) {
                $hasPurchased = OrderItem::whereHas('order', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                    if ($user->phone) $q->orWhere('customer_phone', $user->phone);
                })
                ->where(function($q) use ($product) {
                    $q->where('product_id', $product->id)
                      ->orWhere('product_name', 'LIKE', '%' . $product->name . '%');
                })
                ->exists();
            }
        }

        if (!$hasPurchased) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chức năng đánh giá chỉ dành cho khách hàng đã từng đặt mua sản phẩm này tại BeeStyle!'
                ], 403);
            }
            return back()->with('error', 'Chức năng đánh giá chỉ dành cho khách hàng đã từng đặt mua sản phẩm này tại BeeStyle!');
        }

        // 2. Validate dữ liệu đánh giá & hình ảnh đính kèm
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:4|max:1000',
            'review_images' => 'nullable|array|max:5',
            'review_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'images' => 'nullable|array|max:5',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ], [
            'rating.required' => 'Vui lòng chọn số sao đánh giá (1 - 5 sao).',
            'rating.min' => 'Số sao đánh giá tối thiểu là 1 sao.',
            'rating.max' => 'Số sao đánh giá tối đa là 5 sao.',
            'comment.required' => 'Vui lòng nhập nội dung nhận xét của bạn.',
            'comment.min' => 'Nội dung nhận xét tối thiểu từ 4 ký tự.',
            'comment.max' => 'Nội dung nhận xét tối đa 1000 ký tự.',
            'review_images.max' => 'Bạn chỉ có thể tải lên tối đa 5 hình ảnh.',
            'review_images.*.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'review_images.*.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp.',
            'review_images.*.max' => 'Dung lượng mỗi ảnh tối đa 5MB.',
        ]);

        // 3. Xử lý tải lên hình ảnh đính kèm
        $existingReview = Review::where('product_id', $product->id)->where('user_id', $user->id)->first();
        $isFirstTime = !$existingReview;
        
        $imagePaths = $existingReview && is_array($existingReview->images) ? $existingReview->images : [];

        // Lấy danh sách ảnh tải lên từ form (cả 'review_images' hoặc 'images')
        $uploadedFiles = $request->file('review_images') ?: $request->file('images');
        if ($uploadedFiles && is_array($uploadedFiles)) {
            $newImagePaths = [];
            $uploadPath = public_path('uploads/reviews');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($uploadedFiles as $idx => $file) {
                if ($file && $file->isValid()) {
                    $filename = 'rev_' . $product->id . '_' . $user->id . '_' . time() . '_' . ($idx + 1) . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $filename);
                    $newImagePaths[] = 'uploads/reviews/' . $filename;
                }
            }

            if (!empty($newImagePaths)) {
                $imagePaths = $newImagePaths;
            }
        }

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
                'images' => $imagePaths,
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

        $msg = $isFirstTime 
            ? 'Cảm ơn bạn đã đánh giá & chia sẻ hình ảnh sản phẩm "' . $product->name . '"! Đóng góp của bạn giúp BeeStyle ngày càng hoàn thiện hơn.' 
            : 'Đã cập nhật đánh giá & hình ảnh của bạn về sản phẩm "' . $product->name . '" thành công!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
                'is_first_time' => $isFirstTime,
                'rating' => (int)$validated['rating'],
                'comment' => $validated['comment'],
                'images' => $review->images_urls,
                'has_images' => $review->has_images,
                'product_rating' => round($avgRating, 1),
                'product_reviews_count' => $reviewsCount,
                'review' => [
                    'id' => $review->id,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar_url,
                    'rating' => (int)$validated['rating'],
                    'comment' => $validated['comment'],
                    'images' => $review->images_urls,
                    'has_images' => $review->has_images,
                    'created_at' => $review->created_at ? $review->created_at->format('d/m/Y H:i') : date('d/m/Y H:i'),
                    'time_ago' => 'Vừa xong',
                ],
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



