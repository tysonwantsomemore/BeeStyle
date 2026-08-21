<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');

        $query = User::where('role', 'customer')->withCount(['orders', 'reviews'])->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $customers = $query->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    /**
     * Xem thông tin chi tiết tài khoản khách hàng, lịch sử mua hàng và các đánh giá
     */
    public function show($id)
    {
        $customer = User::with([
            'orders' => fn($q) => $q->with('items')->latest(),
            'reviews' => fn($q) => $q->with('product')->latest(),
            'addresses'
        ])->where('role', 'customer')->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }
}

