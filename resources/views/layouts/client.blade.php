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

  <!-- Google Fonts: Libre Franklin, Cormorant Garamond & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Libre+Franklin:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    }
    .font-serif-luxury {
      font-family: 'Cormorant Garamond', Georgia, serif;
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
        <span><span class="text-amber-400 font-semibold">ƯU ĐÃI THÀNH VIÊN:</span> MÃ <strong class="text-white bg-neutral-800 px-1.5 py-0.5 rounded cursor-pointer" onclick="if(window.BeeCore) BeeCore.copyCode('BEESTYLE15')">BEESTYLE15</strong> GIẢM 15% | <strong class="text-white bg-neutral-800 px-1.5 py-0.5 rounded cursor-pointer" onclick="if(window.BeeCore) BeeCore.copyCode('BEESTYLE50')">BEESTYLE50</strong> GIẢM 50K</span>
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
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      
      <!-- Mobile Menu Button -->
      <div class="flex items-center gap-3 md:hidden">
        <button onclick="toggleMobileNav()" class="p-1.5 text-neutral-800 hover:text-black" aria-label="Menu">
          <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <button onclick="if(window.BeeCore) BeeCore.openSearchModal()" class="p-1.5 text-neutral-800 hover:text-black" aria-label="Tìm kiếm">
          <i data-lucide="search" class="w-5 h-5"></i>
        </button>
      </div>

      <!-- Main Desktop Navigation Links -->
      <nav class="hidden md:flex items-center gap-8 text-xs tracking-[0.2em] uppercase font-medium text-neutral-700">
        <a href="{{ route('client.home') }}" class="hover:text-black transition-colors {{ request()->routeIs('client.home') ? 'active text-neutral-950 font-bold' : '' }}">Trang Chủ</a>
        <a href="{{ route('client.home') }}#collections" class="hover:text-black transition-colors">Bộ Sưu Tập</a>
        
        <!-- ZARA MEGA MENU TRIGGER (SẢN PHẨM) -->
        <div class="relative group/mega py-3">
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
                <a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="block font-serif text-3xl xl:text-4xl text-neutral-950 font-bold hover:text-amber-800 transition-colors uppercase tracking-tight py-0.5 flex items-center gap-2">
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
                <!-- [01] MỚI -->
                <div class="space-y-1">
                  <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[01] MỚI</span>
                  <a href="{{ route('client.products.index', ['sort' => 'latest']) }}" class="block text-neutral-950 hover:text-amber-800 font-bold text-sm transition-colors">THE NEW 2026</a>
                  <a href="{{ route('client.products.index') }}" class="block text-neutral-600 hover:text-black transition-colors">SARTORIAL x BEESTYLE</a>
                </div>

                <!-- [02] DÒNG THIẾT KẾ -->
                <div class="space-y-1">
                  <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[02] ĐẶC QUYỀN</span>
                  <a href="{{ route('client.products.index', ['featured' => 1]) }}" class="block text-neutral-800 hover:text-black font-semibold transition-colors">BEESTYLE ORIGINS</a>
                </div>

                <!-- [03] SPOTLIGHT -->
                <div class="space-y-1">
                  <span class="text-[10px] text-neutral-400 font-mono tracking-widest block font-bold">[03] ƯU ĐÃI</span>
                  <a href="{{ route('client.daily-deals.index') }}" class="block text-rose-700 font-bold hover:text-rose-800 transition-colors flex items-center gap-1.5">
                    <span>FLASH SALE TRONG NGÀY</span>
                    <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded text-[10px]">HOT</span>
                  </a>
                </div>

                <!-- [04] BỘ SƯU TẬP & DANH MỤC -->
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

        <a href="{{ route('client.home') }}#lookbook" class="hover:text-black transition-colors">Lookbook</a>
        <a href="{{ route('client.home') }}#about" class="hover:text-black transition-colors">Beestyle</a>
      </nav>

      <!-- Brand Logo Center -->
      <div class="text-center">
        <a href="{{ route('client.home') }}" class="inline-block group">
          <span class="font-serif-luxury text-2xl md:text-3xl font-bold tracking-[0.25em] text-neutral-900 uppercase group-hover:opacity-80 transition-opacity">
            BEESTYLE
          </span>
          <span class="block text-[9px] tracking-[0.4em] text-neutral-500 uppercase -mt-1 font-sans">
            STUDIO • 2026
          </span>
        </a>
      </div>

      <!-- Action Icons Right -->
      <div class="flex items-center gap-5 text-neutral-800">
        
        <!-- Search Trigger / Modal Trigger -->
        <button onclick="if(window.BeeCore) BeeCore.openSearchModal();" class="hidden md:flex items-center gap-1.5 text-xs uppercase tracking-wider hover:text-black transition-colors p-1" title="Tìm kiếm">
          <i data-lucide="search" class="w-4 h-4"></i>
          <span class="hidden lg:inline text-[11px]">Tìm kiếm</span>
        </button>

        <!-- Wishlist Link -->
        <a href="{{ route('client.wishlist.index') }}" class="relative hover:text-black transition-colors p-1" title="Yêu thích">
          <i data-lucide="heart" class="w-5 h-5"></i>
          @if(isset($wishlistCount) && $wishlistCount > 0)
            <span class="absolute -top-1 -right-1 bg-amber-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $wishlistCount }}</span>
          @endif
        </a>

        <!-- Shopping Cart Link / Slideover -->
        <a href="{{ route('client.cart') }}" class="relative hover:text-black transition-colors flex items-center gap-2 p-1" title="Túi mua hàng">
          <div class="relative">
            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
            @php $cartQty = session('cart') ? array_sum(array_column(session('cart'), 'quantity')) : 0; @endphp
            @if($cartQty > 0)
              <span class="absolute -top-1.5 -right-1.5 bg-neutral-900 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">{{ $cartQty }}</span>
            @endif
          </div>
          @php
            $cartSubtotal = 0;
            if (session('cart')) {
              foreach(session('cart') as $cItem) {
                $cartSubtotal += ($cItem['price'] ?? 0) * ($cItem['quantity'] ?? 1);
              }
            }
          @endphp
          <span class="hidden lg:inline text-xs font-semibold tracking-wider text-neutral-900">{{ number_format($cartSubtotal, 0, ',', '.') }}₫</span>
        </a>

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
                <a href="{{ route('client.profile', ['tab' => 'address']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="map-pin" class="w-4 h-4 text-neutral-400"></i> Sổ địa chỉ nhận hàng
                </a>
                <a href="{{ route('client.profile', ['tab' => 'security']) }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs text-neutral-700 hover:bg-neutral-50 hover:text-black transition-colors font-medium">
                  <i data-lucide="lock" class="w-4 h-4 text-neutral-400"></i> Bảo mật &amp; Mật khẩu
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
            <div>
              <span class="font-serif-luxury text-xl font-bold tracking-[0.2em]">BEESTYLE</span>
              <span class="block text-[8px] tracking-[0.3em] text-neutral-500 uppercase">STUDIO • 2026</span>
            </div>
            <button onclick="toggleMobileNav()" class="p-2 text-neutral-500 hover:text-black">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
          <nav class="flex flex-col gap-4 mt-6 text-sm tracking-[0.2em] uppercase font-medium">
            <a href="{{ route('client.home') }}" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Trang Chủ</a>
            <a href="{{ route('client.home') }}#collections" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Bộ Sưu Tập 2026</a>
            <a href="{{ route('client.products.index') }}" class="py-2 border-b border-neutral-100 text-neutral-950 font-bold">Tất Cả Sản Phẩm</a>
            <a href="{{ route('client.daily-deals.index') }}" class="py-2 border-b border-neutral-100 text-rose-700 font-bold flex items-center justify-between">
              <span>Flash Sale Ngày</span>
              <span class="bg-rose-100 text-rose-700 px-1.5 py-0.5 rounded text-[10px]">HOT</span>
            </a>
            <a href="{{ route('client.home') }}#lookbook" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Lookbook Editorial</a>
            <a href="{{ route('client.home') }}#about" onclick="toggleMobileNav()" class="py-2 border-b border-neutral-100">Beestyle Atelier</a>
            <a href="{{ route('client.order-tracking') }}" class="py-2 text-amber-700 flex items-center gap-2">
              <i data-lucide="package" class="w-4 h-4"></i> Tra cứu đơn hàng
            </a>
            <div class="mt-2 pt-3 border-t border-neutral-100 flex flex-col gap-2">
              <span class="text-[10px] tracking-widest uppercase text-neutral-400 font-semibold">TÀI KHOẢN KHÁCH HÀNG</span>
              @auth
                <a href="{{ route('client.profile') }}" class="py-1 text-xs text-neutral-800 flex items-center gap-2 font-normal">
                  <i data-lucide="user" class="w-4 h-4 text-neutral-500"></i> Thông tin: {{ Auth::user()->name }}
                </a>
                <a href="{{ route('client.profile', ['tab' => 'orders']) }}" class="py-1 text-xs text-neutral-800 flex items-center gap-2 font-normal">
                  <i data-lucide="shopping-bag" class="w-4 h-4 text-neutral-500"></i> Đơn hàng của tôi
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
  <!-- 4. LUXURY FOOTER -->
  <!-- ========================================================================= -->
  <footer class="w-full bg-neutral-950 text-neutral-400 pt-16 pb-12 border-t border-neutral-800 mt-auto">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
      
      <div>
        <span class="font-serif-luxury text-2xl font-bold tracking-[0.25em] text-white uppercase block mb-3">BEESTYLE</span>
        <p class="text-xs leading-relaxed text-neutral-400 font-light mb-4">
          Nhà may đương đại &amp; thời trang thiết kế tối giản. Tôn vinh vẻ đẹp tự nhiên và sự chuẩn mực trong từng đường kim mũi chỉ.
        </p>
        <span class="text-xs text-neutral-300">Hotline: 1900 8899 (8:00 - 22:00)</span>
      </div>

      <div>
        <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-white mb-4">Danh Mục</h4>
        <ul class="space-y-2 text-xs">
          <li><a href="{{ route('client.products.index', ['category' => 'ao-so-mi-nam']) }}" class="hover:text-white transition-colors">Sơ Mi Lụa May Đo</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-khoac-blazer-nam']) }}" class="hover:text-white transition-colors">Blazer &amp; Áo Khoác Ý</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-polo-nam']) }}" class="hover:text-white transition-colors">Polo Luxury Cotton</a></li>
          <li><a href="{{ route('client.products.index', ['category' => 'ao-thun-nam']) }}" class="hover:text-white transition-colors">Áo Thun Streetwear Boxy</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-white mb-4">Dịch Vụ Khách Hàng</h4>
        <ul class="space-y-2 text-xs">
          <li><a href="{{ route('client.order-tracking') }}" class="hover:text-white transition-colors">Tra Cứu Đơn Hàng</a></li>
          <li><a href="{{ route('client.profile', ['tab' => 'returns']) }}" class="hover:text-white transition-colors">Đổi Trả &amp; Bảo Hành 30 Ngày</a></li>
          <li><a href="{{ route('client.home') }}#about" class="hover:text-white transition-colors">Về Thương Hiệu Beestyle</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-xs uppercase tracking-[0.2em] font-semibold text-white mb-4">Atelier Vietnam</h4>
        <p class="text-xs leading-relaxed text-neutral-400 mb-2">
          Flagship Store: 88 Lê Lợi, Bến Nghé, Quận 1, TP. Hồ Chí Minh
        </p>
        <p class="text-[11px] text-neutral-500">© 2026 Beestyle Atelier. All rights reserved.</p>
      </div>

    </div>
  </footer>

  <!-- App Core JS & Helper Scripts -->
  <script src="{{ asset('assets/js/app-core.js') }}"></script>
  <script src="{{ asset('assets/js/ecommerce-validators.js') }}"></script>

  <script>
    function toggleMobileNav() {
      document.getElementById('mobile-nav')?.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
      if (window.lucide) {
        lucide.createIcons();
      }
    });
  </script>

  @stack('scripts')
</body>
</html>