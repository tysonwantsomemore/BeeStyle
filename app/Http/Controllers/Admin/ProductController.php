public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $brandId = $request->query('brand_id');
        $status = $request->query('status');
        $search = $request->query('q');

        $query = Product::with(['category', 'brand', 'variants'])->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($status === 'active') {
            $query->where('status', 'active')->where('stock', '>', 0);
        } elseif ($status === 'inactive') {
            $query->where('status', 'inactive');
        } elseif ($status === 'out_of_stock') {
            $query->where('stock', '<=', 5);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->paginate(10)->withQueryString();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();

        // Thống kê nhanh chỉ số kho hàng
        $totalProductsCount = Product::count();
        $activeProductsCount = Product::where('status', 'active')->where('stock', '>', 0)->count();
        $inactiveProductsCount = Product::where('status', 'inactive')->count();
        $lowStockProductsCount = Product::where('stock', '<=', 5)->count();

        // Dữ liệu chi tiết cho 4 nhóm KPI khi bấm vào từng thẻ
        $allProducts = Product::with(['category', 'brand'])->get();

        $formatProduct = function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'category' => $p->category->name ?? 'Thời trang nam',
                'brand' => $p->brand->name ?? null,
                'price' => $p->price,
                'price_formatted' => number_format($p->price, 0, ',', '.') . '₫',
                'original_price' => $p->original_price,
                'original_price_formatted' => $p->original_price ? number_format($p->original_price, 0, ',', '.') . '₫' : null,
                'stock' => $p->stock,
                'sold_count' => $p->sold_count,
                'rating' => $p->rating,
                'reviews_count' => $p->reviews_count,
                'status' => $p->status,
                'is_active' => $p->is_active,
                'image' => asset($p->image),
                'url' => route('client.products.show', $p->id),
                'edit_url' => route('admin.products.edit', $p->id),
            ];
        };

        $allList = $allProducts->map($formatProduct)->values();
        $activeList = $allProducts->where('status', 'active')->where('stock', '>', 0)->map($formatProduct)->values();
        $inactiveList = $allProducts->where('status', 'inactive')->map($formatProduct)->values();
        $lowStockList = $allProducts->where('stock', '<=', 5)->map($formatProduct)->values();

        $kpiDetailData = [
            'all' => [
                'type' => 'all',
                'title' => 'Toàn Bộ Mẫu Trong Kho',
                'subtitle' => 'Danh sách toàn bộ các mẫu sản phẩm thời trang trong kho hàng BeeStyle',
                'badge' => 'TỔNG QUAN',
                'badge_class' => 'bg-warning text-dark',
                'icon' => 'fa-solid fa-shirt',
                'filter_url' => route('admin.products.index'),
                'metrics' => [
                    ['label' => 'Tổng Mẫu Sản Phẩm', 'value' => $allProducts->count() . ' mẫu', 'color' => 'text-primary'],
                    ['label' => 'Tổng Số Cái Tồn Kho', 'value' => number_format($allProducts->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Tổng Giá Trị Tồn Kho', 'value' => number_format($allProducts->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Tổng Đã Xuất Bán', 'value' => number_format($allProducts->sum('sold_count'), 0, ',', '.') . ' cái', 'color' => 'text-success'],
                ],
                'products' => $allList,
            ],
            'active' => [
                'type' => 'active',
                'title' => 'Sản Phẩm Đang Mở Bán Công Khai',
                'subtitle' => 'Các mẫu sản phẩm đang hiển thị và cho phép khách hàng đặt mua trên Website',
                'badge' => 'KINH DOANH',
                'badge_class' => 'bg-success text-white',
                'icon' => 'fa-solid fa-circle-check',
                'filter_url' => route('admin.products.index', ['status' => 'active']),
                'metrics' => [
                    ['label' => 'Số Mẫu Đang Bán', 'value' => $activeList->count() . ' mẫu', 'color' => 'text-success'],
                    ['label' => 'Tổng Cái Đang Mở Bán', 'value' => number_format($allProducts->where('status', 'active')->where('stock', '>', 0)->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Giá Trị Hàng Đang Bán', 'value' => number_format($allProducts->where('status', 'active')->where('stock', '>', 0)->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Giá Bán Trung Bình', 'value' => number_format(round($allProducts->where('status', 'active')->where('stock', '>', 0)->avg('price') ?: 0), 0, ',', '.') . '₫', 'color' => 'text-primary'],
                ],
                'products' => $activeList,
            ],
            'inactive' => [
                'type' => 'inactive',
                'title' => 'Sản Phẩm Đang Ẩn / Tạm Dừng',
                'subtitle' => 'Các mẫu sản phẩm đã tạm dừng kinh doanh và ẩn hoàn toàn khỏi Website',
                'badge' => 'TẠM DỪNG',
                'badge_class' => 'bg-secondary text-white',
                'icon' => 'fa-solid fa-eye-slash',
                'filter_url' => route('admin.products.index', ['status' => 'inactive']),
                'metrics' => [
                    ['label' => 'Số Mẫu Tạm Dừng', 'value' => $inactiveList->count() . ' mẫu', 'color' => 'text-secondary'],
                    ['label' => 'Số Cái Đang Lưu Kho', 'value' => number_format($allProducts->where('status', 'inactive')->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                    ['label' => 'Giá Trị Hàng Tạm Dừng', 'value' => number_format($allProducts->where('status', 'inactive')->sum(fn($p) => $p->price * $p->stock), 0, ',', '.') . '₫', 'color' => 'text-danger'],
                    ['label' => 'Đã Từng Bán', 'value' => number_format($allProducts->where('status', 'inactive')->sum('sold_count'), 0, ',', '.') . ' cái', 'color' => 'text-muted'],
                ],
                'products' => $inactiveList,
            ],
            'low_stock' => [
                'type' => 'low_stock',
                'title' => 'Sản Phẩm Cảnh Báo Tồn Kho (≤ 5 Cái)',
                'subtitle' => 'Danh sách các mẫu sản phẩm đã hết hàng hoặc số lượng trong kho còn từ 1 đến 5 cái cần nhập thêm gấp',
                'badge' => 'CẢNH BÁO KHO',
                'badge_class' => 'bg-danger text-white',
                'icon' => 'fa-solid fa-triangle-exclamation',
                'filter_url' => route('admin.products.index', ['status' => 'out_of_stock']),
                'metrics' => [
                    ['label' => 'Tổng Mẫu Cảnh Báo', 'value' => $lowStockList->count() . ' mẫu', 'color' => 'text-danger'],
                    ['label' => 'Mẫu Đã Hết Sạch Kho', 'value' => $allProducts->where('stock', '<=', 0)->count() . ' mẫu', 'color' => 'text-danger'],
                    ['label' => 'Mẫu Sắp Hết (1-5 cái)', 'value' => $allProducts->where('stock', '>', 0)->where('stock', '<=', 5)->count() . ' mẫu', 'color' => 'text-warning'],
                    ['label' => 'Tổng Cái Còn Lại', 'value' => number_format($allProducts->where('stock', '<=', 5)->sum('stock'), 0, ',', '.') . ' cái', 'color' => 'text-dark'],
                ],
                'products' => $lowStockList,
            ],
        ];

        return view('admin.products.index', compact(
            'products', 
            'categories', 
            'brands',
            'categoryId', 
            'brandId',
            'status', 
            'search',
            'totalProductsCount',
            'activeProductsCount',
            'inactiveProductsCount',
            'lowStockProductsCount',
            'kpiDetailData'
        ));
    }