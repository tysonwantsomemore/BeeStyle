<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EcommerceDataService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = EcommerceDataService::getCustomers();
        return view('admin.customers.index', compact('customers'));
    }
}
