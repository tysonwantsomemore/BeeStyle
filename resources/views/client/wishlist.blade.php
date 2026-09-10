@extends('layouts.client')

@section('title', 'Sản Phẩm Yêu Thích — BEESTYLE Studio')

@section('content')
<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Tác Phẩm Yêu Thích</span>
  </nav>

  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-8 pb-6 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-[0.4em] uppercase text-amber-800 font-semibold block mb-1">BỘ SƯU TẬP CÁ NHÂN</span>
      <h1 class="font-serif-luxury text-3xl md:text-4xl font-light text-neutral-900">Danh Sách Yêu Thích</h1>
    </div>
    @if($products->count() > 0)
      <div class="flex items-center gap-4">
        <span class="text-xs text-neutral-500">Đã lưu: <strong class="text-neutral-950 font-semibold">{{ $products->count() }}</strong> tác phẩm</span>
        <form action="{{ route('client.wishlist.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tất cả sản phẩm khỏi danh sách yêu thích?');">
          @csrf
          <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 font-semibold uppercase tracking-wider">
            Xóa Tất Cả
          </button>
        </form>
      </div>
    @endif
  </div>

  @if($products->isEmpty())
    <div class="bg-white p-12 md:p-16 rounded-2xl border border-neutral-200 text-center max-w-md mx-auto shadow-sm my-8">
      <div class="w-20 h-20 rounded-full bg-brand-100 flex items-center justify-center mx-auto mb-4 text-neutral-400">
        <i data-lucide="heart" class="w-10 h-10 stroke-1"></i>
      </div>
      <h2 class="font-serif-luxury text-2xl text-neutral-900 mb-2">Chưa có tác phẩm nào được lưu</h2>
      <p class="text-xs text-neutral-500 font-light mb-8 leading-relaxed">
        Hãy nhấn biểu tượng trái tim trên các sản phẩm bạn yêu thích để dễ dàng xem lại bất cứ lúc nào.
      </p>
      <a href="{{ route('client.products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-neutral-950 text-white text-xs font-semibold tracking-[0.2em] uppercase rounded-lg hover:bg-neutral-800 transition-all shadow-lg">
        <span>Khám Phá Sản Phẩm</span>
        <i data-lucide="arrow-right" class="w-4 h-4 text-amber-400"></i>
      </a>
    </div>
  @else
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-8">
      @foreach($products as $item)
        @php
          $itemImg = $item->primaryImage->image_path ?? $item->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=600&auto=format&fit=crop';
          if (!str_starts_with($itemImg, 'http')) {
            $itemImg = asset($itemImg);
          }
        @endphp
        <div class="group flex flex-col bg-white rounded-2xl border border-neutral-200/80 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
          <div class="relative aspect-[3/4] bg-neutral-100 overflow-hidden">
            <a href="{{ route('client.products.show', $item->id) }}" class="block w-full h-full">
              <img src="{{ $itemImg }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            </a>
            <form action="{{ route('client.wishlist.remove', $item->id) }}" method="POST" class="absolute top-3 right-3">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md flex items-center justify-center text-rose-600 shadow hover:scale-110 transition-transform" title="Bỏ yêu thích">
                <i data-lucide="heart" class="w-4 h-4 fill-rose-600"></i>
              </button>
            </form>
          </div>

          <div class="p-4 flex flex-col justify-between flex-grow">
            <div>
              <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold block mb-1">
                {{ $item->category->name ?? 'Beestyle' }}
              </span>
              <a href="{{ route('client.products.show', $item->id) }}" class="font-serif-luxury text-base font-semibold text-neutral-900 hover:text-amber-800 transition-colors line-clamp-1">
                {{ $item->name }}
              </a>
            </div>

            <div class="mt-3 pt-3 border-t border-neutral-100 flex items-center justify-between">
              <span class="font-serif-luxury text-base font-bold text-neutral-950">
                {{ number_format($item->price, 0, ',', '.') }}₫
              </span>
              <form action="{{ route('client.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $item->id }}">
                <input type="hidden" name="variant_id" value="{{ $item->variants->first()->id ?? '' }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="px-3 py-1.5 bg-neutral-950 text-white text-xs tracking-wider uppercase font-semibold rounded hover:bg-amber-400 hover:text-black transition-colors">
                  Mua Ngay
                </button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</main>
@endsection
