<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = EcommerceDataService::getCoupons();
        return view('admin.coupons.index', compact('coupons'));
    }
}
