<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="@yield('meta_description', 'Beestyle - Thời trang thiết kế đương đại, sang trọng & tối giản. Khám phá bộ sưu tập quần áo, phụ kiện và lookbook mới nhất 2026.')">
  <title>@yield('title', 'BEESTYLE — Contemporary Fashion & Studio')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicons/favicon-32x32.png') }}">

  <!-- Google Fonts: Montserrat, Libre Franklin, Cormorant Garamond & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Libre+Franklin:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome 6 Pro Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com/3.4.17"></script>
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          fontFamily: {
            brand: ['"Montserrat"', 'sans-serif'],
            sans: ['"Libre Franklin"', 'sans-serif'],
            serif: ['"Cormorant Garamond"', 'serif'],
            body: ['"Plus Jakarta Sans"', 'sans-serif']
          },
          colors: {
            brand: {
              50: '#faf8f5',
              100: '#f5f0e8',
              200: '#e8dcce',
              300: '#d7c2ad',
              400: '#c3a388',
              500: '#b48c6f',
              600: '#a3775f',
              700: '#875f4d',
              800: '#6f4e41',
              900: '#1c1917',
              950: '#0c0a09'
            }
          }
        }
      }
    }
  </script>

  <style>
    body {
      font-family: 'Libre Franklin', sans-serif;
      -webkit-font-smoothing: antialiased;
      color: #1e293b;
    }
    .brand-logo-text {
      font-family: 'Montserrat', sans-serif;
      font-weight: 900;
      letter-spacing: 0.05em;
    }
    .font-serif-luxury {
      font-family: 'Cormorant Garamond', Georgia, serif;
    }
    /* Đảm bảo độ tương phản cao, màu chữ sắc nét trên toàn bộ các trang Client, triệt tiêu chữ mờ/ẩn */
    .text-contrast-body {
      color: #0f172a !important;
    }
    .text-contrast-muted {
      color: #475569 !important;
    }
    main .text-neutral-400:not(.text-white) {
      color: #64748b;
      font-weight: 500;
    }
    main .text-neutral-500:not(.text-white) {
      color: #475569;
      font-weight: 500;
    }
    main .text-gray-400:not(.text-white) {
      color: #64748b;
      font-weight: 500;
    }
    main .text-gray-500:not(.text-white) {
      color: #475569;
      font-weight: 500;
    }
    input, select, textarea {
      color: #0f172a !important;
    }
    ::placeholder {
      color: #64748b !important;
      opacity: 0.9 !important;
    }
    .collection-card img {
      transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .collection-card:hover img {
      transform: scale(1.05);
    }
    nav a {
      position: relative;
    }
    nav a::after {
      content: '';
      position: absolute;
      bottom: -4px;
      left: 0;
      width: 0;
      height: 1.5px;
      background: currentColor;
      transition: width 0.3s ease;
    }
    nav a:hover::after, nav a.active::after {
      width: 100%;
    }
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
      background: #c5b8a5;
      border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #998877;
    }
    @keyframes slideDown {
      from { transform: translateY(-100%); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    .animate-toast {
      animation: slideDown 0.3s ease-out forwards;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fadeIn 0.35s ease-out forwards;
    }
  </style>

  @stack('styles')
</head>

<body class="bg-brand-50 text-neutral-900 selection:bg-neutral-900 selection:text-white flex flex-col min-h-screen">

  <!-- ========================================================================= -->
  <!-- 1. ANNOUNCEMENT TOP BAR -->
  <!-- ========================================================================= -->
  <div class="bg-neutral-950 text-neutral-200 py-2 px-4 text-xs tracking-widest uppercase border-b border-neutral-800">
    <div class="max-w-7xl mx-auto flex justify-between items-center text-[11px]">
      <div class="flex items-center gap-4">
        <span><span class="text-amber-400 font-semibold">ƯU ĐÃI THÀNH VIÊN:</span> MÃ <strong class="text-white bg-neutral-800 px-1.5 py-0.5 rounded cursor-pointer hover:bg-neutral-700 transition-colors" onclick="copyCouponTopBar('BEESTYLE15')">BEESTYLE15</strong> GIẢM 15% | <strong class="text-white bg-neutral-800 px-1.5 py-0.5 rounded cursor-pointer hover:bg-neutral-700 transition-colors" onclick="copyCouponTopBar('BEESTYLE50')">BEESTYLE50</strong> GIẢM 50K</span>
        <span class="hidden lg:inline text-neutral-500">|</span>
        <span class="hidden lg:inline text-neutral-400">FREESHIP ĐƠN TỪ 500.000₫</span>
      </div>
      <div class="flex items-center gap-6">
        <a href="{{ route('client.order-tracking') }}" class="hover:text-white flex items-center gap-1 transition-colors">
          <i data-lucide="package" class="w-3.5 h-3.5"></i>
          <span>Tra Cứu Đơn Hàng</span>
        </a>
        <a href="{{ route('client.profile', ['tab' => 'returns']) }}" class="hover:text-white flex items-center gap-1 transition-colors">
          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
          <span>Đổi Trả &amp; Hoàn Tiền</span>
        </a>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- 2. STICKY MAIN HEADER -->
  <!-- ========================================================================= -->
  <header class="sticky top-0 left-0 w-full z-40 bg-white/95 backdrop-blur-md border-b border-neutral-200/80 transition-all duration-300 shadow-sm" id="main-header">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-3.5">
      
      <!-- CỤM BÊN TRÁI: MOBILE TOGGLE + LOGO THƯƠNG HIỆU + MENU ĐIỀU HƯỚNG BẮT ĐẦU VỚI TRANG CHỦ -->
      <div class="flex items-center gap-6 lg:gap-8">
        
        <!-- Mobile Menu Button -->
        <div class="flex items-center md:hidden">
          <button onclick="toggleMobileNav()" class="p-1.5 text-neutral-800 hover:text-black" aria-label="Menu">
            <i data-lucide="menu" class="w-6 h-6"></i>
          </button>
        </div>

        <!-- LOGO THƯƠNG HIỆU BEESTYLE (Ở GÓC BÊN TRÁI ĐẦU CẠNH TRANG CHỦ) -->
        <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2.5 text-decoration-none group select-none py-1 shrink-0">
          <div class="w-10 h-10 rounded-xl bg-amber-400 text-neutral-950 flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform shrink-0" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
            <i class="fa-solid fa-gem text-neutral-950 text-base"></i>
          </div>
          <div class="brand-logo-text text-left leading-tight">
            <div class="text-xl md:text-2xl font-black text-neutral-950 tracking-wider font-sans">BEE<span class="text-amber-500">STYLE</span></div>
            <div class="text-[9px] text-neutral-600 font-bold tracking-[0.25em] uppercase font-sans -mt-0.5">CONTEMPORARY FASHION</div>
          </div>
        </a>

        <!-- Main Desktop Navigation Links (Nằm ngay cạnh Logo) -->
        <nav class="hidden md:flex items-center gap-6 lg:gap-7 text-xs tracking-[0.16em] uppercase font-semibold text-neutral-700">
          <a href="{{ route('client.home') }}" class="hover:text-black transition-colors py-2 flex items-center gap-1 {{ request()->routeIs('client.home') ? 'active text-neutral-950 font-bold text-amber-700' : '' }}">
            <span>Trang Chủ</span>
          </a>
          
          <!-- ZARA MEGA MENU TRIGGER (SẢN PHẨM) -->
          <div class="relative group/mega py-2">
            <a href="{{ route('client.products.index') }}" class="hover:text-black transition-colors flex items-center gap-1 font-semibold text-neutral-900 {{ request()->routeIs('client.products.*') ? 'text-amber-800' : '' }}">
              <span>Sản Phẩm</span>
              <i data-lucide="chevron-down" class="w-3 h-3 group-hover/mega:rotate-180 transition-transform duration-300 text-neutral-400"></i>
            </a>

            <!-- Dark backdrop overlay -->
            <div class="fixed inset-0 top-[65px] bg-neutral-950/60 backdrop-blur-sm transition-opacity duration-300 opacity-0 invisible group-hover/mega:opacity-100 group-hover/mega:visible pointer-events-none z-40"></div>

            <!-- ZARA MEGA MENU DROPDOWN -->
            <div class="fixed top-[65px] left-0 w-full bg-white border-b-2 border-neutral-950 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.3)] transition-all duration-200 opacity-0 invisible group-hover/mega:opacity-100 group-hover/mega:visible pointer-events-none group-hover/mega:pointer-events-auto z-50">
              <div class="max-w-7xl mx-auto px-8 py-10 grid grid-cols-12 gap-8 text-neutral-950 bg-white">
                
                <!-- Col 1: Big Typography Departments -->
                <div class="col-span-3 space-y-2 pr-6 border-r border-neutral-200">
                  <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="font-serif text-3xl xl:text-4xl text-neutral-950 font-bold hover:text-amber-800 transition-colors uppercase tracking-tight py-0.5 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-neutral-950 inline-block"></span>
                    NAM
                  </a>
                  <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="block font-serif text-3xl xl:text-4xl text-neutral-500 hover:text-black transition-colors uppercase tracking-tight py-0.5 font-normal">
                    BLAZER &amp; ÁO KHOÁC
                  </a>
                  <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="block font-serif text-3xl xl:text-4xl text-neutral-500 hover:text-black transition-colors uppercase tracking-tight py-0.5 font-normal">
                    POLO LUXURY
                  </a>
                  <a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="block font-serif text-3xl xl:text-4xl text-neutral-500 hover:text-black transition-colors uppercase tracking-tight py-0.5 font-normal">
                    ÁO THUN &amp; DẠO PHỐ
                  </a>
                  <a href="{{ route('client.home') }}#lookbook" class="block font-serif text-2xl xl:text-3xl text-neutral-500 hover:text-black transition-colors uppercase tracking-tight py-0.5 font-normal">
                    LOOKBOOK 2026
                  </a>
                  <div class="pt-6 border-t border-neutral-100 mt-4">
                    <span class="font-serif-luxury text-sm tracking-[0.3em] text-neutral-950 font-bold uppercase block">BEESTYLE</span>
                    <span class="text-[9px] tracking-[0.4em] text-neutral-500 uppercase font-sans font-semibold">ATELIER VIETNAM</span>
                  </div>
                </div>

                <!-- Col 2: Numbered Categories & Lists -->
                <div class="col-span-4 space-y-5 text-xs tracking-wider uppercase font-medium pl-4">
                  <div class="space-y-1">
                    <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[01] MỚI</span>
                    <a href="{{ route('client.products.index', ['sort' => 'latest']) }}" class="block text-neutral-950 hover:text-amber-800 font-bold text-sm transition-colors">THE NEW 2026</a>
                    <a href="{{ route('client.products.index') }}" class="block text-neutral-600 hover:text-black transition-colors">SARTORIAL x BEESTYLE</a>
                  </div>

                  <div class="space-y-1">
                    <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[02] ĐẶC QUYỀN</span>
                    <a href="{{ route('client.products.index', ['featured' => 1]) }}" class="block text-neutral-800 hover:text-black font-semibold transition-colors">BEESTYLE ORIGINS</a>
                  </div>

                  <div class="space-y-1">
                    <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[03] ƯU ĐÃI</span>
                    <a href="{{ route('client.daily-deals.index') }}" class="block text-rose-700 font-bold hover:text-rose-800 transition-colors flex items-center gap-1.5">
                      <span>FLASH SALE TRONG NGÀY</span>
                      <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded text-[10px]">HOT</span>
                    </a>
                  </div>

                  <div class="space-y-2 pt-4 border-t border-neutral-200">
                    <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[04] DANH MỤC SẢN PHẨM</span>
                    <div class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-xs leading-relaxed">
                      <a href="{{ route('client.products.index') }}" class="text-neutral-950 font-bold hover:underline">XEM TẤT CẢ</a>
                      <a href="{{ route('client.products.index', ['sort' => 'bestseller']) }}" class="text-amber-800 font-bold hover:underline">BEST SELLERS</a>
                      <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="text-neutral-700 hover:text-black font-medium">ÁO POLO NAM</a>
                      <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="text-neutral-700 hover:text-black font-medium">ÁO SƠ MI LỤA</a>
                      <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="text-neutral-700 hover:text-black font-medium">BLAZER MAY ĐO</a>
                      <a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="text-neutral-700 hover:text-black font-medium">ÁO THUN FOAM BOXY</a>
                      <a href="{{ route('client.products.index', ['category' => 'ao-thu-dong-nam']) }}" class="text-neutral-700 hover:text-black font-medium">ÁO THU ĐÔNG</a>
                      <a href="{{ route('client.brands.index') }}" class="text-neutral-700 hover:text-black font-medium">THƯƠNG HIỆU ĐỐI TÁC</a>
                    </div>
                  </div>
                </div>

                <!-- Col 3: Curated Visual Thumbnails -->
                <div class="col-span-5 grid grid-cols-3 gap-3.5 pl-6 border-l border-neutral-200">
                  <a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="group/card flex flex-col">
                    <div class="aspect-[3/4] rounded-lg bg-neutral-100 overflow-hidden mb-2 border border-neutral-200 shadow-sm">
                      <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=400&auto=format&fit=crop" alt="Blazer May Đo" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                    </div>
                    <span class="text-[10px] tracking-wider uppercase font-bold text-neutral-900 text-center truncate group-hover/card:underline">BLAZER MAY ĐO</span>
                  </a>

                  <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="group/card flex flex-col">
                    <div class="aspect-[3/4] rounded-lg bg-neutral-100 overflow-hidden mb-2 border border-neutral-200 shadow-sm">
                      <img src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=400&auto=format&fit=crop" alt="Áo Sơ Mi Lụa" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                    </div>
                    <span class="text-[10px] tracking-wider uppercase font-bold text-neutral-900 text-center truncate group-hover/card:underline">SƠ MI LỤA PHÁP</span>
                  </a>

                  <a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="group/card flex flex-col">
                    <div class="aspect-[3/4] rounded-lg bg-neutral-100 overflow-hidden mb-2 border border-neutral-200 shadow-sm">
                      <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=400&auto=format&fit=crop" alt="Polo Nam" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                    </div>
                    <span class="text-[10px] tracking-wider uppercase font-bold text-neutral-900 text-center truncate group-hover/card:underline">POLO SỢI COTTON</span>
                  </a>
                </div>

              </div>
            </div>
          </div>

          <a href="{{ route('client.home') }}#collections" class="hover:text-black transition-colors py-2">Bộ Sưu Tập</a>
          <a href="{{ route('client.home') }}#lookbook" class="hover:text-black transition-colors py-2">Lookbook</a>
          <a href="{{ route('client.home') }}#about" class="hover:text-black transition-colors py-2">Về BeeStyle</a>
        </nav>
      </div>

      <!-- Action Icons Right -->
      <div class="flex items-center gap-4 lg:gap-5 text-neutral-800">
        
        <!-- Quick Search Bar (Desktop) -->
        <div class="hidden lg:block relative">
          <form action="{{ route('client.products.index') }}" method="GET" class="relative">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm may đo..." class="w-44 xl:w-52 bg-neutral-100 text-neutral-900 placeholder:text-neutral-500 text-xs rounded-full pl-8 pr-3 py-1.5 border border-transparent focus:border-neutral-300 focus:bg-white focus:outline-none transition-all">
            <i data-lucide="search" class="w-3.5 h-3.5 text-neutral-500 absolute left-2.5 top-2"></i>
          </form>
        </div>
        
        <!-- Wishlist Link -->
        <a href="{{ route('client.wishlist.index') }}" class="relative hover:text-black transition-colors p-1" title="Yêu thích">
          <i data-lucide="heart" class="w-5 h-5"></i>
          <span id="wishlistCountBadge" class="absolute -top-1 -right-1 bg-amber-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold {{ (isset($wishlistCount) && $wishlistCount > 0) ? '' : 'hidden' }}">
            {{ $wishlistCount ?? 0 }}
          </span>
        </a>

        <!-- Shopping Cart Link -->
        <a href="{{ route('client.cart') }}" class="relative hover:text-black transition-colors flex items-center gap-2 p-1" title="Túi mua hàng">
          <div class="relative">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            @php $cartQty = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
            <span id="cartCountBadge" class="bee-cart-count absolute -top-1.5 -right-1.5 bg-neutral-900 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold {{ $cartQty > 0 ? '' : 'hidden' }}">
              {{ $cartQty }}
            </span>
          </div>
          @php
            $cartSubtotal = 0;
            if (session('cart')) {
              foreach(session('cart') as $cItem) {
                $cartSubtotal += ($cItem['price'] ?? 0) * ($cItem['quantity'] ?? 1);
              }
            }
          @endphp
          <span id="headerCartSubtotal" class="hidden lg:inline text-xs font-semibold tracking-wider text-neutral-900">{{ number_format($cartSubtotal, 0, ',', '.') }}₫</span>
        </a>

        <!-- Notification Bell & Dropdown -->
        <div class="relative" id="headerNotificationWrapper">
          @php
            $allShopNotifs = $allShopNotifications ?? collect();
            $unreadNotifCount = $allShopNotifs->where('is_unread', true)->count();
          @endphp
          <button type="button" id="headerNotificationBtn" onclick="toggleNotificationDropdown(event)" class="relative hover:text-black transition-colors p-1 flex items-center justify-center text-neutral-700" title="Thông báo đơn hàng & hoạt động">
            <i data-lucide="bell" class="w-5 h-5"></i>
            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-rose-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold {{ $unreadNotifCount > 0 ? '' : 'hidden' }}">
              {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
            </span>
          </button>

          <!-- Dropdown Menu -->
          <div id="notificationDropdownMenu" class="hidden absolute right-0 sm:-right-10 top-full mt-2 w-80 sm:w-96 bg-white border border-neutral-200 rounded-2xl shadow-2xl py-3 z-50 animate-fade-in divide-y divide-neutral-100">
            <div class="px-4 pb-2.5 flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="font-bold text-neutral-900 text-sm">Thông Báo</span>
                <span id="unreadNotifCountBadge" class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 text-[10px] font-bold {{ $unreadNotifCount > 0 ? '' : 'hidden' }}">{{ $unreadNotifCount }} mới</span>
              </div>
              <a href="{{ route('client.order-tracking') }}" class="text-[11px] text-amber-700 hover:text-amber-800 font-medium flex items-center gap-1">
                <i data-lucide="search" class="w-3 h-3"></i> Tra cứu đơn
              </a>
            </div>

            <div class="max-h-96 overflow-y-auto divide-y divide-neutral-100" id="notificationItemsList">
              @forelse($allShopNotifs as $notif)
                <div class="p-3.5 hover:bg-neutral-50 transition-colors {{ ($notif['is_unread'] ?? false) ? 'bg-amber-50/40' : '' }}" id="notifItem_{{ $notif['id'] ?? '' }}">
                  <div class="flex items-start gap-3">
                    <div class="shrink-0 mt-0.5">
                      @if(!empty($notif['image']))
                        <img src="{{ $notif['image'] }}" class="w-10 h-10 object-cover rounded-lg border border-neutral-200" alt="Item">
                      @else
                        <div class="w-9 h-9 rounded-xl bg-neutral-100 border border-neutral-200 flex items-center justify-center text-xs">
                          <i class="{{ $notif['icon'] ?? 'fa-solid fa-bell text-neutral-600' }}"></i>
                        </div>
                      @endif
                    </div>
                    <div class="flex-grow min-w-0">
                      <div class="flex items-center justify-between gap-1 mb-1">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $notif['badge_class'] ?? 'bg-neutral-100 text-neutral-700' }}">
                          {{ $notif['badge'] ?? 'Thông báo' }}
                        </span>
                        <span class="text-[10px] text-neutral-400">{{ $notif['time_ago'] ?? '' }}</span>
                      </div>
                      <h5 class="text-xs font-bold text-neutral-900 leading-tight mb-1">{{ $notif['title'] ?? '' }}</h5>
                      <p class="text-[11px] text-neutral-600 leading-relaxed line-clamp-2 mb-2">{{ $notif['content'] ?? '' }}</p>

                      {{-- Các nút hành động tùy loại thông báo --}}
                      @if(($notif['action_type'] ?? '') === 'confirm_or_return')
                        <div class="flex items-center gap-2 pt-1">
                          <button type="button" onclick="confirmDeliveredAjax('{{ $notif['order_code'] }}', {{ $notif['first_product_id'] ?? 1 }}, '{{ addslashes($notif['first_product_name'] ?? 'Sản phẩm') }}', '{{ addslashes($notif['image'] ?? '') }}')" class="flex-1 py-1.5 px-2.5 bg-neutral-900 hover:bg-black text-white text-[11px] font-bold rounded-lg transition-colors shadow-xs flex items-center justify-center gap-1.5">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-400"></i> Đã Nhận Hàng
                          </button>
                          <a href="{{ route('client.order-tracking', ['code' => $notif['order_code']]) }}#carrierTrackingPassSection" class="py-1.5 px-2.5 bg-white hover:bg-rose-50 text-rose-700 border border-rose-300 text-[11px] font-semibold rounded-lg transition-colors">
                            Đổi Trả / Hoàn Tiền
                          </a>
                        </div>
                      @elseif(($notif['action_type'] ?? '') === 'review')
                        <div class="pt-1">
                          <button type="button" onclick="openGlobalReviewModal({{ $notif['product_id'] }}, '{{ addslashes($notif['product_name'] ?? 'Sản phẩm') }}', '{{ $notif['image'] ?? '' }}', '{{ $notif['order_code'] ?? '' }}')" class="w-full py-1.5 px-3 bg-amber-500 hover:bg-amber-600 text-white text-[11px] font-bold rounded-lg transition-colors shadow-xs flex items-center justify-center gap-1.5">
                            <i data-lucide="star" class="w-3.5 h-3.5 fill-white"></i> Đánh Giá Ngay (Tặng Voucher)
                          </button>
                        </div>
                      @elseif(!empty($notif['link']))
                        <div class="pt-1">
                          <a href="{{ $notif['link'] }}" class="inline-flex items-center gap-1 text-[11px] font-semibold text-neutral-800 hover:text-amber-700">
                            {{ $notif['action_text'] ?? 'Chi tiết' }} <i data-lucide="arrow-right" class="w-3 h-3"></i>
                          </a>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              @empty
                <div class="p-8 text-center text-neutral-400 text-xs">
                  <i data-lucide="bell-off" class="w-8 h-8 mx-auto mb-2 text-neutral-300"></i>
                  <p>Hiện không có thông báo nào mới.</p>
                </div>
              @endforelse
            </div>

            <div class="px-4 pt-2.5 flex items-center justify-between text-[11px]">
              <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="text-neutral-600 hover:text-neutral-950 font-medium flex items-center gap-1">
                <i data-lucide="package" class="w-3.5 h-3.5"></i> Đơn hàng của tôi
              </a>
              <a href="{{ route('client.profile', ['tab' => 'pending-reviews']) }}" class="text-amber-800 hover:text-amber-900 font-semibold flex items-center gap-1">
                <i data-lucide="star" class="w-3.5 h-3.5"></i> Chờ đánh giá
              </a>
            </div>
          </div>
        </div>

        <!-- Auth Area -->
        <div class="header-auth-area relative">
          @auth
            <div class="relative group/user py-2">
              <button class="flex items-center gap-2 text-xs font-medium text-neutral-800 hover:text-black transition-colors">
                <div class="w-7 h-7 rounded-full bg-neutral-900 text-white flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                  {{ mb_substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <span class="hidden md:inline font-semibold max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-neutral-400 group-hover/user:rotate-180 transition-transform"></i>
              </button>

              <!-- Dropdown Menu -->
              <div class="absolute right-0 top-full w-56 bg-white border border-neutral-200 rounded-xl shadow-xl py-2 opacity-0 invisible group-hover/user:opacity-100 group-hover/user:visible transition-all duration-200 z-50">
                <div class="px-4 py-2 border-b border-neutral-100">
                  <p class="text-xs font-semibold text-neutral-900 truncate">{{ Auth::user()->name }}</p>
                  <p class="text-[11px] text-neutral-500 truncate">{{ Auth::user()->email }}</p>
                </div>
                @if(Auth::user()->isAdmin())
                  <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-amber-800 hover:bg-amber-50 font-semibold transition-colors">
                    <i data-lucide="shield" class="w-4 h-4 text-amber-600"></i> Quản Trị Hệ Thống (Admin)
                  </a>
                @endif
                <a href="{{ route('client.profile') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="user" class="w-4 h-4 text-neutral-400"></i> Hồ sơ tài khoản
                </a>
                <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="shopping-bag" class="w-4 h-4 text-neutral-400"></i> Đơn hàng của tôi
                </a>
                <a href="{{ route('client.profile', ['tab' => 'pending-reviews']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="clock" class="w-4 h-4 text-neutral-400"></i> Chờ đánh giá
                </a>
                <a href="{{ route('client.profile', ['tab' => 'addresses']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="map-pin" class="w-4 h-4 text-neutral-400"></i> Sổ địa chỉ nhận hàng
                </a>
                <div class="border-t border-neutral-100 my-1"></div>
                <form action="{{ route('auth.logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="w-full text-left flex items-center gap-2.5 px-4 py-2.5 text-xs text-rose-600 hover:bg-rose-50 font-medium transition-colors">
                    <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i> Đăng xuất
                  </button>
                </form>
              </div>
            </div>
          @else
            <a href="{{ route('auth.login') }}" class="flex items-center gap-1.5 text-xs uppercase tracking-wider text-neutral-700 hover:text-black font-semibold transition-colors">
              <i data-lucide="user" class="w-4 h-4"></i>
              <span class="hidden md:inline">Đăng Nhập</span>
            </a>
          @endauth
        </div>

      </div>

    </div>

    <!-- Mobile Navigation Drawer -->
    <div id="mobile-nav" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden">
      <div class="w-4/5 max-w-sm h-full bg-white p-6 flex flex-col justify-between shadow-2xl animate-fade-in">
        <div>
          <div class="flex justify-between items-center pb-4 border-b border-neutral-200">
            <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2 text-decoration-none">
              <div class="w-8 h-8 rounded-lg bg-amber-400 text-neutral-950 flex items-center justify-center font-bold shadow-sm shrink-0" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
                <i class="fa-solid fa-gem text-neutral-950 text-xs"></i>
              </div>
              <div class="brand-logo-text text-left leading-tight">
                <div class="text-lg font-black text-neutral-950 tracking-wider font-sans">BEE<span class="text-amber-500">STYLE</span></div>
                <div class="text-[8px] text-neutral-600 font-bold tracking-[0.2em] uppercase font-sans">MENSWEAR</div>
              </div>
            </a>
            <button onclick="toggleMobileNav()" class="p-2 text-neutral-500 hover:text-black">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <nav class="flex flex-col gap-4 mt-6 text-sm tracking-[0.2em] uppercase font-medium">
            <a href="{{ route('client.home') }}" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Trang Chủ</a>
            <a href="{{ route('client.products.index') }}" class="py-2 border-b border-neutral-100 text-neutral-950 font-bold">Tất Cả Sản Phẩm</a>
            <a href="{{ route('client.daily-deals.index') }}" class="py-2 border-b border-neutral-100 text-rose-700 font-bold flex items-center justify-between">
              <span>Flash Sale Ngày</span>
              <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded text-[10px]">HOT</span>
            </a>
            <a href="{{ route('client.home') }}#lookbook" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Lookbook Editorial</a>
            <a href="{{ route('client.order-tracking') }}" class="py-2 text-amber-700 flex items-center gap-2">
              <i data-lucide="package" class="w-4 h-4"></i> Tra cứu đơn hàng
            </a>
            <div class="mt-2 pt-3 border-t border-neutral-100 flex flex-col gap-2">
              <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold">TÀI KHOẢN KHÁCH HÀNG</span>
              @auth
                <a href="{{ route('client.profile') }}" class="py-1 text-xs text-neutral-800 flex items-center gap-2 font-normal">
                  <i data-lucide="user" class="w-4 h-4 text-neutral-500"></i> {{ Auth::user()->name }}
                </a>
                <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="py-1 text-xs text-neutral-800 flex items-center gap-2 font-normal">
                  <i data-lucide="shopping-bag" class="w-4 h-4 text-neutral-500"></i> Đơn hàng của tôi
                </a>
                <a href="{{ route('client.profile', ['tab' => 'pending-reviews']) }}" class="py-1 text-xs text-amber-800 flex items-center gap-2 font-medium">
                  <i data-lucide="star" class="w-4 h-4 text-amber-500 fill-amber-500"></i> Chờ đánh giá (Tặng Voucher)
                </a>
                <form action="{{ route('auth.logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="py-1 text-xs text-rose-600 flex items-center gap-2 font-normal">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Đăng xuất
                  </button>
                </form>
              @else
                <a href="{{ route('auth.login') }}" class="py-1 text-xs text-neutral-800 flex items-center gap-2 font-semibold">
                  <i data-lucide="log-in" class="w-4 h-4 text-neutral-500"></i> Đăng nhập / Đăng ký
                </a>
              @endauth
            </div>
          </nav>
        </div>
        <div class="pt-6 border-t border-neutral-200 text-xs text-neutral-500">
          <p class="font-medium text-neutral-800 mb-1">BEESTYLE ATELIER VIETNAM</p>
          <p>Hotline: 1900 8899 (8:00 - 22:00)</p>
          <p class="mt-2 text-[11px]">© 2026 Beestyle. All rights reserved.</p>
        </div>
      </div>
    </div>
  </header>

  <!-- ========================================================================= -->
  <!-- 3. MAIN PAGE CONTENT -->
  <!-- ========================================================================= -->
  <main class="w-full flex-grow">
    @if(session('success'))
      <div class="max-w-7xl mx-auto px-6 pt-4">
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg text-xs flex items-center justify-between shadow-sm animate-fade-in">
          <div class="flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
            <span>{{ session('success') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
        </div>
      </div>
    @endif

    @if(session('error'))
      <div class="max-w-7xl mx-auto px-6 pt-4">
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-lg text-xs flex items-center justify-between shadow-sm animate-fade-in">
          <div class="flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span>{{ session('error') }}</span>
          </div>
          <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
        </div>
      </div>
    @endif

    @yield('content')
  </main>

  <!-- ========================================================================= -->
  <!-- 4. LUXURY FOOTER (ATELIER COUTURE STANDARD) -->
  <!-- ========================================================================= -->
  <footer class="w-full bg-neutral-950 text-neutral-300 pt-14 pb-10 border-t border-neutral-800 mt-auto selection:bg-amber-500 selection:text-neutral-950">
    
    <!-- Top Footer: 4 Service Guarantees (Dải Cam Kết Chất Lượng) -->
    <div class="max-w-7xl mx-auto px-6 pb-12 border-b border-neutral-800/80">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="flex items-center gap-3.5 p-4 rounded-xl bg-neutral-900/60 border border-neutral-800/60 hover:border-amber-500/30 transition-colors">
          <div class="w-11 h-11 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-400/20">
            <i class="fa-solid fa-truck-fast"></i>
          </div>
          <div>
            <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-0.5">Giao Hàng Hỏa Tốc</h5>
            <p class="text-[11px] text-neutral-400 mb-0">Miễn phí toàn quốc cho đơn từ 500K</p>
          </div>
        </div>

        <div class="flex items-center gap-3.5 p-4 rounded-xl bg-neutral-900/60 border border-neutral-800/60 hover:border-amber-500/30 transition-colors">
          <div class="w-11 h-11 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-400/20">
            <i class="fa-solid fa-rotate-left"></i>
          </div>
          <div>
            <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-0.5">Đổi Trả 30 Ngày</h5>
            <p class="text-[11px] text-neutral-400 mb-0">Đổi size, mẫu tận nhà, hoàn tiền linh hoạt</p>
          </div>
        </div>

        <div class="flex items-center gap-3.5 p-4 rounded-xl bg-neutral-900/60 border border-neutral-800/60 hover:border-amber-500/30 transition-colors">
          <div class="w-11 h-11 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-400/20">
            <i class="fa-solid fa-certificate"></i>
          </div>
          <div>
            <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-0.5">Lụa &amp; Cotton Cao Cấp</h5>
            <p class="text-[11px] text-neutral-400 mb-0">100% tự nhiên, cắt may chuẩn may đo Ý</p>
          </div>
        </div>

        <div class="flex items-center gap-3.5 p-4 rounded-xl bg-neutral-900/60 border border-neutral-800/60 hover:border-amber-500/30 transition-colors">
          <div class="w-11 h-11 rounded-xl bg-amber-400/10 text-amber-400 flex items-center justify-center text-lg shrink-0 border border-amber-400/20">
            <i class="fa-solid fa-headset"></i>
          </div>
          <div>
            <h5 class="text-xs font-bold uppercase tracking-wider text-white mb-0.5">Tư Vấn Phong Cách 24/7</h5>
            <p class="text-[11px] text-neutral-400 mb-0">Hotline 1900 8899 hỗ trợ tận tình</p>
          </div>
        </div>

      </div>
    </div>

    <!-- Main Footer Columns (Grid 4 Columns) -->
    <div class="max-w-7xl mx-auto px-6 pt-12 pb-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
      
      <!-- Col 1: Brand & Social (4 cols) -->
      <div class="lg:col-span-4 space-y-4">
        <!-- Logo đồng bộ chuẩn admin -->
        <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2.5 text-decoration-none group select-none">
          <div class="w-10 h-10 rounded-xl bg-amber-400 text-neutral-950 flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform shrink-0" style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);">
            <i class="fa-solid fa-gem text-neutral-950 text-base"></i>
          </div>
          <div class="brand-logo-text text-left leading-tight">
            <div class="text-xl font-black text-white tracking-wider font-sans">BEE<span class="text-amber-400">STYLE</span></div>
            <div class="text-[9px] text-neutral-400 font-bold tracking-[0.22em] uppercase font-sans -mt-0.5">MENSWEAR &amp; ATELIER</div>
          </div>
        </a>

        <p class="text-xs leading-relaxed text-neutral-400 max-w-sm">
          Nhà may đương đại &amp; thời trang thiết kế nam may đo tối giản. Tôn vinh nét lịch lãm, phóng khoáng và sự chuẩn mực trường tồn trong từng đường kim mũi chỉ.
        </p>

        <!-- Social Media Icons -->
        <div class="flex items-center gap-3 pt-1">
          <a href="#" class="w-8 h-8 rounded-lg bg-neutral-900 border border-neutral-800 text-neutral-400 hover:text-amber-400 hover:border-amber-400/40 flex items-center justify-center text-xs transition-all" title="Facebook">
            <i class="fa-brands fa-facebook-f"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-lg bg-neutral-900 border border-neutral-800 text-neutral-400 hover:text-amber-400 hover:border-amber-400/40 flex items-center justify-center text-xs transition-all" title="Instagram">
            <i class="fa-brands fa-instagram"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-lg bg-neutral-900 border border-neutral-800 text-neutral-400 hover:text-amber-400 hover:border-amber-400/40 flex items-center justify-center text-xs transition-all" title="TikTok">
            <i class="fa-brands fa-tiktok"></i>
          </a>
          <a href="#" class="w-8 h-8 rounded-lg bg-neutral-900 border border-neutral-800 text-neutral-400 hover:text-amber-400 hover:border-amber-400/40 flex items-center justify-center text-xs transition-all" title="YouTube">
            <i class="fa-brands fa-youtube"></i>
          </a>
        </div>

        <!-- Chứng nhận Bộ Công Thương Badge -->
        <div class="pt-2">
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-neutral-900 border border-neutral-800 text-[11px] text-neutral-300">
            <i class="fa-solid fa-shield-halved text-emerald-400"></i>
            <span>Chứng Nhận Đã Thông Báo BCT</span>
          </div>
        </div>
      </div>

      <!-- Col 2: Categories (2 cols) -->
      <div class="lg:col-span-2 space-y-3">
        <h4 class="text-xs uppercase tracking-[0.2em] font-bold text-white mb-4">Danh Mục</h4>
        <ul class="space-y-2.5 text-xs text-neutral-400">
          <li><a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-neutral-600"></span> Sơ Mi Lụa May Đo</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-neutral-600"></span> Blazer &amp; Áo Khoác Ý</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-neutral-600"></span> Polo Luxury Cotton</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-neutral-600"></span> Áo Thun Form Boxy</a></li>
          <li><a href="{{ route('client.products.index', ['sort' => 'bestseller']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-neutral-600"></span> Tác Phẩm Bán Chạy</a></li>
          <li><a href="{{ route('client.daily-deals.index') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5 text-rose-400 font-semibold"><span class="w-1 h-1 rounded-full bg-rose-500"></span> Flash Sale Ưu Đãi</a></li>
        </ul>
      </div>

      <!-- Col 3: Customer Service (3 cols) -->
      <div class="lg:col-span-3 space-y-3">
        <h4 class="text-xs uppercase tracking-[0.2em] font-bold text-white mb-4">Chăm Sóc Khách Hàng</h4>
        <ul class="space-y-2.5 text-xs text-neutral-400">
          <li><a href="{{ route('client.order-tracking') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-magnifying-glass text-[10px]"></i> Tra Cứu Đơn Hàng Hỏa Tốc</a></li>
          <li><a href="{{ route('client.profile', ['tab' => 'returns']) }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-arrow-rotate-left text-[10px]"></i> Đổi Hàng &amp; Hoàn Tiền 30 Ngày</a></li>
          <li><a href="{{ route('client.profile') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-crown text-[10px] text-amber-400"></i> Đặc Quyền Hội Viên Atelier</a></li>
          <li><a href="{{ route('client.home') }}#about" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-circle-info text-[10px]"></i> Câu Chuyện Thương Hiệu</a></li>
          <li><a href="{{ route('client.cart') }}" class="hover:text-amber-400 transition-colors flex items-center gap-1.5"><i class="fa-solid fa-bag-shopping text-[10px]"></i> Túi Mua Hàng &amp; Giỏ Hàng</a></li>
        </ul>
      </div>

      <!-- Col 4: Stores & Payment Methods (3 cols) -->
      <div class="lg:col-span-3 space-y-3">
        <h4 class="text-xs uppercase tracking-[0.2em] font-bold text-white mb-4">Hệ Thống Atelier &amp; Hotline</h4>
        <div class="space-y-2 text-xs text-neutral-400 leading-relaxed">
          <p class="flex items-start gap-2">
            <i class="fa-solid fa-location-dot text-amber-400 mt-1 shrink-0"></i>
            <span><strong>Flagship HCM:</strong> 88 Lê Lợi, P. Bến Nghé, Quận 1, TP. Hồ Chí Minh</span>
          </p>
          <p class="flex items-start gap-2">
            <i class="fa-solid fa-location-dot text-amber-400 mt-1 shrink-0"></i>
            <span><strong>Store Hà Nội:</strong> 26 Phố Huế, P. Hàng Bài, Q. Hoàn Kiếm, Hà Nội</span>
          </p>
          <p class="flex items-center gap-2 pt-1 text-white">
            <i class="fa-solid fa-phone text-amber-400 shrink-0"></i>
            <span>Hotline: <strong class="text-amber-400 font-mono text-sm tracking-wide">1900 8899</strong> (8:00 - 22:00)</span>
          </p>
          <p class="flex items-center gap-2 text-neutral-400">
            <i class="fa-solid fa-envelope text-neutral-500 shrink-0"></i>
            <span>Email: <a href="mailto:cskh@beestyle.vn" class="text-neutral-300 hover:text-amber-400 transition-colors">cskh@beestyle.vn</a></span>
          </p>
        </div>

        <!-- Payment Badges -->
        <div class="pt-3 border-t border-neutral-800">
          <span class="text-[10px] uppercase tracking-wider text-neutral-400 font-semibold block mb-2">Phương Thức Thanh Toán</span>
          <div class="flex items-center gap-2 flex-wrap text-neutral-300">
            <span class="px-2 py-1 rounded bg-neutral-900 border border-neutral-800 text-[10px] font-bold">VISA</span>
            <span class="px-2 py-1 rounded bg-neutral-900 border border-neutral-800 text-[10px] font-bold">MASTERCARD</span>
            <span class="px-2 py-1 rounded bg-neutral-900 border border-neutral-800 text-[10px] font-bold text-rose-400">MOMO</span>
            <span class="px-2 py-1 rounded bg-neutral-900 border border-neutral-800 text-[10px] font-bold text-sky-400">ZALOPAY</span>
            <span class="px-2 py-1 rounded bg-neutral-900 border border-neutral-800 text-[10px] font-bold text-amber-400">COD</span>
          </div>
        </div>
      </div>

    </div>

    <!-- Bottom Bar: Copyright & Legal -->
    <div class="max-w-7xl mx-auto px-6 pt-6 border-t border-neutral-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-[11px] text-neutral-400">
      <div>
        © 2026 <strong class="text-white">BEESTYLE ATELIER VIETNAM</strong>. TẤT CẢ QUYỀN ĐƯỢC BẢO LƯU.
      </div>
      <div class="flex items-center gap-6">
        <a href="{{ route('client.home') }}#about" class="hover:text-white transition-colors">Điều Khoản Dịch Vụ</a>
        <span>•</span>
        <a href="{{ route('client.profile', ['tab' => 'returns']) }}" class="hover:text-white transition-colors">Chính Sách Bảo Mật</a>
        <span>•</span>
        <a href="{{ route('client.order-tracking') }}" class="hover:text-white transition-colors">Chính Sách Vận Chuyển</a>
      </div>
    </div>
  </footer>

  <!-- ========================================================================= -->
  <!-- MODAL 1: CHI TIẾT NHANH & CHỌN BIẾN THỂ (QUICK VIEW VARIANT MODAL) -->
  <!-- ========================================================================= -->
  <div id="quickVariantModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl border border-neutral-200 overflow-hidden animate-fade-in text-xs max-h-[92vh] flex flex-col">
      <div class="flex items-center justify-between p-4 border-b border-neutral-100 shrink-0">
        <div class="flex items-center gap-2">
          <span id="qvmModeBadge" class="px-2 py-0.5 bg-amber-100 text-amber-900 rounded font-bold uppercase text-[10px]">CHI TIẾT NHANH</span>
          <span id="qvmCategoryBadge" class="text-neutral-500 text-[11px]">Thời trang nam</span>
          <span class="text-neutral-300">•</span>
          <span id="qvmSkuText" class="font-mono text-neutral-500 text-[11px]">SKU: BS-01</span>
        </div>
        <button type="button" onclick="closeQuickVariantModal()" class="text-neutral-400 hover:text-black">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <div class="p-6 overflow-y-auto grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        <!-- Cột ảnh -->
        <div class="md:col-span-5 space-y-3 text-center">
          <div class="w-full aspect-[3/4] bg-neutral-100 rounded-xl overflow-hidden border border-neutral-200 flex items-center justify-center relative">
            <span id="qvmDiscountBadge" class="absolute top-2 left-2 bg-rose-600 text-white font-bold px-2 py-0.5 rounded text-[10px] hidden">-15%</span>
            <img id="qvmProductImage" src="" alt="Sản phẩm" class="w-full h-full object-cover">
          </div>
          <div id="qvmThumbnailsContainer" class="flex gap-2 justify-center overflow-x-auto pb-1"></div>
        </div>

        <!-- Cột thông tin & chọn màu/size -->
        <div class="md:col-span-7 space-y-4">
          <div>
            <h3 class="font-serif-luxury text-xl font-bold text-neutral-900" id="qvmProductName">Tên sản phẩm</h3>
            <div class="flex items-center gap-2 mt-1 text-[11px] text-neutral-500">
              <span class="text-amber-500 font-bold flex items-center gap-0.5">
                <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                <span id="qvmRatingText">5.0</span>
              </span>
              <span>•</span>
              <span>Đã bán: <strong class="text-neutral-800" id="qvmSoldCount">0</strong></span>
            </div>
          </div>

          <!-- Bảng giá -->
          <div class="p-3 bg-brand-50 rounded-xl border border-brand-200 flex items-baseline gap-3">
            <span class="font-serif-luxury text-2xl font-bold text-neutral-950" id="qvmProductPrice">0₫</span>
            <span class="text-neutral-400 line-through text-xs hidden" id="qvmProductOriginalPrice">0₫</span>
            <span class="ml-auto text-[10px] font-bold text-rose-700 bg-rose-100 px-2 py-0.5 rounded hidden" id="qvmSavingsBadge">Tiết kiệm 0₫</span>
          </div>

          <!-- 1. Chọn màu sắc -->
          <div>
            <div class="flex justify-between items-center mb-1.5">
              <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[10px]">1. Chọn Màu Sắc:</span>
              <span id="qvmSelectedColorText" class="font-bold text-rose-600">Chưa chọn</span>
            </div>
            <div id="qvmColorsContainer" class="flex flex-wrap gap-2"></div>
          </div>

          <!-- 2. Chọn size -->
          <div>
            <div class="flex justify-between items-center mb-1.5">
              <span class="font-semibold uppercase tracking-wider text-neutral-800 text-[10px]">2. Chọn Kích Thước (Size Nam):</span>
              <span id="qvmSelectedSizeText" class="font-bold text-rose-600">Chưa chọn</span>
            </div>
            <div id="qvmSizesContainer" class="flex flex-wrap gap-2"></div>
          </div>

          <!-- 3. Số lượng & Tồn kho -->
          <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 space-y-2">
            <div class="flex justify-between items-center">
              <span class="font-semibold text-neutral-800">Số Lượng Mua:</span>
              <span id="qvmStockBadge" class="text-emerald-700 font-semibold text-[11px]">Còn <strong id="qvmStockNumber">...</strong> trong kho</span>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center border border-neutral-300 rounded-lg bg-white overflow-hidden text-xs">
                <button type="button" onclick="changeQvmQuantity(-1)" class="px-3 py-1.5 text-neutral-700 hover:bg-neutral-100">-</button>
                <input type="number" id="qvmQuantityInput" value="1" min="1" max="99" class="w-10 text-center font-bold text-neutral-900 focus:outline-none" readonly>
                <button type="button" onclick="changeQvmQuantity(1)" class="px-3 py-1.5 text-neutral-700 hover:bg-neutral-100">+</button>
              </div>
              <div class="text-[11px] text-neutral-500">
                Tạm tính: <strong class="text-neutral-950 font-bold" id="qvmSubtotalLive">0₫</strong>
              </div>
            </div>

            <!-- Cảnh báo cọc 50% khi mua từ 10 món -->
            <div id="qvmBulkDepositBox" class="p-2.5 bg-amber-50 border border-amber-300 rounded-lg text-amber-950 text-[11px] hidden">
              <strong>Đặt cọc 50% (Đơn từ 10 cái):</strong> Cọc trước: <strong class="text-rose-600 font-mono" id="qvmDepositAmountLive">0₫</strong>. Còn lại COD: <strong class="font-mono" id="qvmRemainingAmountLive">0₫</strong>.
            </div>
          </div>

          <!-- Nút hành động -->
          <div class="flex gap-2 pt-1">
            <button type="button" id="qvmAddToCartBtn" onclick="submitQvmAction(false)" class="flex-1 py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-all shadow text-xs flex items-center justify-center gap-1.5">
              <i data-lucide="shopping-bag" class="w-4 h-4"></i> Thêm Vào Giỏ
            </button>
            <button type="button" id="qvmBuyNowBtn" onclick="submitQvmAction(true)" class="flex-1 py-3 bg-amber-400 hover:bg-amber-500 text-neutral-950 font-bold uppercase tracking-wider rounded-xl transition-all shadow text-xs flex items-center justify-center gap-1.5">
              <i data-lucide="zap" class="w-4 h-4"></i> Mua Ngay
            </button>
          </div>

          <div class="text-center pt-1">
            <a href="#" id="qvmFullDetailLink" class="text-neutral-500 hover:text-neutral-900 text-[11px] font-semibold underline">
              Xem chi tiết toàn bộ sản phẩm &rarr;
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL 2: THÔNG BÁO THÊM GIỎ HÀNG THÀNH CÔNG (TAILWIND) -->
  <!-- ========================================================================= -->
  <div id="cartSuccessModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white max-w-sm w-full rounded-2xl p-6 shadow-2xl border border-neutral-200 text-center animate-fade-in text-xs space-y-4">
      <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto shadow-xs">
        <i data-lucide="check" class="w-7 h-7"></i>
      </div>
      <div>
        <h4 class="font-serif-luxury text-xl font-bold text-neutral-900">Đã Thêm Vào Giỏ Hàng!</h4>
        <p class="text-neutral-500 text-[11px] mt-0.5">Sản phẩm đã được chọn vào túi mua hàng thành công.</p>
      </div>

      <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 flex items-center gap-3 text-left">
        <img id="csmProductImage" src="" alt="Sản phẩm" class="w-12 h-14 object-cover rounded-lg border border-neutral-200 bg-white shrink-0">
        <div class="min-w-0 flex-grow">
          <h5 id="csmProductName" class="font-semibold text-neutral-900 truncate">Tên sản phẩm</h5>
          <span id="csmVariantText" class="text-neutral-500 text-[10px] block">Đen / Size L</span>
          <div class="flex justify-between items-baseline mt-1">
            <span class="text-neutral-400 text-[10px]">SL: x<strong id="csmQuantityText">1</strong></span>
            <strong id="csmPriceText" class="font-bold text-neutral-950 font-serif-luxury text-sm">0₫</strong>
          </div>
        </div>
      </div>

      <div class="flex flex-col gap-2">
        <a href="{{ route('client.cart') }}" class="w-full py-2.5 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-all shadow flex items-center justify-center gap-2">
          <i data-lucide="shopping-bag" class="w-4 h-4"></i> Xem Giỏ Hàng &amp; Thanh Toán
        </a>
        <button type="button" onclick="closeCartSuccessModal()" class="w-full py-2 border border-neutral-300 text-neutral-700 hover:bg-neutral-50 font-semibold rounded-xl transition-colors">
          Tiếp Tục Chọn Mua Thêm
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- MODAL 3: YÊU CẦU ĐĂNG NHẬP (TAILWIND) -->
  <!-- ========================================================================= -->
  <div id="authRequiredModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
    <div class="bg-white max-w-sm w-full rounded-2xl p-6 shadow-2xl border border-neutral-200 text-center animate-fade-in text-xs">
      <div class="w-14 h-14 rounded-full bg-amber-100 text-neutral-900 flex items-center justify-center mx-auto mb-3 shadow-xs">
        <i data-lucide="lock" class="w-6 h-6 text-amber-800"></i>
      </div>
      <h4 class="font-serif-luxury text-lg font-bold text-neutral-900 mb-1">Yêu Cầu Đăng Nhập</h4>
      <p class="text-neutral-500 mb-4 leading-relaxed">
        Để thực hiện <span id="authRequiredActionText" class="font-semibold text-neutral-800">thao tác này</span>, quý khách vui lòng đăng nhập vào tài khoản BeeStyle.
      </p>
      <div class="flex flex-col gap-2">
        <a href="{{ route('auth.login') }}" class="w-full py-2.5 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold rounded-lg uppercase tracking-wider transition-colors shadow">
          Đăng Nhập Ngay
        </a>
        <a href="{{ route('auth.register') }}" class="w-full py-2.5 border border-neutral-300 text-neutral-800 hover:bg-neutral-50 font-semibold rounded-lg transition-colors">
          Tạo Tài Khoản Mới
        </a>
        <button type="button" onclick="closeAuthModal()" class="text-neutral-400 hover:text-neutral-700 text-[11px] pt-1">
          Để sau, tiếp tục xem sản phẩm
        </button>
      </div>
    </div>
  </div>

  <!-- ========================================================================= -->
  <!-- FLOATING DELIVERY / REVIEW NOTIFICATION ALERT (GÓC MÀN HÌNH) -->
  <!-- ========================================================================= -->
  @php
    $activeDeliveringOrder = isset($deliveringOrders) && $deliveringOrders->isNotEmpty() ? $deliveringOrders->first() : null;
    $activePendingReview = isset($pendingReviewItems) && $pendingReviewItems->isNotEmpty() ? $pendingReviewItems->first() : null;
  @endphp

  @if($activeDeliveringOrder)
    <!-- THÔNG BÁO NỔI: BƯU TÁ ĐÃ GIAO HÀNG THÀNH CÔNG (CẦN XÁC NHẬN HOẶC HOÀN TRẢ) -->
    <div id="floatingDeliveryAlert" data-order-code="{{ $activeDeliveringOrder->order_code }}" class="fixed bottom-5 left-4 sm:left-6 z-40 max-w-sm sm:max-w-md w-[calc(100%-2rem)] bg-white rounded-2xl shadow-2xl border-2 border-emerald-500 p-4 animate-fade-in transition-all duration-300">
      <div class="flex items-start justify-between gap-3 mb-2.5">
        <div class="flex items-center gap-2">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
          </span>
          <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-wider bg-emerald-100 px-2 py-0.5 rounded-md">Bưu tá đã phát kiện hàng</span>
        </div>
        <button type="button" onclick="closeFloatingDeliveryAlert('{{ $activeDeliveringOrder->order_code }}')" class="text-neutral-400 hover:text-neutral-900 p-1" title="Đóng thông báo">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      @php
        $flFirstItem = $activeDeliveringOrder->items->first();
        $flItemThumb = asset($flFirstItem->image ?? ($flFirstItem->product->thumbnail ?? 'assets/img/products/1.png'));
        $flItemName = $flFirstItem->product_name ?? 'Sản phẩm BeeStyle';
        $flProductId = $flFirstItem->product_id ?? 1;
      @endphp
      <div class="flex items-center gap-3 mb-3 bg-neutral-50 p-2.5 rounded-xl border border-neutral-200">
        <img src="{{ $flItemThumb }}" alt="{{ $flItemName }}" class="w-12 h-14 object-cover rounded-lg border border-neutral-200 shrink-0 bg-white">
        <div class="min-w-0 flex-grow">
          <h4 class="text-xs font-bold text-neutral-900 truncate">Kiện hàng #{{ $activeDeliveringOrder->order_code }}</h4>
          <p class="text-[11px] text-neutral-600 line-clamp-2 mt-0.5">Bưu tá đã giao bưu phẩm đến bạn. Vui lòng kiểm tra và xác nhận nhận hàng hoặc đổi trả nếu có lỗi.</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" id="flConfirmDeliveredBtn" onclick="confirmDeliveredAjax('{{ $activeDeliveringOrder->order_code }}', {{ $flProductId }}, '{{ addslashes($flItemName) }}', '{{ addslashes($flItemThumb) }}')" class="flex-1 py-2 px-3 bg-neutral-950 hover:bg-neutral-800 text-white rounded-xl text-xs font-bold shadow-xs transition-colors flex items-center justify-center gap-1.5">
          <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400"></i>
          <span>Đã Nhận Hàng</span>
        </button>
        <a href="{{ route('client.order-tracking', ['code' => $activeDeliveringOrder->order_code]) }}#carrierTrackingPassSection" class="py-2 px-3 bg-white hover:bg-rose-50 text-rose-700 border border-rose-300 rounded-xl text-xs font-bold transition-colors text-center whitespace-nowrap">
          Đổi Trả / Hoàn Tiền
        </a>
      </div>
    </div>
  @elseif($activePendingReview)
    <!-- THÔNG BÁO NỔI: NHẮC NHỞ ĐÁNH GIÁ SẢN PHẨM NHẬN VOUCHER -->
    @php
      $flRevThumb = asset($activePendingReview->image ?? ($activePendingReview->product->thumbnail ?? 'assets/img/products/1.png'));
    @endphp
    <div id="floatingReviewAlert" data-review-id="rev_{{ $activePendingReview->id }}" class="fixed bottom-5 left-4 sm:left-6 z-40 max-w-sm sm:max-w-md w-[calc(100%-2rem)] bg-white rounded-2xl shadow-2xl border-2 border-amber-400 p-4 animate-fade-in transition-all duration-300">
      <div class="flex items-start justify-between gap-3 mb-2">
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
          <span class="text-[10px] font-extrabold text-amber-900 uppercase tracking-wider bg-amber-100 px-2 py-0.5 rounded-md">Tặng Voucher Ưu Đãi 10%</span>
        </div>
        <button type="button" onclick="closeFloatingReviewAlert('rev_{{ $activePendingReview->id }}')" class="text-neutral-400 hover:text-neutral-900 p-1" title="Đóng thông báo">
          <i data-lucide="x" class="w-4 h-4"></i>
        </button>
      </div>

      <div class="flex items-center gap-3 mb-3 bg-amber-50/50 p-2.5 rounded-xl border border-amber-200">
        <img src="{{ $flRevThumb }}" alt="{{ $activePendingReview->product_name }}" class="w-12 h-14 object-cover rounded-lg border border-neutral-200 shrink-0 bg-white">
        <div class="min-w-0 flex-grow">
          <h4 class="text-xs font-bold text-neutral-900 truncate">Chia sẻ cảm nhận của bạn</h4>
          <p class="text-[11px] text-neutral-600 line-clamp-2 mt-0.5">Sản phẩm "{{ $activePendingReview->product_name }}" dùng tốt chứ? Đánh giá ngay để nhận Voucher nhé!</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <button type="button" onclick="openGlobalReviewModal({{ $activePendingReview->product_id }}, '{{ addslashes($activePendingReview->product_name) }}', '{{ addslashes($flRevThumb) }}', '{{ $activePendingReview->order->order_code ?? '' }}')" class="flex-1 py-2 px-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow-xs transition-colors flex items-center justify-center gap-1.5">
          <i data-lucide="star" class="w-4 h-4 fill-white"></i>
          <span>Đánh Giá Ngay (Tặng Voucher)</span>
        </button>
        <button type="button" onclick="closeFloatingReviewAlert('rev_{{ $activePendingReview->id }}')" class="py-2 px-3 text-neutral-500 hover:text-neutral-900 text-xs font-medium">
          Để sau
        </button>
      </div>
    </div>
  @endif

  <!-- ========================================================================= -->
  <!-- MODAL 4: ĐÁNH GIÁ SẢN PHẨM TOÀN CỤC & TẶNG VOUCHER (TAILWIND) -->
  <!-- ========================================================================= -->
  <div id="globalQuickReviewModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white max-w-lg w-full rounded-2xl shadow-2xl border border-neutral-200 my-8 overflow-hidden animate-fade-in flex flex-col max-h-[90vh]">
      <div class="flex items-center justify-between p-5 border-b border-neutral-200 bg-neutral-50 shrink-0">
        <div class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
            <i data-lucide="star" class="w-5 h-5 fill-amber-500 text-amber-500"></i>
          </div>
          <div>
            <h3 class="font-serif-luxury text-base font-bold text-neutral-900">Đánh Giá Sản Phẩm</h3>
            <span class="text-[11px] text-amber-800 font-semibold block">Tặng ngay Voucher ưu đãi vào ví thành viên</span>
          </div>
        </div>
        <button type="button" onclick="closeGlobalReviewModal()" class="text-neutral-400 hover:text-neutral-900 p-1">
          <i data-lucide="x" class="w-5 h-5"></i>
        </button>
      </div>

      <form id="globalQuickReviewForm" method="POST" enctype="multipart/form-data" class="p-6 text-xs text-neutral-800 space-y-4 overflow-y-auto">
        @csrf
        
        <!-- Thông tin sản phẩm đang đánh giá -->
        <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 flex items-center gap-3">
          <img id="grmProductImage" src="" alt="Sản phẩm" class="w-12 h-14 object-cover rounded-lg border border-neutral-200 bg-white shrink-0">
          <div class="min-w-0 flex-grow">
            <h4 id="grmProductName" class="font-bold text-neutral-900 truncate text-xs">Tên sản phẩm</h4>
            <span id="grmOrderCodeBadge" class="text-[10px] text-neutral-500 block mt-0.5 font-mono">Đơn hàng: #---</span>
          </div>
        </div>

        <!-- Chọn số sao (Interactive Rating) -->
        <div>
          <label class="block font-bold text-neutral-800 mb-2">Đánh giá chung của bạn *</label>
          <div class="flex items-center gap-2">
            <div class="flex items-center gap-1" id="grmStarsContainer">
              @for($s = 1; $s <= 5; $s++)
                <button type="button" onclick="setGrmRating({{ $s }})" onmouseenter="previewGrmRating({{ $s }})" onmouseleave="resetGrmRatingPreview()" class="text-amber-400 hover:scale-110 transition-transform p-0.5 grm-star-btn" data-star="{{ $s }}">
                  <i data-lucide="star" class="w-6 h-6 fill-amber-400 text-amber-400"></i>
                </button>
              @endfor
            </div>
            <span id="grmRatingText" class="font-bold text-neutral-900 text-xs ml-2">Tuyệt vời (5 sao)</span>
          </div>
          <input type="hidden" name="rating" id="grmRatingInput" value="5">
        </div>

        <!-- Nội dung nhận xét -->
        <div>
          <label for="grmCommentInput" class="block font-bold text-neutral-800 mb-1.5">Nhận xét chi tiết *</label>
          <textarea name="comment" id="grmCommentInput" rows="3" required minlength="4" maxlength="1000" placeholder="Chất liệu vải thế nào? Form áo/quần mặc lên có chuẩn không? Bưu tá giao hàng có nhanh nhẹn không?..." class="w-full px-3.5 py-2.5 rounded-xl border border-neutral-300 focus:border-neutral-950 focus:ring-1 focus:ring-neutral-950 text-xs text-neutral-900 placeholder:text-neutral-400"></textarea>
        </div>

        <!-- Đính kèm ảnh thực tế -->
        <div>
          <label class="block font-bold text-neutral-800 mb-1.5">Ảnh thực tế sản phẩm (Tùy chọn, tối đa 5 ảnh)</label>
          <input type="file" name="review_images[]" id="grmImagesInput" multiple accept="image/*" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-neutral-900 file:text-white hover:file:bg-black cursor-pointer">
          <div id="grmImagesPreview" class="flex flex-wrap gap-2 mt-2"></div>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
          <button type="submit" id="grmSubmitBtn" class="w-full py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-bold uppercase tracking-wider rounded-xl transition-all shadow text-xs flex items-center justify-center gap-2">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Gửi Đánh Giá &amp; Nhận Voucher</span>
          </button>
          <p class="text-[10px] text-neutral-500 text-center mt-2 flex items-center justify-center gap-1">
            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
            Đánh giá của bạn sẽ được hiển thị công khai trên trang sản phẩm
          </p>
        </div>
      </form>
    </div>
  </div>

  <!-- Toast Notification Container -->
  <div id="beeToastContainer" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

  <!-- Core Scripts -->
  <script>
    const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};

    function toggleMobileNav() {
      document.getElementById('mobile-nav')?.classList.toggle('hidden');
    }

    function copyCouponTopBar(code) {
      navigator.clipboard.writeText(code).then(() => {
        showGlobalToast(`Đã sao chép mã ưu đãi: ${code}`, 'success');
      });
    }

    function requireAuthPrompt(actionName = 'thực hiện thao tác này') {
      const actEl = document.getElementById('authRequiredActionText');
      if (actEl) actEl.textContent = actionName;
      document.getElementById('authRequiredModal')?.classList.remove('hidden');
    }

    function closeAuthModal() {
      document.getElementById('authRequiredModal')?.classList.add('hidden');
    }

    function closeCartSuccessModal() {
      document.getElementById('cartSuccessModal')?.classList.add('hidden');
    }

    function closeQuickVariantModal() {
      document.getElementById('quickVariantModal')?.classList.add('hidden');
    }

    // Toggle Wishlist toàn trang
    function toggleWishlist(productId, btnEl) {
      if (!IS_AUTHENTICATED) {
        requireAuthPrompt('lưu sản phẩm vào danh sách yêu thích');
        return;
      }

      fetch('{{ route("client.wishlist.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ product_id: productId })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success || data.is_favorite !== undefined) {
          const isFav = data.is_favorite;
          
          document.querySelectorAll(`.btn-wishlist-${productId}`).forEach(btn => {
            const icon = btn.querySelector('svg, i');
            if (isFav) {
              btn.classList.add('text-rose-600');
              if (icon) icon.classList.add('fill-rose-500', 'text-rose-500');
            } else {
              btn.classList.remove('text-rose-600');
              if (icon) icon.classList.remove('fill-rose-500', 'text-rose-500');
            }
          });

          const badge = document.getElementById('wishlistCountBadge');
          if (badge && data.count !== undefined) {
            badge.textContent = data.count;
            badge.classList.toggle('hidden', data.count <= 0);
          }

          showGlobalToast(data.message || (isFav ? 'Đã thêm vào yêu thích' : 'Đã gỡ khỏi yêu thích'), isFav ? 'heart' : 'info');
        }
      })
      .catch(err => console.error('Wishlist error:', err));
    }

    // Hiển thị Toast thông báo Tailwind
    function showGlobalToast(message, type = 'info') {
      const container = document.getElementById('beeToastContainer');
      if (!container) return;

      const toast = document.createElement('div');
      toast.className = 'pointer-events-auto bg-neutral-900 text-white px-4 py-2.5 rounded-xl shadow-xl border border-neutral-700 text-xs flex items-center gap-2.5 animate-fade-in';
      
      let iconHtml = '<i data-lucide="info" class="w-4 h-4 text-amber-400 shrink-0"></i>';
      if (type === 'heart') {
        iconHtml = '<i data-lucide="heart" class="w-4 h-4 text-rose-500 fill-rose-500 shrink-0"></i>';
      } else if (type === 'success') {
        iconHtml = '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 shrink-0"></i>';
      }

      toast.innerHTML = `
        ${iconHtml}
        <span class="font-medium">${message}</span>
      `;

      container.appendChild(toast);
      if (window.lucide) lucide.createIcons();

      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, 3200);
    }

    // =========================================================================
    // GLOBAL CART BADGE & STATE CONTROLLER
    // =========================================================================
    function updateGlobalCartState(cartCount, subtotalFormatted = null) {
      const count = parseInt(cartCount) || 0;

      // Cập nhật toàn bộ các badge hiển thị số lượng giỏ hàng trên trang
      document.querySelectorAll('.bee-cart-count, #cartCountBadge').forEach(badge => {
        badge.textContent = count;
        if (count > 0) {
          badge.classList.remove('hidden');
          // Hiệu ứng nảy số (bounce / pulse animation)
          badge.classList.add('scale-125', 'bg-rose-600');
          setTimeout(() => {
            badge.classList.remove('scale-125', 'bg-rose-600');
          }, 600);
        } else {
          badge.classList.add('hidden');
        }
      });

      // Hiệu ứng rung icon giỏ hàng trên thanh header
      const cartLink = document.querySelector('a[title="Túi mua hàng"]');
      if (cartLink) {
        const iconWrap = cartLink.querySelector('div');
        if (iconWrap) {
          iconWrap.classList.add('animate-bounce');
          setTimeout(() => iconWrap.classList.remove('animate-bounce'), 800);
        }
      }

      // Cập nhật tổng tiền tạm tính nếu có
      if (subtotalFormatted) {
        const subtotalEl = document.getElementById('headerCartSubtotal');
        if (subtotalEl) {
          subtotalEl.textContent = subtotalFormatted;
        }
      }
    }

    // =========================================================================
    // QUICK VIEW & VARIANT SELECTION MODAL CONTROLLER
    // =========================================================================
    let currentQvmProduct = null;
    let selectedColor = null;
    let selectedSize = null;
    let selectedVariantId = null;
    let isBuyNowMode = false;

    function openQuickVariantModal(productId, isBuyNow = false, btnEl = null) {
      isBuyNowMode = isBuyNow;
      selectedColor = null;
      selectedSize = null;
      selectedVariantId = null;

      // Cập nhật nhãn trạng thái chế độ mở
      const modeBadge = document.getElementById('qvmModeBadge');
      if (modeBadge) {
        if (isBuyNow) {
          modeBadge.textContent = '⚡ MUA NGAY SIÊU TỐC';
          modeBadge.className = 'px-2.5 py-0.5 bg-amber-400 text-neutral-950 font-black rounded uppercase text-[10px] shadow-xs';
        } else {
          modeBadge.textContent = 'CHI TIẾT NHANH';
          modeBadge.className = 'px-2 py-0.5 bg-amber-100 text-amber-900 rounded font-bold uppercase text-[10px]';
        }
      }

      // Đánh dấu nút tương ứng trong modal
      const buyBtn = document.getElementById('qvmBuyNowBtn');
      const addBtn = document.getElementById('qvmAddToCartBtn');
      if (buyBtn && addBtn) {
        if (isBuyNow) {
          buyBtn.classList.add('ring-4', 'ring-amber-300');
          addBtn.classList.remove('ring-4', 'ring-neutral-900');
        } else {
          addBtn.classList.add('ring-4', 'ring-neutral-900');
          buyBtn.classList.remove('ring-4', 'ring-amber-300');
        }
      }

      const initCol = document.getElementById('qvmSelectedColorText');
      if (initCol) {
        initCol.textContent = 'Chưa chọn';
        initCol.className = 'font-bold text-rose-600';
      }
      const initSz = document.getElementById('qvmSelectedSizeText');
      if (initSz) {
        initSz.textContent = 'Chưa chọn';
        initSz.className = 'font-bold text-rose-600';
      }
      document.getElementById('qvmQuantityInput').value = 1;

      const modalEl = document.getElementById('quickVariantModal');
      if (modalEl) modalEl.classList.remove('hidden');

      const apiUrl = "{{ url('/san-pham/api-quick-view') }}/" + productId;
      fetch(apiUrl, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(data => {
        if (data && data.success) {
          currentQvmProduct = data;
          renderQvmProductData(data);
        } else {
          showGlobalToast(data.message || 'Không thể tải thông tin sản phẩm', 'info');
          closeQuickVariantModal();
        }
      })
      .catch(err => {
        console.warn('Quick view error:', err);
        showGlobalToast('Không thể kết nối đến máy chủ', 'info');
        closeQuickVariantModal();
      });
    }

    function renderQvmProductData(data) {
      document.getElementById('qvmCategoryBadge').textContent = data.category_name || 'Thời trang nam';
      document.getElementById('qvmSkuText').textContent = `SKU: ${data.sku || ('BS-' + data.id)}`;
      document.getElementById('qvmProductName').textContent = data.name || 'Sản phẩm';
      document.getElementById('qvmFullDetailLink').href = data.product_url || ('{{ url("/san-pham") }}/' + data.id);
      document.getElementById('qvmRatingText').textContent = (data.rating || 5.0).toFixed(1);
      document.getElementById('qvmSoldCount').textContent = (data.sold_count || 0).toLocaleString('vi-VN');
      document.getElementById('qvmProductPrice').textContent = data.price_formatted || '0₫';
      document.getElementById('qvmProductImage').src = data.image || '';

      const discBadge = document.getElementById('qvmDiscountBadge');
      if (discBadge) {
        if (data.discount_percent > 0) {
          discBadge.textContent = `-${data.discount_percent}%`;
          discBadge.classList.remove('hidden');
        } else {
          discBadge.classList.add('hidden');
        }
      }

      const origPrice = document.getElementById('qvmProductOriginalPrice');
      if (origPrice) {
        if (data.original_price_formatted && data.original_price > data.price) {
          origPrice.textContent = data.original_price_formatted;
          origPrice.classList.remove('hidden');
        } else {
          origPrice.classList.add('hidden');
        }
      }

      const saveBadge = document.getElementById('qvmSavingsBadge');
      if (saveBadge) {
        if (data.original_price && data.original_price > data.price) {
          const savings = data.original_price - data.price;
          saveBadge.textContent = `Tiết kiệm ${savings.toLocaleString('vi-VN')}₫`;
          saveBadge.classList.remove('hidden');
        } else {
          saveBadge.classList.add('hidden');
        }
      }

      const stockNum = document.getElementById('qvmStockNumber');
      if (stockNum) stockNum.textContent = data.stock || 0;

      // Danh sách màu sắc (KHÔNG chọn sẵn, để khách hàng tự chọn)
      const colors = (data.colors && data.colors.length > 0) ? data.colors : ['Tiêu chuẩn'];
      const colorsHtml = colors.map(col => `
        <button type="button" onclick="selectQvmColor('${col}', this)" class="px-3.5 py-1.5 rounded-lg border border-neutral-300 text-neutral-800 hover:border-neutral-950 text-xs font-semibold transition-all qvm-col-btn">
          ${col}
        </button>
      `).join('');
      document.getElementById('qvmColorsContainer').innerHTML = colorsHtml;

      // Danh sách kích thước (Size) (KHÔNG chọn sẵn, để khách hàng tự chọn)
      const sizes = (data.sizes && data.sizes.length > 0) ? data.sizes : ['Freesize'];
      const sizesHtml = sizes.map(sz => `
        <button type="button" onclick="selectQvmSize('${sz}', this)" class="w-12 h-10 rounded-lg border border-neutral-300 text-neutral-800 hover:border-neutral-950 font-bold uppercase text-xs transition-all qvm-sz-btn">
          ${sz}
        </button>
      `).join('');
      document.getElementById('qvmSizesContainer').innerHTML = sizesHtml;

      // Mặc định ở trạng thái Chưa chọn để khách hàng chủ động chọn phân loại
      selectedColor = null;
      const colText = document.getElementById('qvmSelectedColorText');
      if (colText) {
        colText.textContent = 'Chưa chọn';
        colText.className = 'font-bold text-rose-600';
      }

      selectedSize = null;
      const szText = document.getElementById('qvmSelectedSizeText');
      if (szText) {
        szText.textContent = 'Chưa chọn';
        szText.className = 'font-bold text-rose-600';
      }

      selectedVariantId = null;
      updateQvmQtyDisplay(1);
    }

    function selectQvmColor(col, btn) {
      selectedColor = col;
      const colText = document.getElementById('qvmSelectedColorText');
      if (colText) {
        colText.textContent = col;
        colText.className = 'font-bold text-neutral-950';
      }
      document.querySelectorAll('.qvm-col-btn').forEach(b => {
        b.classList.remove('bg-neutral-950', 'text-white', 'border-neutral-950', 'shadow-xs');
        b.classList.add('border-neutral-300', 'text-neutral-800');
      });
      if (btn) {
        btn.classList.add('bg-neutral-950', 'text-white', 'border-neutral-950', 'shadow-xs');
        btn.classList.remove('border-neutral-300', 'text-neutral-800');
      }
      syncQvmVariantMatch();
    }

    function selectQvmSize(sz, btn) {
      selectedSize = sz;
      const szText = document.getElementById('qvmSelectedSizeText');
      if (szText) {
        szText.textContent = sz;
        szText.className = 'font-bold text-neutral-950';
      }
      document.querySelectorAll('.qvm-sz-btn').forEach(b => {
        b.classList.remove('bg-neutral-950', 'text-white', 'border-neutral-950', 'shadow-xs');
        b.classList.add('border-neutral-300', 'text-neutral-800');
      });
      if (btn) {
        btn.classList.add('bg-neutral-950', 'text-white', 'border-neutral-950', 'shadow-xs');
        btn.classList.remove('border-neutral-300', 'text-neutral-800');
      }
      syncQvmVariantMatch();
    }

    function syncQvmVariantMatch() {
      if (!currentQvmProduct || !currentQvmProduct.variants) return;
      const vars = currentQvmProduct.variants;
      const matched = vars.find(v => 
        (!selectedColor || v.color.toLowerCase() === selectedColor.toLowerCase()) &&
        (!selectedSize || v.size.toLowerCase() === selectedSize.toLowerCase())
      );

      if (matched) {
        selectedVariantId = matched.id;
        if (matched.price_formatted) {
          document.getElementById('qvmProductPrice').textContent = matched.price_formatted;
        }
        const stockNum = document.getElementById('qvmStockNumber');
        if (stockNum) stockNum.textContent = matched.stock;
      }
    }

    function changeQvmQuantity(delta) {
      const input = document.getElementById('qvmQuantityInput');
      let val = (parseInt(input.value) || 1) + delta;
      updateQvmQtyDisplay(val);
    }

    function updateQvmQtyDisplay(val) {
      const maxStock = currentQvmProduct ? (currentQvmProduct.stock || 99) : 99;
      if (val < 1) val = 1;
      if (val > maxStock) val = maxStock;
      document.getElementById('qvmQuantityInput').value = val;

      const unitPrice = currentQvmProduct ? currentQvmProduct.price : 0;
      const subtotal = unitPrice * val;
      document.getElementById('qvmSubtotalLive').textContent = subtotal.toLocaleString('vi-VN') + '₫';

      const bulkBox = document.getElementById('qvmBulkDepositBox');
      if (val >= 10) {
        bulkBox?.classList.remove('hidden');
        document.getElementById('qvmDepositAmountLive').textContent = Math.round(subtotal * 0.5).toLocaleString('vi-VN') + '₫';
        document.getElementById('qvmRemainingAmountLive').textContent = (subtotal - Math.round(subtotal * 0.5)).toLocaleString('vi-VN') + '₫';
      } else {
        bulkBox?.classList.add('hidden');
      }
    }

    function submitQvmAction(isBuyNow) {
      if (!currentQvmProduct) return;
      if (!selectedColor) {
        showGlobalToast('Quý khách vui lòng chọn Màu sắc sản phẩm!', 'info');
        const colSec = document.getElementById('qvmColorsContainer');
        if (colSec) {
          colSec.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          colSec.classList.add('ring-2', 'ring-rose-500', 'p-1', 'rounded-lg');
          setTimeout(() => colSec.classList.remove('ring-2', 'ring-rose-500', 'p-1', 'rounded-lg'), 1500);
        }
        return;
      }
      if (!selectedSize) {
        showGlobalToast('Quý khách vui lòng chọn Kích thước (Size) sản phẩm!', 'info');
        const szSec = document.getElementById('qvmSizesContainer');
        if (szSec) {
          szSec.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          szSec.classList.add('ring-2', 'ring-rose-500', 'p-1', 'rounded-lg');
          setTimeout(() => szSec.classList.remove('ring-2', 'ring-rose-500', 'p-1', 'rounded-lg'), 1500);
        }
        return;
      }

      const activeBtn = isBuyNow ? document.getElementById('qvmBuyNowBtn') : document.getElementById('qvmAddToCartBtn');
      const origHtml = activeBtn ? activeBtn.innerHTML : '';
      if (activeBtn) {
        activeBtn.disabled = true;
        activeBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> <span>Đang xử lý...</span>';
        if (window.lucide) lucide.createIcons();
      }

      const qty = parseInt(document.getElementById('qvmQuantityInput').value) || 1;
      const payload = {
        product_id: currentQvmProduct.id,
        variant_id: selectedVariantId,
        color: selectedColor,
        size: selectedSize,
        quantity: qty,
        buy_now: isBuyNow ? 1 : 0
      };

      fetch('{{ route("client.cart.add") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(payload)
      })
      .then(r => r.json())
      .then(data => {
        if (activeBtn) {
          activeBtn.disabled = false;
          activeBtn.innerHTML = origHtml;
          if (window.lucide) lucide.createIcons();
        }

        if (data.success) {
          closeQuickVariantModal();
          if (isBuyNow) {
            window.location.href = '{{ route("client.checkout") }}';
          } else {
            // Cập nhật trạng thái giỏ hàng toàn trang
            updateGlobalCartState(data.cart_count, data.cart?.total_formatted);

            // Điền thông tin vào Modal Thông Báo Thêm Thành Công
            document.getElementById('csmProductImage').src = currentQvmProduct.image || '';
            document.getElementById('csmProductName').textContent = currentQvmProduct.name;
            document.getElementById('csmVariantText').textContent = `${selectedColor} / Size ${selectedSize}`;
            document.getElementById('csmQuantityText').textContent = qty;
            document.getElementById('csmPriceText').textContent = ((currentQvmProduct.price || 0) * qty).toLocaleString('vi-VN') + '₫';
            document.getElementById('cartSuccessModal')?.classList.remove('hidden');

            // Hiển thị Toast thông báo số lượng sản phẩm đã thêm
            showGlobalToast(`🛒 Đã thêm ${qty} sản phẩm vào giỏ hàng thành công! Giỏ hiện có ${data.cart_count} sản phẩm.`, 'success');
          }
        } else {
          showGlobalToast(data.message || 'Không thể thêm sản phẩm vào giỏ hàng', 'info');
        }
      })
      .catch(err => {
        if (activeBtn) {
          activeBtn.disabled = false;
          activeBtn.innerHTML = origHtml;
          if (window.lucide) lucide.createIcons();
        }
        console.error('Cart add error:', err);
        showGlobalToast('Có lỗi xảy ra khi kết nối máy chủ', 'info');
      });
    }

    // =========================================================================
    // THÔNG BÁO GIAO HÀNG & ĐÁNH GIÁ SẢN PHẨM TOÀN TRANG (TAILWIND)
    // =========================================================================
    function toggleNotificationDropdown(e) {
      if (e) e.stopPropagation();
      const menu = document.getElementById('notificationDropdownMenu');
      if (menu) {
        menu.classList.toggle('hidden');
        if (window.lucide) lucide.createIcons();
      }
    }

    document.addEventListener('click', function(e) {
      const notifWrapper = document.getElementById('headerNotificationWrapper');
      const notifMenu = document.getElementById('notificationDropdownMenu');
      if (notifWrapper && notifMenu && !notifWrapper.contains(e.target)) {
        notifMenu.classList.add('hidden');
      }
    });

    function closeFloatingDeliveryAlert(orderCode) {
      const el = document.getElementById('floatingDeliveryAlert');
      if (el) el.classList.add('hidden');
      if (orderCode) sessionStorage.setItem('dismissed_delivery_alert_' + orderCode, '1');
    }

    function closeFloatingReviewAlert(reviewId) {
      const el = document.getElementById('floatingReviewAlert');
      if (el) el.classList.add('hidden');
      if (reviewId) sessionStorage.setItem('dismissed_review_alert_' + reviewId, '1');
    }

    // Xác nhận đã nhận hàng qua AJAX (Dùng cho chuông thông báo & thông báo nổi)
    function confirmDeliveredAjax(orderCode, productId, productName, productImage) {
      if (!IS_AUTHENTICATED) {
        requireAuthPrompt('xác nhận đã nhận hàng');
        return;
      }

      const confirmUrl = '{{ url("/tra-cuu-don-hang") }}/' + orderCode + '/da-nhan-hang';
      const btn = document.getElementById('flConfirmDeliveredBtn');
      const origHtml = btn ? btn.innerHTML : '';
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Đang xác nhận...';
        if (window.lucide) lucide.createIcons();
      }

      fetch(confirmUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(res => res.json())
      .then(data => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = origHtml;
          if (window.lucide) lucide.createIcons();
        }

        if (data.success) {
          // Ẩn thông báo nổi
          const fl = document.getElementById('floatingDeliveryAlert');
          if (fl) fl.remove();

          // Ẩn thông báo trong chuông
          const notifEl = document.getElementById('notifItem_deliv_' + orderCode);
          if (notifEl) notifEl.remove();

          // Cập nhật số lượng thông báo chưa đọc
          const badge = document.getElementById('notificationBadge');
          const countBadge = document.getElementById('unreadNotifCountBadge');
          if (badge) {
            let cur = parseInt(badge.textContent) || 0;
            if (cur > 1) {
              badge.textContent = cur - 1;
            } else {
              badge.classList.add('hidden');
              if (countBadge) countBadge.classList.add('hidden');
            }
          }

          showGlobalToast(`🎉 Đã xác nhận nhận kiện hàng #${orderCode} thành công!`, 'success');

          // Mở ngay Modal đánh giá sản phẩm để khách hàng nhận voucher
          const targetProdId = data.product_id || productId || 1;
          setTimeout(() => {
            openGlobalReviewModal(targetProdId, productName, productImage, orderCode);
          }, 400);
        } else {
          showGlobalToast(data.message || 'Không thể xác nhận nhận hàng, vui lòng thử lại', 'info');
        }
      })
      .catch(err => {
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = origHtml;
          if (window.lucide) lucide.createIcons();
        }
        console.error('Confirm delivery error:', err);
        showGlobalToast('Có lỗi xảy ra khi gửi xác nhận nhận hàng', 'info');
      });
    }

    // =========================================================================
    // MODAL ĐÁNH GIÁ SẢN PHẨM TOÀN CỤC (GLOBAL QUICK REVIEW MODAL)
    // =========================================================================
    let currentGrmProductId = null;
    let currentGrmRating = 5;

    const ratingDescriptions = {
      1: 'Rất không hài lòng (1 sao)',
      2: 'Không hài lòng (2 sao)',
      3: 'Bình thường (3 sao)',
      4: 'Hài lòng (4 sao)',
      5: 'Tuyệt vời (5 sao)'
    };

    function openGlobalReviewModal(productId, productName, productImage, orderCode) {
      if (!IS_AUTHENTICATED) {
        requireAuthPrompt('gửi đánh giá sản phẩm');
        return;
      }

      currentGrmProductId = productId;
      const form = document.getElementById('globalQuickReviewForm');
      if (form) {
        form.action = '{{ url("/san-pham") }}/' + productId + '/danh-gia';
      }

      const imgEl = document.getElementById('grmProductImage');
      if (imgEl) imgEl.src = productImage || '{{ asset("assets/img/products/1.png") }}';

      const nameEl = document.getElementById('grmProductName');
      if (nameEl) nameEl.textContent = productName || 'Sản phẩm BeeStyle';

      const codeEl = document.getElementById('grmOrderCodeBadge');
      if (codeEl) codeEl.textContent = orderCode ? ('Đơn hàng: #' + orderCode) : 'Đã mua tại BeeStyle';

      // Reset form fields
      setGrmRating(5);
      const commentInput = document.getElementById('grmCommentInput');
      if (commentInput) commentInput.value = '';

      const imagesInput = document.getElementById('grmImagesInput');
      if (imagesInput) imagesInput.value = '';

      const previewContainer = document.getElementById('grmImagesPreview');
      if (previewContainer) previewContainer.innerHTML = '';

      // Hiển thị modal
      const modal = document.getElementById('globalQuickReviewModal');
      if (modal) {
        modal.classList.remove('hidden');
        if (window.lucide) lucide.createIcons();
      }
    }

    function closeGlobalReviewModal() {
      document.getElementById('globalQuickReviewModal')?.classList.add('hidden');
    }

    function setGrmRating(val) {
      currentGrmRating = val;
      const ratingInput = document.getElementById('grmRatingInput');
      if (ratingInput) ratingInput.value = val;

      const ratingText = document.getElementById('grmRatingText');
      if (ratingText) ratingText.textContent = ratingDescriptions[val] || `${val} sao`;

      updateGrmStarsUI(val);
    }

    function previewGrmRating(val) {
      updateGrmStarsUI(val);
      const ratingText = document.getElementById('grmRatingText');
      if (ratingText) ratingText.textContent = ratingDescriptions[val] || `${val} sao`;
    }

    function resetGrmRatingPreview() {
      updateGrmStarsUI(currentGrmRating);
      const ratingText = document.getElementById('grmRatingText');
      if (ratingText) ratingText.textContent = ratingDescriptions[currentGrmRating] || `${currentGrmRating} sao`;
    }

    function updateGrmStarsUI(val) {
      document.querySelectorAll('.grm-star-btn').forEach(btn => {
        const starNum = parseInt(btn.getAttribute('data-star'));
        const icon = btn.querySelector('svg, i');
        if (starNum <= val) {
          btn.className = 'text-amber-400 hover:scale-110 transition-transform p-0.5 grm-star-btn';
          if (icon) {
            icon.classList.add('fill-amber-400', 'text-amber-400');
            icon.classList.remove('text-neutral-300', 'fill-transparent');
          }
        } else {
          btn.className = 'text-neutral-300 hover:scale-110 transition-transform p-0.5 grm-star-btn';
          if (icon) {
            icon.classList.remove('fill-amber-400', 'text-amber-400');
            icon.classList.add('text-neutral-300', 'fill-transparent');
          }
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) {
        lucide.createIcons();
      }

      // Xem trước hình ảnh khi khách chọn ảnh đánh giá
      const imgInput = document.getElementById('grmImagesInput');
      const previewContainer = document.getElementById('grmImagesPreview');
      if (imgInput && previewContainer) {
        imgInput.addEventListener('change', function(e) {
          previewContainer.innerHTML = '';
          const files = Array.from(e.target.files).slice(0, 5);
          files.forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = function(evt) {
              const div = document.createElement('div');
              div.className = 'relative w-14 h-14 rounded-lg overflow-hidden border border-neutral-200 bg-neutral-100 shadow-2xs';
              div.innerHTML = `<img src="${evt.target.result}" class="w-full h-full object-cover">`;
              previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
          });
        });
      }

      // Submit Form Đánh Giá Toàn Cục bằng AJAX
      const reviewForm = document.getElementById('globalQuickReviewForm');
      if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const comment = document.getElementById('grmCommentInput')?.value?.trim();
          if (!comment || comment.length < 4) {
            showGlobalToast('Vui lòng nhập nhận xét chi tiết ít nhất 4 ký tự', 'info');
            return;
          }

          const submitBtn = document.getElementById('grmSubmitBtn');
          const origBtnHtml = submitBtn ? submitBtn.innerHTML : '';
          if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> <span>Đang gửi đánh giá...</span>';
            if (window.lucide) lucide.createIcons();
          }

          const formData = new FormData(reviewForm);

          fetch(reviewForm.action, {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = origBtnHtml;
              if (window.lucide) lucide.createIcons();
            }

            if (data.success) {
              closeGlobalReviewModal();
              showGlobalToast(data.message || 'Cảm ơn bạn đã gửi đánh giá! Voucher ưu đãi đã được lưu vào ví.', 'success');

              // Ẩn các nhắc nhở đánh giá liên quan
              const flReview = document.getElementById('floatingReviewAlert');
              if (flReview) flReview.remove();

              const notifItem = document.getElementById('notifItem_rev_' + currentGrmProductId);
              if (notifItem) notifItem.remove();
            } else {
              showGlobalToast(data.message || 'Không thể gửi đánh giá, vui lòng kiểm tra lại', 'info');
            }
          })
          .catch(err => {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = origBtnHtml;
              if (window.lucide) lucide.createIcons();
            }
            console.error('Submit review error:', err);
            showGlobalToast('Có lỗi xảy ra khi gửi đánh giá', 'info');
          });
        });
      }

      // Kiểm tra ẩn floating delivery alert nếu khách đã bấm đóng trước đó trong phiên
      const flAlert = document.getElementById('floatingDeliveryAlert');
      if (flAlert) {
        const orderCode = flAlert.getAttribute('data-order-code');
        if (orderCode && sessionStorage.getItem('dismissed_delivery_alert_' + orderCode) === '1') {
          flAlert.classList.add('hidden');
        }
      }

      // Kiểm tra ẩn floating review alert nếu khách đã bấm đóng trước đó trong phiên
      const flRev = document.getElementById('floatingReviewAlert');
      if (flRev) {
        const revId = flRev.getAttribute('data-review-id');
        if (revId && sessionStorage.getItem('dismissed_review_alert_' + revId) === '1') {
          flRev.classList.add('hidden');
        }
      }

      // Tự động mở modal đánh giá nếu có session 'open_review_modal_product_id' từ backend
      @if(session('open_review_modal_product_id'))
        setTimeout(() => {
          openGlobalReviewModal({{ session('open_review_modal_product_id') }}, 'Sản phẩm của bạn', '', '');
        }, 500);
      @endif
    });
  </script>

  @stack('scripts')
</body>
</html>