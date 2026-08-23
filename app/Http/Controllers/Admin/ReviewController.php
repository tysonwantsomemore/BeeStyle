<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Danh sách đánh giá & nhận xét từ khách hàng
     */
    public function index(Request $request)
    {
        $search = $request->query('q');
        $rating = $request->query('rating');
        $status = $request->query('status');

        $query = Review::with(['product', 'user.orders'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'LIKE', "%{$search}%")
                  ->orWhere('comment', 'LIKE', "%{$search}%")
                  ->orWhereHas('product', fn($p) => $p->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('user', fn($u) => $u->where('email', 'LIKE', "%{$search}%"));
            });
        }

        if ($rating) {
            $query->where('rating', $rating);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $reviews = $query->paginate(15)->withQueryString();

        // Nối thông tin đơn hàng khách đã mua sản phẩm này
        foreach ($reviews as $rev) {
            $this->attachMatchedOrder($rev);
        }

        $latestReviews = Review::with(['product', 'user'])->latest()->take(4)->get();
        foreach ($latestReviews as $lRev) {
            $this->attachMatchedOrder($lRev);
        }

        $totalReviews = Review::count();
        $avgRating = round(Review::where('status', 'approved')->avg('rating'), 1) ?: 5.0;
        $fiveStarCount = Review::where('rating', 5)->count();

        return view('admin.reviews.index', compact(
            'reviews',
            'latestReviews',
            'search',
            'rating',
            'status',
            'totalReviews',
            'avgRating',
            'fiveStarCount'
        ));
    }

    /**
     * Nối thông tin Đơn hàng khách đã mua sản phẩm được đánh giá
     */
    private function attachMatchedOrder(&$review)
    {
        $review->matched_order = null;
        if ($review->user_id && $review->product_id) {
            $order = \App\Models\Order::where(function($q) use ($review) {
                    $q->where('user_id', $review->user_id);
                    if ($review->user && $review->user->phone) {
                        $q->orWhere('customer_phone', $review->user->phone);
                    }
                })
                ->whereHas('items', function ($q) use ($review) {
                    $q->where('product_id', $review->product_id)
                      ->orWhere('product_name', 'LIKE', '%' . ($review->product->name ?? '') . '%');
                })
                ->with(['items'])
                ->latest()
                ->first();

            if ($order) {
                $matchedItem = $order->items->first(function($it) use ($review) {
                    return $it->product_id == $review->product_id || ($review->product && str_contains($it->product_name, $review->product->name));
                }) ?: $order->items->first();

                if ($matchedItem) {
                    $review->matched_order = [
                        'order_id' => $order->id,
                        'order_code' => $order->order_code,
                        'created_at' => $order->created_at ? $order->created_at->format('d/m/Y H:i') : '',
                        'total_amount' => number_format($order->total_amount, 0, ',', '.') . '₫',
                        'shipping_status_label' => $order->status_label ?? 'Đã hoàn tất',
                        'payment_status_label' => $order->payment_status_label ?? 'Đã thanh toán',
                        'color' => $matchedItem->color ?? 'Tiêu chuẩn',
                        'size' => $matchedItem->size ?? 'M',
                        'quantity' => $matchedItem->quantity ?? 1,
                        'price' => number_format($matchedItem->price ?? ($review->product->price ?? 0), 0, ',', '.') . '₫',
                        'item_image' => $matchedItem->image ? asset($matchedItem->image) : ($review->product && $review->product->image ? asset($review->product->image) : asset('/assets/img/products/1.png')),
                    ];
                }
            }
        }
    }



    /**
     * Cập nhật trạng thái duyệt / ẩn đánh giá
     */
    public function updateStatus(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $status = $request->input('status', 'approved');

        $review->update(['status' => $status]);

        // Cập nhật lại rating trung bình và số lượng review của sản phẩm
        $product = Product::find($review->product_id);
        if ($product) {
            $product->rating = round(Review::where('product_id', $product->id)->where('status', 'approved')->avg('rating'), 1) ?: 5.0;
            $product->reviews_count = Review::where('product_id', $product->id)->where('status', 'approved')->count();
            $product->save();
        }

        return back()->with('success', "Đã cập nhật trạng thái đánh giá thành \"{$status}\" thành công!");
    }

    /**
     * Xóa đánh giá
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $productId = $review->product_id;
        $review->delete();

        // Cập nhật lại rating sản phẩm
        $product = Product::find($productId);
        if ($product) {
            $product->rating = round(Review::where('product_id', $product->id)->where('status', 'approved')->avg('rating'), 1) ?: 5.0;
            $product->reviews_count = Review::where('product_id', $product->id)->where('status', 'approved')->count();
            $product->save();
        }

        return back()->with('success', 'Đã xóa đánh giá của khách hàng thành công!');
    }
}
