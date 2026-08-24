<!-- FILTER TOOLBAR -->
<div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">

    <form
        action="{{ route('admin.products.index') }}"
        method="GET"
        class="d-flex align-items-center gap-2 flex-wrap"
    >

        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            class="form-control form-control-sm"
            placeholder="Tìm tên hoặc mã SKU..."
            style="width: 260px;"
        >

        <select
            name="category_id"
            class="form-select form-select-sm"
            onchange="this.form.submit()"
            style="width: 200px;"
        >
            <option value="">Tất cả danh mục</option>

            @foreach($categories as $cat)
                <option
                    value="{{ $cat->id }}"
                    {{ request('category_id') == $cat->id ? 'selected' : '' }}
                >
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <select
            name="brand_id"
            class="form-select form-select-sm"
            onchange="this.form.submit()"
            style="width: 180px;"
        >
            <option value="">Tất cả thương hiệu</option>

            @foreach($brands as $b)
                <option
                    value="{{ $b->id }}"
                    {{ request('brand_id') == $b->id ? 'selected' : '' }}
                >
                    {{ $b->name }}
                </option>
            @endforeach
        </select>

        <button
            type="submit"
            class="btn btn-sm btn-outline-secondary"
        >
            Lọc
        </button>

        @if(request('q') || request('category_id') || request('brand_id'))

            <a
                href="{{ route('admin.products.index') }}"
                class="btn btn-sm btn-link text-danger p-0 ms-1"
            >
                Xóa lọc
            </a>

        @endif

    </form>


    <div class="text-muted small">

        Tổng số:
        <strong>{{ $products->total() }}</strong>
        sản phẩm

    </div>

</div>