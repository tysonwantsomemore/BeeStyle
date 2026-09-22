<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    /**
     * Danh sách tài khoản khách hàng kèm bộ lọc đa năng & thống kê toàn diện
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $tier = $request->query('tier');
        $behavior = $request->query('behavior');
        $sort = $request->query('sort', 'latest');

        // Lấy tất cả khách hàng (bao gồm role = 'customer' hoặc các tài khoản không phải admin)
        $query = User::where(function ($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })
        ->withCount(['orders', 'reviews'])
        ->withSum([
            'orders as actual_total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')
        ], 'total_amount');

        // 1. Tìm kiếm theo từ khóa: Tên, Email, SĐT, ID khách hàng
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                // Kiểm tra nếu search có dạng số hoặc "#CUST-0008"
                $cleanId = preg_replace('/[^\d]/', '', $search);
                if (!empty($cleanId) && is_numeric($cleanId)) {
                    $q->orWhere('id', (int) $cleanId);
                }

                $q->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // 2. Lọc theo trạng thái tài khoản
        if (in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        // 3. Lọc theo hành vi mua hàng
        if ($behavior === 'has_orders') {
            $query->whereHas('orders', fn($q) => $q->where('shipping_status', '!=', 'cancelled'));
        } elseif ($behavior === 'no_orders') {
            $query->whereDoesntHave('orders', fn($q) => $q->where('shipping_status', '!=', 'cancelled'));
        }

        // 4. Lọc theo hạng thành viên (Dựa trên tổng chi tiêu thực tế)
        if ($tier === 'diamond') {
            $query->having('actual_total_spent', '>=', 10000000);
        } elseif ($tier === 'gold') {
            $query->having('actual_total_spent', '>=', 5000000)->having('actual_total_spent', '<', 10000000);
        } elseif ($tier === 'silver') {
            $query->having('actual_total_spent', '>=', 2000000)->having('actual_total_spent', '<', 5000000);
        } elseif ($tier === 'bronze') {
            $query->havingRaw('COALESCE(actual_total_spent, 0) < 2000000');
        }

        // 5. Sắp xếp danh sách
        match ($sort) {
            'spent_desc'  => $query->orderByDesc('actual_total_spent'),
            'spent_asc'   => $query->orderBy('actual_total_spent', 'asc'),
            'orders_desc' => $query->orderByDesc('orders_count'),
            'name_asc'    => $query->orderBy('name', 'asc'),
            'oldest'      => $query->orderBy('created_at', 'asc'),
            default       => $query->orderByDesc('created_at'),
        };

        $customers = $query->paginate(12)->withQueryString();

        // THỐNG KÊ TỔNG QUAN TÀI KHOẢN KHÁCH HÀNG & DOANH THU TOÀN DIỆN
        $baseCustomersQuery = User::where(function ($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        });

        $totalRegisteredCustomers = (clone $baseCustomersQuery)->count();
        $newCustomersThisMonth = (clone $baseCustomersQuery)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalBannedAccounts = (clone $baseCustomersQuery)->where('status', 'banned')->count();

        // Khách hàng có đơn thành công
        $totalPurchasingAccounts = (clone $baseCustomersQuery)
            ->whereHas('orders', fn($q) => $q->where('shipping_status', '!=', 'cancelled'))
            ->count();

        // Doanh thu từ tài khoản thành viên đăng nhập
        $registeredTotalSpent = Order::whereNotNull('user_id')
            ->where('shipping_status', '!=', 'cancelled')
            ->sum('total_amount');

        // Doanh thu toàn bộ shop (bao gồm cả khách mua nhanh vãng lai)
        $totalAllCustomersSpent = Order::where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $totalCompletedSpent = Order::whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $totalOrdersCount = Order::where('shipping_status', '!=', 'cancelled')->count();

        // Chi tiêu trung bình trên mỗi khách hàng đã mua
        $averageSpendPerCustomer = $totalPurchasingAccounts > 0 ? round($registeredTotalSpent / $totalPurchasingAccounts) : 0;

        // Số khách hàng VIP (Chi tiêu >= 5 triệu)
        $vipCustomersCount = (clone $baseCustomersQuery)
            ->withSum(['orders as actual_total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')], 'total_amount')
            ->having('actual_total_spent', '>=', 5000000)
            ->count();

        return view('admin.customers.index', compact(
            'customers',
            'search',
            'status',
            'tier',
            'behavior',
            'sort',
            'totalRegisteredCustomers',
            'newCustomersThisMonth',
            'totalBannedAccounts',
            'totalPurchasingAccounts',
            'registeredTotalSpent',
            'totalAllCustomersSpent',
            'totalCompletedSpent',
            'totalOrdersCount',
            'averageSpendPerCustomer',
            'vipCustomersCount'
        ));
    }

    /**
     * Xem thông tin chi tiết tài khoản khách hàng, lịch sử đơn hàng, địa chỉ, đánh giá
     */
    public function show($id)
    {
        $customer = User::with([
            'orders' => fn($q) => $q->with('items.product')->latest(),
            'reviews' => fn($q) => $q->with('product')->latest(),
            'addresses'
        ])->where(function($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })->findOrFail($id);

        // Thống kê đơn hàng & chi tiêu của riêng khách hàng này
        $customerTotalSpent = $customer->orders->where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $customerCompletedSpent = $customer->orders->whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $customerOrdersCount = $customer->orders->count();
        $customerCompletedOrdersCount = $customer->orders->whereIn('shipping_status', ['completed', 'delivered'])->count();
        $customerCancelledOrdersCount = $customer->orders->where('shipping_status', 'cancelled')->count();
        $customerAverageOrderValue = $customerOrdersCount > 0 ? round($customerTotalSpent / $customerOrdersCount) : 0;
        $customerSuccessRate = $customerOrdersCount > 0 ? round(($customerCompletedOrdersCount / $customerOrdersCount) * 100, 1) : 0;

        // Thống kê toàn bộ các khách hàng và toàn shop từ trước đến nay
        $totalAllCustomersSpent = Order::where('shipping_status', '!=', 'cancelled')->sum('total_amount');
        $totalCompletedSpent = Order::whereIn('shipping_status', ['completed', 'delivered'])->sum('total_amount');
        $totalAllRegisteredCustomers = User::where(function($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })->count();
        $totalShopOrdersCount = Order::where('shipping_status', '!=', 'cancelled')->count();

        // Danh sách tất cả các tài khoản khách hàng đã từng mua hàng từ trước đến nay
        $allPurchasingCustomers = User::where(function($q) {
                $q->where('role', 'customer')
                  ->orWhere('role', '!=', 'admin')
                  ->orWhereNull('role');
            })
            ->whereHas('orders', fn($q) => $q->where('shipping_status', '!=', 'cancelled'))
            ->withCount([
                'orders' => fn($q) => $q->where('shipping_status', '!=', 'cancelled'),
                'reviews'
            ])
            ->withSum([
                'orders as total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')
            ], 'total_amount')
            ->orderByDesc('total_spent')
            ->get();

        $totalPurchasingAccounts = $allPurchasingCustomers->count();
        $averageSpendPerAccount = $totalPurchasingAccounts > 0 ? round($totalAllCustomersSpent / $totalPurchasingAccounts) : 0;

        // Tỷ lệ đóng góp chi tiêu của khách này trong tổng chi tiêu toàn shop
        $customerContributionPercent = $totalAllCustomersSpent > 0 ? round(($customerTotalSpent / $totalAllCustomersSpent) * 100, 2) : 0;

        // Vị trí xếp hạng chi tiêu của khách hàng này trong shop
        $rankIndex = $allPurchasingCustomers->search(fn($c) => $c->id === $customer->id);
        $customerRankPosition = ($rankIndex !== false) ? ($rankIndex + 1) : '—';

        // Tổng hợp tất cả địa chỉ (Profile + Sổ địa chỉ + Đơn hàng thực tế)
        $orderShippingAddresses = $customer->orders
            ->filter(fn($o) => !empty($o->shipping_address))
            ->unique('shipping_address')
            ->take(5);

        return view('admin.customers.show', compact(
            'customer',
            'customerTotalSpent',
            'customerCompletedSpent',
            'customerOrdersCount',
            'customerCompletedOrdersCount',
            'customerCancelledOrdersCount',
            'customerAverageOrderValue',
            'customerSuccessRate',
            'customerContributionPercent',
            'customerRankPosition',
            'totalAllCustomersSpent',
            'totalCompletedSpent',
            'totalAllRegisteredCustomers',
            'totalPurchasingAccounts',
            'totalShopOrdersCount',
            'averageSpendPerAccount',
            'allPurchasingCustomers',
            'orderShippingAddresses'
        ));
    }

    /**
     * Khóa hoặc Mở khóa tài khoản khách hàng (Toggle Status)
     */
    public function toggleStatus($id)
    {
        $customer = User::where(function($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })->findOrFail($id);

        if ($customer->isAdmin()) {
            return back()->with('error', 'Không thể thay đổi trạng thái của tài khoản Quản trị viên tại đây.');
        }

        if ($customer->is(Auth::user())) {
            return back()->with('error', 'Bạn không thể tự thao tác trên tài khoản đang đăng nhập.');
        }

        $newStatus = ($customer->status === 'banned') ? 'active' : 'banned';
        $customer->update(['status' => $newStatus]);

        $statusText = ($newStatus === 'active') ? 'mở khóa hoạt động' : 'khóa tạm thời';

        return back()->with('success', "Đã {$statusText} tài khoản của khách hàng \"{$customer->name}\" thành công.");
    }

    /**
     * Cập nhật thông tin tài khoản khách hàng
     */
    public function update(Request $request, $id)
    {
        $customer = User::where(function($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })->findOrFail($id);

        if ($customer->isAdmin()) {
            return back()->with('error', 'Không thể sửa thông tin của Quản trị viên từ giao diện khách hàng.');
        }

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'gender'   => ['nullable', 'string', 'in:Nam,Nữ,Khác'],
            'dob'      => ['nullable', 'date'],
            'address'  => ['nullable', 'string', 'max:255'],
            'city'     => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'points'   => ['nullable', 'integer', 'min:0'],
            'status'   => ['required', 'in:active,banned'],
        ], [
            'name.required' => 'Họ và tên khách hàng không được để trống.',
            'status.in'     => 'Trạng thái tài khoản không hợp lệ.',
        ]);

        $customer->update($validated);

        return back()->with('success', "Đã cập nhật thông tin tài khoản khách hàng \"{$customer->name}\" thành công.");
    }

    /**
     * Xuất danh sách khách hàng ra file CSV (Tương thích Microsoft Excel với UTF-8 BOM)
     */
    public function export(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');
        $tier = $request->query('tier');

        $query = User::where(function ($q) {
            $q->where('role', 'customer')
              ->orWhere('role', '!=', 'admin')
              ->orWhereNull('role');
        })
        ->withCount(['orders', 'reviews'])
        ->withSum([
            'orders as actual_total_spent' => fn($q) => $q->where('shipping_status', '!=', 'cancelled')
        ], 'total_amount')
        ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if (in_array($status, ['active', 'banned'])) {
            $query->where('status', $status);
        }

        if ($tier === 'diamond') {
            $query->having('actual_total_spent', '>=', 10000000);
        } elseif ($tier === 'gold') {
            $query->having('actual_total_spent', '>=', 5000000)->having('actual_total_spent', '<', 10000000);
        } elseif ($tier === 'silver') {
            $query->having('actual_total_spent', '>=', 2000000)->having('actual_total_spent', '<', 5000000);
        } elseif ($tier === 'bronze') {
            $query->havingRaw('COALESCE(actual_total_spent, 0) < 2000000');
        }

        $customers = $query->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="khach_hang_beestyle_' . date('Y-m-d_His') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($customers) {
            $handle = fopen('php://output', 'w');
            // Ghi UTF-8 BOM để Excel hiển thị đúng tiếng Việt có dấu
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID',
                'Mã Khách Hàng',
                'Họ và Tên',
                'Email',
                'Số Điện Thoại',
                'Giới Tính',
                'Ngày Sinh',
                'Địa Chỉ',
                'Tỉnh/Thành Phố',
                'Hạng Thành Viên',
                'Điểm Tích Lũy',
                'Số Đơn Hàng',
                'Tổng Chi Tiêu (VNĐ)',
                'Trạng Thái',
                'Ngày Tham Gia'
            ]);

            foreach ($customers as $c) {
                fputcsv($handle, [
                    $c->id,
                    '#CUST-' . str_pad($c->id, 4, '0', STR_PAD_LEFT),
                    $c->name,
                    $c->email,
                    $c->phone ?? '',
                    $c->gender ?? '',
                    $c->dob ? date('d/m/Y', strtotime($c->dob)) : '',
                    $c->address ?? '',
                    $c->city ?? '',
                    $c->rank ?? 'Thành viên',
                    $c->points ?? 0,
                    $c->orders_count ?? 0,
                    $c->actual_total_spent ?? 0,
                    ($c->status === 'banned') ? 'Đã bị khóa' : 'Hoạt động',
                    $c->created_at ? $c->created_at->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($handle);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
