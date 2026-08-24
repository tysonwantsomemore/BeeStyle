<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $this->date($request->query('from'), now()->startOfMonth());
        $to = $this->date($request->query('to'), now())->endOfDay();
        if ($from->gt($to)) [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];

        $orders = Order::whereBetween('created_at', [$from, $to]);
        $validOrders = (clone $orders)->where('shipping_status', '!=', 'cancelled');
        $orderCount = (clone $orders)->count();
        $validOrderCount = (clone $validOrders)->count();
        $revenue = (clone $validOrders)->sum('total_amount');
        $completed = (clone $orders)->whereIn('shipping_status', ['completed', 'delivered'])->count();

        $dailyRows = (clone $validOrders)
            ->selectRaw('DATE(created_at) as report_date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->groupBy('report_date')->orderBy('report_date')->get()->keyBy('report_date');
        $labels = []; $revenueSeries = []; $orderSeries = [];
        for ($day = $from->copy()->startOfDay(); $day->lte($to); $day->addDay()) {
            $key = $day->toDateString(); $row = $dailyRows->get($key);
            $labels[] = $day->format('d/m'); $revenueSeries[] = (int) ($row->revenue ?? 0); $orderSeries[] = (int) ($row->orders ?? 0);
        }

        $topProducts = OrderItem::query()->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$from, $to])->where('orders.shipping_status', '!=', 'cancelled')
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as quantity'), DB::raw('SUM(order_items.subtotal) as revenue'))
            ->groupBy('order_items.product_name')->orderByDesc('quantity')->limit(5)->get();
        $topCustomers = User::query()->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$from, $to])->where('orders.shipping_status', '!=', 'cancelled')
            ->select('users.name', 'users.email', DB::raw('COUNT(orders.id) as orders_count'), DB::raw('SUM(orders.total_amount) as spent'))
            ->groupBy('users.id', 'users.name', 'users.email')->orderByDesc('spent')->limit(5)->get();
        $statusBreakdown = (clone $orders)->select('shipping_status', DB::raw('COUNT(*) as total'))->groupBy('shipping_status')->pluck('total', 'shipping_status');
        $paymentBreakdown = (clone $validOrders)->select('payment_method', DB::raw('SUM(total_amount) as total'))->groupBy('payment_method')->pluck('total', 'payment_method');

        return view('admin.reports.index', compact('from', 'to', 'orderCount', 'validOrderCount', 'revenue', 'completed', 'labels', 'revenueSeries', 'orderSeries', 'topProducts', 'topCustomers', 'statusBreakdown', 'paymentBreakdown'));
    }

    private function date(?string $value, Carbon $fallback): Carbon
    {
        try { return $value ? Carbon::parse($value)->startOfDay() : $fallback; }
        catch (\Throwable) { return $fallback; }
    }
}
