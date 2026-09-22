@extends('layouts.client')

@section('title', 'Tài Khoản & Thiết Lập Atelier — BEESTYLE Studio')

@section('content')
@php
  $addresses = $addresses ?? ($user->addresses ?? collect());
  $pendingReviewItems = $pendingReviewItems ?? collect();
  $userReviews = $user->reviews ?? collect();

  $vietnamBanks = [
    'Ngân hàng phổ biến nhất' => [
      ['code' => 'VCB', 'short_name' => 'Vietcombank', 'full_name' => 'Vietcombank - Ngân hàng Ngoại Thương Việt Nam (VCB)'],
      ['code' => 'MB', 'short_name' => 'MB Bank', 'full_name' => 'MB Bank - Ngân hàng TMCP Quân Đội (MB)'],
      ['code' => 'TCB', 'short_name' => 'Techcombank', 'full_name' => 'Techcombank - Ngân hàng Kỹ Thương Việt Nam (TCB)'],
      ['code' => 'CTG', 'short_name' => 'VietinBank', 'full_name' => 'VietinBank - Ngân hàng Công Thương Việt Nam (CTG)'],
      ['code' => 'BIDV', 'short_name' => 'BIDV', 'full_name' => 'BIDV - Ngân hàng Đầu Tư & Phát Triển Việt Nam'],
      ['code' => 'VPB', 'short_name' => 'VPBank', 'full_name' => 'VPBank - Ngân hàng Việt Nam Thịnh Vượng (VPB)'],
      ['code' => 'ACB', 'short_name' => 'ACB', 'full_name' => 'ACB - Ngân hàng TMCP Á Châu (ACB)'],
      ['code' => 'TPB', 'short_name' => 'TPBank', 'full_name' => 'TPBank - Ngân hàng Tiên Phong (TPB)'],
      ['code' => 'STB', 'short_name' => 'Sacombank', 'full_name' => 'Sacombank - Ngân hàng Sài Gòn Thương Tín (STB)'],
      ['code' => 'VBA', 'short_name' => 'Agribank', 'full_name' => 'Agribank - Ngân hàng Nông Nghiệp & PTNT (VBA)'],
    ],
    'Ngân hàng Thương mại Cổ phần' => [
      ['code' => 'VIB', 'short_name' => 'VIB', 'full_name' => 'VIB - Ngân hàng Quốc Tế Việt Nam'],
      ['code' => 'HDB', 'short_name' => 'HDBank', 'full_name' => 'HDBank - Ngân hàng Phát Triển TP.HCM'],
      ['code' => 'SHB', 'short_name' => 'SHB', 'full_name' => 'SHB - Ngân hàng Sài Gòn - Hà Nội'],
      ['code' => 'MSB', 'short_name' => 'MSB', 'full_name' => 'MSB - Ngân hàng Hàng Hải Việt Nam'],
      ['code' => 'OCB', 'short_name' => 'OCB', 'full_name' => 'OCB - Ngân hàng Phương Đông'],
      ['code' => 'SSB', 'short_name' => 'SeABank', 'full_name' => 'SeABank - Ngân hàng Đông Nam Á'],
      ['code' => 'LPB', 'short_name' => 'LPBank', 'full_name' => 'LPBank - Ngân hàng Lộc Phát Việt Nam (Bưu Điện Liên Việt)'],
      ['code' => 'EIB', 'short_name' => 'Eximbank', 'full_name' => 'Eximbank - Ngân hàng Xuất Nhập Khẩu Việt Nam'],
      ['code' => 'PVB', 'short_name' => 'PVcomBank', 'full_name' => 'PVcomBank - Ngân hàng Đại Chúng Việt Nam'],
      ['code' => 'BAB', 'short_name' => 'Bac A Bank', 'full_name' => 'Bac A Bank - Ngân hàng TMCP Bắc Á'],
      ['code' => 'BVB', 'short_name' => 'BaoViet Bank', 'full_name' => 'BaoViet Bank - Ngân hàng Bảo Việt'],
      ['code' => 'ABB', 'short_name' => 'ABBANK', 'full_name' => 'ABBANK - Ngân hàng An Bình'],
      ['code' => 'NAB', 'short_name' => 'Nam A Bank', 'full_name' => 'Nam A Bank - Ngân hàng Nam Á'],
      ['code' => 'KLB', 'short_name' => 'Kienlongbank', 'full_name' => 'Kienlongbank - Ngân hàng Kiên Long'],
      ['code' => 'BVBANK', 'short_name' => 'BVBank', 'full_name' => 'BVBank - Ngân hàng Bản Việt'],
      ['code' => 'PGB', 'short_name' => 'PG Bank', 'full_name' => 'PG Bank - Ngân hàng Xăng Dầu Petrolimex'],
      ['code' => 'SGB', 'short_name' => 'Saigonbank', 'full_name' => 'Saigonbank - Ngân hàng Sài Gòn Công Thương'],
      ['code' => 'VAB', 'short_name' => 'VietABank', 'full_name' => 'VietABank - Ngân hàng Việt Á'],
    ],
    'Ngân hàng số & Ví điện tử' => [
      ['code' => 'CAKE', 'short_name' => 'Cake by VPBank', 'full_name' => 'Cake by VPBank - Ngân hàng số Cake'],
      ['code' => 'TNEX', 'short_name' => 'TNEX', 'full_name' => 'TNEX - Ngân hàng số TNEX (MSB)'],
      ['code' => 'TIMO', 'short_name' => 'Timo', 'full_name' => 'Timo - Ngân hàng số Timo (BVBank)'],
      ['code' => 'VIETTEL', 'short_name' => 'Viettel Money', 'full_name' => 'Viettel Money - Tổng công ty Dịch vụ số Viettel'],
      ['code' => 'VNPT', 'short_name' => 'VNPT Money', 'full_name' => 'VNPT Money - Tập đoàn Bưu chính Viễn thông'],
    ],
    'Ngân hàng Quốc tế & Liên doanh' => [
      ['code' => 'SHBVN', 'short_name' => 'Shinhan Bank', 'full_name' => 'Shinhan Bank - Ngân hàng TNHH MTV Shinhan Việt Nam'],
      ['code' => 'WRB', 'short_name' => 'Woori Bank', 'full_name' => 'Woori Bank - Ngân hàng TNHH MTV Woori Việt Nam'],
      ['code' => 'HSBC', 'short_name' => 'HSBC', 'full_name' => 'HSBC - Ngân hàng TNHH MTV HSBC Việt Nam'],
      ['code' => 'SCVN', 'short_name' => 'Standard Chartered', 'full_name' => 'Standard Chartered - Ngân hàng Standard Chartered VN'],
      ['code' => 'PBVN', 'short_name' => 'Public Bank', 'full_name' => 'Public Bank - Ngân hàng Public Bank Việt Nam'],
      ['code' => 'UOB', 'short_name' => 'UOB', 'full_name' => 'UOB - Ngân hàng United Overseas Bank Việt Nam'],
      ['code' => 'CIMB', 'short_name' => 'CIMB', 'full_name' => 'CIMB - Ngân hàng TNHH MTV CIMB Việt Nam'],
      ['code' => 'IVB', 'short_name' => 'Indovina Bank', 'full_name' => 'Indovina Bank - Ngân hàng TNHH Indovina (IVB)'],
      ['code' => 'HLB', 'short_name' => 'Hong Leong Bank', 'full_name' => 'Hong Leong Bank - Ngân hàng Hong Leong Việt Nam'],
    ],
  ];
  
  $totalSpent = $orders->where('shipping_status', 'completed')->sum('total_amount');
  if ($totalSpent <= 0) {
    $totalSpent = $orders->where('payment_status', 'paid')->sum('total_amount');
  }
  
  if ($totalSpent >= 10000000) {
    $tierName = 'VIP Kim Cương (Diamond)';
    $tierBadgeClass = 'bg-neutral-900 text-amber-400 border border-amber-400/40';
    $nextTierName = 'Hạng Cao Nhất';
    $nextTierTarget = 10000000;
    $progressPercent = 100;
    $neededMore = 0;
  } elseif ($totalSpent >= 5000000) {
    $tierName = 'VIP Vàng (Gold)';
    $tierBadgeClass = 'bg-amber-100 text-amber-900 border border-amber-300';
    $nextTierName = 'VIP Kim Cương';
    $nextTierTarget = 10000000;
    $progressPercent = min(100, round(($totalSpent / 10000000) * 100));
    $neededMore = 10000000 - $totalSpent;
  } elseif ($totalSpent >= 2000000) {
    $tierName = 'Hội Viên Bạc (Silver)';
    $tierBadgeClass = 'bg-neutral-200 text-neutral-800';
    $nextTierName = 'VIP Vàng';
    $nextTierTarget = 5000000;
    $progressPercent = min(100, round(($totalSpent / 5000000) * 100));
    $neededMore = 5000000 - $totalSpent;
  } else {
    $tierName = 'Thành Viên Đồng (Bronze)';
    $tierBadgeClass = 'bg-neutral-100 text-neutral-700 border border-neutral-200';
    $nextTierName = 'Hội Viên Bạc';
    $nextTierTarget = 2000000;
    $progressPercent = min(100, round(($totalSpent / 2000000) * 100));
    $neededMore = 2000000 - $totalSpent;
  }
@endphp

<main class="w-full flex-grow py-10 px-6 max-w-7xl mx-auto">
  
  <!-- Breadcrumb Navigation -->
  <nav class="flex items-center gap-2 text-xs text-neutral-500 mb-8 overflow-x-auto whitespace-nowrap pb-2">
    <a href="{{ route('client.home') }}" class="hover:text-black">Trang Chủ</a>
    <i data-lucide="chevron-right" class="w-3 h-3 text-neutral-400"></i>
    <span class="text-neutral-900 font-semibold">Tài Khoản Thành Viên Atelier</span>
  </nav>

  @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 p-4 rounded-xl mb-6 text-xs flex items-center gap-2.5 animate-fade-in">
      <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
      <span>{{ session('success') }}</span>
    </div>
  @endif

  @if(session('info'))
    <div class="bg-sky-50 border border-sky-200 text-sky-900 p-4 rounded-xl mb-6 text-xs flex items-center gap-2.5 animate-fade-in">
      <i data-lucide="info" class="w-4 h-4 text-sky-600 shrink-0"></i>
      <span>{{ session('info') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="bg-rose-50 border border-rose-200 text-rose-900 p-4 rounded-xl mb-6 text-xs flex items-center gap-2.5 animate-fade-in">
      <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
      <span>{{ session('error') }}</span>
    </div>
  @endif

  <!-- Flash Error Alerts -->
  @if(isset($errors) && $errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl mb-6 text-xs animate-fade-in">
      <div class="flex items-center gap-2 font-semibold mb-1">
        <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
        <span>Vui lòng kiểm tra lại thông tin:</span>
      </div>
      <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-2">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- LEFT COLUMN: User Sidebar & Tab Navigation (4 cols) -->
    <div class="lg:col-span-4 bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-center">
      
      <!-- Avatar & Crown -->
      <div class="relative w-24 h-24 mx-auto mb-4">
        <img id="sidebarAvatarPreview" src="{{ asset($user->avatar ?? 'assets/img/team/40x40/58.webp') }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover border-2 border-neutral-900 shadow-md">
        <span class="absolute bottom-0 right-0 w-7 h-7 rounded-full bg-amber-400 text-neutral-950 flex items-center justify-center border-2 border-white shadow-xs" title="Hội viên VIP Atelier">
          <i data-lucide="crown" class="w-3.5 h-3.5"></i>
        </span>
      </div>

      <!-- User Info -->
      <h2 class="font-serif-luxury text-2xl font-bold text-neutral-900 mb-1">{{ $user->name }}</h2>
      <p class="text-xs text-neutral-500 mb-3">{{ $user->email }}</p>
      
      <div class="flex justify-center items-center gap-2 mb-6 flex-wrap">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase {{ $tierBadgeClass }}">
          {{ $tierName }}
        </span>
        <span class="px-2.5 py-1 bg-neutral-100 text-neutral-700 rounded-full text-[10px] font-semibold">
          {{ number_format($user->points ?? 150) }} ĐIỂM THƯỞNG
        </span>
      </div>

      <!-- Navigation Tabs List -->
      <div class="space-y-1 text-left text-xs font-medium border-t border-neutral-100 pt-4" id="profileTabs">
        
        <button onclick="switchProfileTab('orders')" id="tab-btn-orders" class="w-full flex items-center justify-between p-3 rounded-xl transition-colors bg-neutral-950 text-white font-semibold profile-tab-btn">
          <div class="flex items-center gap-2.5">
            <i data-lucide="shopping-bag" class="w-4 h-4 text-amber-400"></i>
            <span>Đơn Hàng Của Tôi</span>
          </div>
          <span class="px-2 py-0.5 bg-neutral-800 text-white rounded-full text-[10px] font-bold">{{ $orders->count() }}</span>
        </button>

        <button onclick="switchProfileTab('pending-reviews')" id="tab-btn-pending-reviews" class="w-full flex items-center justify-between p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <div class="flex items-center gap-2.5">
            <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
            <span>Chờ Đánh Giá</span>
          </div>
          <span class="px-2 py-0.5 {{ $pendingReviewItems->count() > 0 ? 'bg-rose-100 text-rose-700 font-bold' : 'bg-neutral-100 text-neutral-600' }} rounded-full text-[10px]">
            {{ $pendingReviewItems->count() }}
          </span>
        </button>

        <button onclick="switchProfileTab('my-reviews')" id="tab-btn-my-reviews" class="w-full flex items-center justify-between p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <div class="flex items-center gap-2.5">
            <i data-lucide="star" class="w-4 h-4 text-amber-500"></i>
            <span>Đánh Giá Của Tôi</span>
          </div>
          <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 rounded-full text-[10px]">{{ $userReviews->count() }}</span>
        </button>

        <button onclick="switchProfileTab('returns')" id="tab-btn-returns" class="w-full flex items-center justify-between p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <div class="flex items-center gap-2.5">
            <i data-lucide="rotate-ccw" class="w-4 h-4 text-amber-600"></i>
            <span>Đổi Trả &amp; Hoàn Tiền (RMA)</span>
          </div>
          <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 rounded-full text-[10px]">{{ isset($returns) ? $returns->count() : 0 }}</span>
        </button>

        <button onclick="switchProfileTab('profile')" id="tab-btn-profile" class="w-full flex items-center gap-2.5 p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <i data-lucide="user" class="w-4 h-4 text-neutral-400"></i>
          <span>Hồ Sơ &amp; Liên Hệ (2-Step OTP)</span>
        </button>

        <button onclick="switchProfileTab('bank')" id="tab-btn-bank" class="w-full flex items-center gap-2.5 p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <i data-lucide="credit-card" class="w-4 h-4 text-neutral-400"></i>
          <span>Tài Khoản Ngân Hàng Hoàn Tiền</span>
        </button>

        <button onclick="switchProfileTab('password')" id="tab-btn-password" class="w-full flex items-center gap-2.5 p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <i data-lucide="shield" class="w-4 h-4 text-neutral-400"></i>
          <span>Đổi Mật Khẩu &amp; Revoke Sessions</span>
        </button>

        <button onclick="switchProfileTab('addresses')" id="tab-btn-addresses" class="w-full flex items-center justify-between p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <div class="flex items-center gap-2.5">
            <i data-lucide="map-pin" class="w-4 h-4 text-neutral-400"></i>
            <span>Sổ Địa Chỉ Giao Hàng</span>
          </div>
          <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 rounded-full text-[10px]">{{ $addresses->count() }}</span>
        </button>

        <button onclick="switchProfileTab('vip')" id="tab-btn-vip" class="w-full flex items-center gap-2.5 p-3 rounded-xl transition-colors text-neutral-700 hover:bg-neutral-50 profile-tab-btn">
          <i data-lucide="gift" class="w-4 h-4 text-amber-600"></i>
          <span>Đặc Quyền Thành Viên Atelier</span>
        </button>

      </div>

      <!-- Logout Form -->
      <form action="{{ route('auth.logout') }}" method="POST" class="mt-6 pt-4 border-t border-neutral-100">
        @csrf
        <button type="submit" class="w-full py-2.5 border border-rose-200 text-rose-600 hover:bg-rose-50 text-xs font-semibold uppercase tracking-wider rounded-xl transition-colors flex items-center justify-center gap-2">
          <i data-lucide="log-out" class="w-4 h-4"></i>
          <span>Đăng Xuất Tài Khoản</span>
        </button>
      </form>

    </div>

    <!-- RIGHT COLUMN: Tab Contents (8 cols) -->
    <div class="lg:col-span-8 space-y-6">
      
      <!-- ========================================================================= -->
      <!-- TAB 1: ORDERS HISTORY & ACTIONS -->
      <!-- ========================================================================= -->
      <div id="tab-panel-orders" class="profile-panel space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
          <div class="flex justify-between items-center pb-4 mb-6 border-b border-neutral-100">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Lịch Sử Đơn Hàng</h3>
              <p class="text-xs text-neutral-500 mt-0.5">Theo dõi chi tiết các đơn may đo, đánh giá sản phẩm và đổi trả</p>
            </div>
            <a href="{{ route('client.products.index') }}" class="text-xs text-amber-800 font-semibold hover:underline flex items-center gap-1">
              <span>Mua thêm</span>
              <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
          </div>

          @if($orders->isEmpty())
            <div class="text-center py-12 text-neutral-500 text-xs">
              <i data-lucide="package" class="w-12 h-12 mx-auto text-neutral-300 mb-2 stroke-1"></i>
              <p class="font-medium text-neutral-800">Bạn chưa có đơn hàng nào tại BeeStyle</p>
              <a href="{{ route('client.products.index') }}" class="inline-block mt-3 text-amber-800 font-semibold underline">Khám phá các thiết kế mới ngay</a>
            </div>
          @else
            <div class="space-y-5">
              @foreach($orders as $order)
                <div class="p-5 rounded-xl border border-neutral-200 bg-neutral-50/60 space-y-4 text-xs shadow-2xs">
                  
                  <!-- Order Top Info -->
                  <div class="flex flex-wrap justify-between items-center gap-2 pb-3 border-b border-neutral-200">
                    <div>
                      <span class="font-mono font-bold text-neutral-950 text-sm">#{{ $order->order_code }}</span>
                      <span class="text-neutral-400 text-[11px] ml-2">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      @if($order->shipping_status === 'completed')
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded font-bold text-[10px]">Hoàn tất</span>
                      @elseif($order->shipping_status === 'delivered')
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 rounded font-bold text-[10px]">Đã giao hàng</span>
                      @elseif($order->shipping_status === 'shipping')
                        <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 rounded font-bold text-[10px]">Đang giao hàng</span>
                      @elseif($order->shipping_status === 'processing')
                        <span class="px-2.5 py-0.5 bg-sky-100 text-sky-800 rounded font-bold text-[10px]">Đang đóng gói</span>
                      @elseif($order->shipping_status === 'cancelled')
                        @if(method_exists($order, 'isCustomerRejected') && $order->isCustomerRejected())
                          <span class="px-2.5 py-0.5 bg-rose-600 text-white rounded font-bold text-[10px]">Khách không nhận (Chuyển hoàn)</span>
                        @else
                          <span class="px-2.5 py-0.5 bg-rose-100 text-rose-800 rounded font-bold text-[10px]">Đã hủy</span>
                        @endif
                      @else
                        <span class="px-2.5 py-0.5 bg-neutral-200 text-neutral-800 rounded font-bold text-[10px]">Chờ xác nhận</span>
                      @endif

                      <span class="font-serif-luxury text-base font-bold text-neutral-950 ml-1">
                        {{ number_format($order->total_amount, 0, ',', '.') }}₫
                      </span>
                    </div>
                  </div>

                  <!-- Banner cảnh báo Đơn hàng từ chối nhận / Đã hủy -->
                  @if(method_exists($order, 'isCustomerRejected') && $order->isCustomerRejected())
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-900 flex items-start gap-2.5">
                      <i data-lucide="truck" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                      <div class="text-[11px]">
                        <strong>Đơn hàng đã từ chối nhận (Đang chuyển hoàn về kho):</strong>
                        <span>{{ $order->cancel_reason ?: 'Khách hàng từ chối nhận bưu phẩm' }}</span>
                        <span class="block text-neutral-400 text-[10px] mt-0.5">Thời gian ghi nhận: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : '' }}</span>
                      </div>
                    </div>
                  @elseif($order->shipping_status === 'cancelled')
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-900 flex items-start gap-2.5">
                      <i data-lucide="x-circle" class="w-4 h-4 text-rose-600 shrink-0 mt-0.5"></i>
                      <div class="text-[11px]">
                        <strong>Đơn hàng đã hủy:</strong> <span>{{ $order->cancel_reason ?: 'Hủy theo yêu cầu' }}</span>
                        <span class="block text-neutral-400 text-[10px] mt-0.5">Thời gian: {{ $order->cancelled_at ? $order->cancelled_at->format('d/m/Y H:i') : '' }}</span>
                      </div>
                    </div>
                  @endif

                  <!-- Banner Đổi Trả RMA Active -->
                  @if($order->latestReturn)
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-950 flex items-center justify-between flex-wrap gap-2">
                      <div class="text-[11px]">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 inline text-amber-700 mr-1"></i>
                        <strong>Yêu Cầu Đổi Trả #{{ $order->latestReturn->return_code ?: 'RMA-' . str_pad($order->latestReturn->id, 5, '0', STR_PAD_LEFT) }}:</strong>
                        <span>{{ $order->latestReturn->type_label ?? 'Đổi trả hàng' }}</span>
                        @if($order->latestReturn->reason)
                          - <em>"{{ $order->latestReturn->reason }}"</em>
                        @endif
                      </div>
                      <div class="flex items-center gap-1.5">
                        <button type="button" onclick="switchProfileTab('returns')" class="px-2.5 py-1 bg-white border border-neutral-300 rounded font-semibold text-[10px] hover:bg-neutral-50 shadow-2xs">
                          Xem Tab Đổi Trả
                        </button>
                        <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="px-2.5 py-1 bg-amber-600 text-white rounded font-semibold text-[10px] hover:bg-amber-700 shadow-2xs">
                          Tra Cứu Bưu Tá
                        </a>
                      </div>
                    </div>
                  @endif

                  <!-- Order Items List with Review & Return Buttons -->
                  <div class="space-y-3">
                    @foreach($order->items as $item)
                      <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-neutral-200/80 hover:border-neutral-300 transition-colors">
                        <div class="flex items-center gap-3 min-w-0 pr-2">
                          <div class="w-12 h-14 bg-neutral-100 rounded-lg border border-neutral-200 overflow-hidden shrink-0">
                            <img src="{{ asset($item->product->primaryImage->image_path ?? $item->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=200&auto=format&fit=crop') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                          </div>
                          <div class="min-w-0">
                            <a href="{{ route('client.products.show', $item->product_id ?: 1) }}" class="font-semibold text-neutral-900 hover:text-amber-800 transition-colors block line-clamp-1 text-xs">
                              {{ $item->product_name }}
                            </a>
                            <span class="text-[11px] text-neutral-500">Màu: {{ $item->color ?? 'Chuẩn' }} | Size: {{ $item->size ?? 'M' }} • SL: x{{ $item->quantity }}</span>
                          </div>
                        </div>

                        <div class="text-right flex flex-col items-end gap-1.5 shrink-0">
                          <span class="font-serif-luxury font-bold text-neutral-950">
                            {{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫
                          </span>
                          
                          <!-- Nút Đổi Trả & Đánh Giá Cho Đơn Đã Giao -->
                          @if(in_array($order->shipping_status, ['delivered', 'completed']) || $order->status === 'completed')
                            <div class="flex items-center gap-1.5">
                              <button type="button" onclick="openReturnModal({{ $order->id }}, '{{ $order->order_code }}', {{ $order->total_amount }}, {{ $item->id }})" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-900 rounded-md font-semibold text-[11px] flex items-center gap-1 transition-colors shadow-sm" title="Yêu cầu đổi trả riêng cho sản phẩm này">
                                <i data-lucide="rotate-ccw" class="w-3 h-3 text-amber-700"></i> Đổi trả
                              </button>
                              <button type="button" onclick="openQuickReviewModal({{ $item->product_id ?: 1 }}, '{{ addslashes($item->product_name) }}')" class="px-2.5 py-1 bg-neutral-900 hover:bg-neutral-800 text-white rounded-md font-semibold text-[11px] flex items-center gap-1 transition-colors shadow-sm">
                                <i data-lucide="star" class="w-3 h-3 text-amber-400"></i> Đánh giá
                              </button>
                            </div>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  </div>

                  <!-- Order Footer Actions -->
                  <div class="pt-3 border-t border-neutral-200 flex flex-wrap justify-between items-center gap-3">
                    <div class="text-neutral-500 text-[11px]">
                      Hình thức: <strong>{{ $order->payment_method_name ?? $order->payment_method }}</strong>
                      @if($order->is_deposit_required)
                        <span class="ml-1.5 px-2 py-0.5 bg-amber-100 text-amber-900 rounded font-bold text-[10px]">Cọc 50%: {{ number_format($order->deposit_amount, 0, ',', '.') }}₫</span>
                      @endif
                    </div>
                    
                    <div class="flex items-center gap-2 flex-wrap">
                      <!-- Hủy đơn hàng trước khi giao -->
                      @if(method_exists($order, 'canBeCancelledByCustomer') ? $order->canBeCancelledByCustomer() : in_array($order->shipping_status, ['pending', 'processing']))
                        <button type="button" onclick="openCancelModal({{ $order->id }}, '{{ $order->order_code }}', {{ $order->total_amount }}, {{ $order->items->count() }})" class="px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors">
                          Hủy Đơn
                        </button>
                      @endif

                      <!-- Xác nhận đã nhận / Hủy hàng hoàn tiền cho đơn đang giao -->
                      @if(in_array($order->shipping_status, ['shipping', 'delivered']) || (isset($order->status_step) && in_array($order->status_step, [4, 5])))
                        <form action="{{ route('client.order-tracking.confirm-delivered', $order->order_code) }}" method="POST" class="inline" onsubmit="return confirm('Bạn xác nhận ĐÃ NHẬN ĐƯỢC ĐỦ HÀNG và hài lòng với chất lượng kiện hàng #{{ $order->order_code }}?');">
                          @csrf
                          <button type="submit" class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow-xs transition-colors flex items-center gap-1.5">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                            <span>Đã Nhận Được Hàng</span>
                          </button>
                        </form>
                        <button type="button" onclick="openReturnModal({{ $order->id }}, '{{ $order->order_code }}', {{ $order->total_amount }})" class="px-3 py-1.5 border border-rose-300 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                          <span>Hủy Hàng Hoàn Tiền</span>
                        </button>
                      @endif

                      <!-- Yêu cầu đổi trả RMA sau khi nhận (Toàn bộ đơn hàng) -->
                      @if(in_array($order->shipping_status, ['delivered', 'completed']) || $order->status === 'completed')
                        <button type="button" onclick="openReturnModal({{ $order->id }}, '{{ $order->order_code }}', {{ $order->total_amount }})" class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-neutral-950 font-bold rounded-lg text-xs transition-colors flex items-center gap-1 shadow-xs">
                          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Đổi Hàng / Hoàn Tiền (7 Ngày)
                        </button>
                      @endif

                      <!-- Chi tiết vận chuyển -->
                      <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="px-3 py-1.5 bg-white border border-neutral-300 rounded-lg text-neutral-800 hover:bg-neutral-100 font-semibold transition-colors flex items-center gap-1">
                        <i data-lucide="truck" class="w-3.5 h-3.5 text-neutral-500"></i> Tra Cứu
                      </a>
                    </div>
                  </div>

                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 2: RMA / RETURN & EXCHANGE REQUESTS (ĐỔI TRẢ & HOÀN TIỀN) -->
      <!-- ========================================================================= -->
      <div id="tab-panel-returns" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="flex justify-between items-center flex-wrap gap-2 mb-6 pb-4 border-b border-neutral-100">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">
                Yêu Cầu Đổi Trả &amp; Bảo Hành ({{ isset($returns) ? $returns->count() : 0 }})
              </h3>
              <p class="text-neutral-500 mt-0.5">Theo dõi tiến trình đổi size, đổi màu và hoàn tiền cho các đơn hàng của bạn</p>
            </div>
            <button type="button" onclick="switchProfileTab('orders')" class="px-3.5 py-1.5 border border-neutral-300 hover:bg-neutral-50 rounded-lg font-semibold text-neutral-800 transition-colors flex items-center gap-1">
              <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i> Xem Danh Sách Đơn Hàng
            </button>
          </div>

          <!-- POLICY BANNER -->
          <div class="p-4 mb-6 rounded-xl border border-sky-200 bg-gradient-to-r from-sky-50 to-emerald-50 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-neutral-950 text-amber-400 flex items-center justify-center shrink-0 shadow-xs">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
              </div>
              <div>
                <h4 class="font-bold text-neutral-900 text-xs uppercase tracking-wide">Chính Sách Đổi Trả Tận Nhà Độc Quyền BeeStyle Atelier</h4>
                <p class="text-neutral-600 text-[11px] mt-0.5">
                  Miễn phí đổi Size / Màu trong <strong>7 ngày</strong> • Shipper mang sản phẩm mới đến tận nhà đổi đồng thời • Hoàn tiền 100% nếu lỗi sản phẩm
                </p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold rounded-full text-[10px]">✓ Đổi Tận Nơi</span>
              <span class="px-2.5 py-1 bg-sky-100 text-sky-800 font-bold rounded-full text-[10px]">⚡ 24-48 Giờ</span>
            </div>
          </div>

          <!-- RETURNS LIST -->
          <div class="space-y-4">
            @if(isset($returns) && $returns->count() > 0)
              @foreach($returns as $ret)
                <div class="border border-neutral-200 rounded-xl p-4 bg-neutral-50/50 shadow-2xs">
                  <!-- RETURN HEADER -->
                  <div class="flex justify-between items-center flex-wrap gap-2 pb-3 border-b border-neutral-200/80 mb-3">
                    <div>
                      <div class="flex items-center gap-2 flex-wrap">
                        <strong class="font-mono text-sm text-neutral-900">#{{ $ret->return_code ?: 'RMA-' . str_pad($ret->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        @if($ret->type === 'exchange')
                          <span class="px-2 py-0.5 bg-sky-100 text-sky-800 border border-sky-200 font-bold rounded-full text-[10px]">Đổi Size/Màu</span>
                        @elseif($ret->type === 'refund_only')
                          <span class="px-2 py-0.5 bg-amber-100 text-amber-900 border border-amber-200 font-bold rounded-full text-[10px]">Từ Chối Nhận Hàng</span>
                        @else
                          <span class="px-2 py-0.5 bg-rose-100 text-rose-800 border border-rose-200 font-bold rounded-full text-[10px]">Trả Hàng &amp; Hoàn Tiền</span>
                        @endif
                      </div>
                      <span class="text-neutral-500 text-[11px] mt-0.5 block">
                        Đơn hàng: <strong class="font-mono text-neutral-800">#{{ $ret->order->order_code ?? 'N/A' }}</strong> 
                        • Ngày gửi: {{ $ret->created_at ? $ret->created_at->format('d/m/Y H:i') : '' }}
                      </span>
                    </div>
                    <div>
                      {!! $ret->status_badge !!}
                    </div>
                  </div>

                  <!-- STEPPER TRACKER 4 BƯỚC -->
                  @php
                    $stepNum = match($ret->status) {
                      'pending' => 1,
                      'approved' => 2,
                      'received' => 3,
                      'completed' => 4,
                      'rejected' => 0,
                      default => 1,
                    };
                  @endphp
                  <div class="p-3 bg-white rounded-xl border border-neutral-200 mb-3">
                    <div class="grid grid-cols-4 gap-2 text-center text-[10px]">
                      <div>
                        <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-bold {{ $stepNum >= 1 ? 'bg-emerald-600 text-white' : 'bg-neutral-100 text-neutral-400' }}">
                          ✓
                        </div>
                        <span class="{{ $stepNum >= 1 ? 'font-bold text-neutral-900' : 'text-neutral-400' }}">1. Gửi Yêu Cầu</span>
                      </div>
                      <div>
                        <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-bold {{ $stepNum >= 2 ? 'bg-emerald-600 text-white' : ($stepNum === 1 ? 'bg-amber-400 text-neutral-950' : 'bg-neutral-100 text-neutral-400') }}">
                          {{ $stepNum >= 2 ? '✓' : '2' }}
                        </div>
                        <span class="{{ $stepNum >= 2 ? 'font-bold text-neutral-900' : 'text-neutral-400' }}">2. Shop Tiếp Nhận</span>
                      </div>
                      <div>
                        <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-bold {{ $stepNum >= 3 ? 'bg-emerald-600 text-white' : 'bg-neutral-100 text-neutral-400' }}">
                          {{ $stepNum >= 3 ? '✓' : '3' }}
                        </div>
                        <span class="{{ $stepNum >= 3 ? 'font-bold text-neutral-900' : 'text-neutral-400' }}">3. {{ $ret->type === 'exchange' ? 'Giao Đổi Tận Nơi' : 'Thu Hồi Hàng' }}</span>
                      </div>
                      <div>
                        <div class="w-6 h-6 rounded-full mx-auto mb-1 flex items-center justify-center font-bold {{ $stepNum === 4 ? 'bg-emerald-600 text-white' : ($ret->status === 'rejected' ? 'bg-rose-600 text-white' : 'bg-neutral-100 text-neutral-400') }}">
                          {{ $stepNum === 4 ? '✓' : ($ret->status === 'rejected' ? '✕' : '4') }}
                        </div>
                        <span class="{{ $stepNum === 4 ? 'font-bold text-emerald-700' : ($ret->status === 'rejected' ? 'font-bold text-rose-700' : 'text-neutral-400') }}">
                          {{ $ret->status === 'rejected' ? 'Bị Từ Chối' : 'Hoàn Tất' }}
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- RETURN DETAIL INFO -->
                  <div class="p-3 bg-white rounded-xl border border-neutral-200 mb-3 space-y-2">
                    <div>
                      <span class="text-neutral-500 text-[11px] block">Lý do yêu cầu:</span>
                      <strong class="text-neutral-900 text-xs">{{ $ret->reason }}</strong>
                    </div>
                    @if($ret->customer_notes)
                      <div>
                        <span class="text-neutral-500 text-[11px] block">Ghi chú của bạn:</span>
                        <span class="text-neutral-700 italic text-xs">"{{ $ret->customer_notes }}"</span>
                      </div>
                    @endif
                    @if($ret->type === 'exchange')
                      <div class="p-2.5 rounded-lg bg-sky-50 border border-sky-100 text-xs">
                        <strong class="text-sky-900 block mb-1">Thông tin đổi hàng mới:</strong>
                        <div class="flex gap-2 flex-wrap">
                          @if($ret->exchange_size)
                            <span class="px-2 py-0.5 bg-neutral-900 text-white rounded font-bold text-[10px]">Size: {{ $ret->exchange_size }}</span>
                          @endif
                          @if($ret->exchange_color)
                            <span class="px-2 py-0.5 bg-white border border-neutral-300 text-neutral-800 rounded font-semibold text-[10px]">Màu: {{ $ret->exchange_color }}</span>
                          @endif
                        </div>
                        <span class="text-neutral-500 text-[10px] mt-1 block">Shipper sẽ mang trang phục mới đến tận nhà đổi và thu hồi sản phẩm cũ.</span>
                      </div>
                    @elseif($ret->refund_amount > 0)
                      <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-100 text-xs">
                        <div class="flex justify-between items-center">
                          <span class="text-neutral-600">Số tiền hoàn trả:</span>
                          <strong class="text-rose-700 font-mono text-sm">{{ number_format($ret->refund_amount, 0, ',', '.') }}₫</strong>
                        </div>
                        @if($ret->bank_account_number)
                          <div class="mt-1 text-[11px] text-neutral-700">
                            TK nhận tiền: <strong>{{ $ret->bank_name }}</strong> • <span class="font-mono">{{ $ret->bank_account_number }}</span> ({{ $ret->bank_account_name }})
                          </div>
                        @endif
                      </div>
                    @endif

                    @if(!empty($ret->image_proofs) && is_array($ret->image_proofs))
                      <div class="pt-2 border-t border-neutral-100">
                        <span class="text-neutral-500 text-[10px] block mb-1">Ảnh minh chứng:</span>
                        <div class="flex gap-2 flex-wrap">
                          @foreach($ret->image_proofs as $img)
                            <a href="{{ asset($img) }}" target="_blank">
                              <img src="{{ asset($img) }}" alt="Minh chứng" class="w-12 h-12 rounded border border-neutral-200 object-cover shadow-2xs">
                            </a>
                          @endforeach
                        </div>
                      </div>
                    @endif
                  </div>

                  <!-- ADMIN RESPONSE -->
                  @if($ret->admin_notes)
                    <div class="p-3 bg-sky-50 border border-sky-200 rounded-xl text-sky-950 mb-3 text-xs">
                      <strong class="block mb-0.5 text-sky-900 font-bold">Phản hồi từ BeeStyle:</strong>
                      <span>{{ $ret->admin_notes }}</span>
                    </div>
                  @endif

                  @if($ret->rejected_reason)
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-950 mb-3 text-xs">
                      <strong class="block mb-0.5 text-rose-900 font-bold">Lý do từ chối:</strong>
                      <span>{{ $ret->rejected_reason }}</span>
                    </div>
                  @endif

                  <!-- FOOTER ACTIONS -->
                  <div class="flex justify-between items-center flex-wrap gap-2 pt-2 border-t border-neutral-200/60">
                    <span class="text-[10px] text-neutral-400">
                      Cập nhật: {{ $ret->updated_at ? $ret->updated_at->format('d/m/Y H:i') : '' }}
                    </span>
                    @if($ret->order)
                      <a href="{{ route('client.order-tracking', ['code' => $ret->order->order_code]) }}" class="px-3 py-1 bg-white border border-neutral-300 hover:bg-neutral-100 rounded-lg text-[11px] font-semibold text-neutral-800 transition-colors">
                        Tra Cứu Vận Trình Bưu Tá →
                      </a>
                    @endif
                  </div>
                </div>
              @endforeach
            @else
              <div class="text-center py-12 text-neutral-500">
                <i data-lucide="rotate-ccw" class="w-12 h-12 mx-auto text-neutral-300 mb-2 stroke-1"></i>
                <p class="font-medium text-neutral-800 text-sm">Bạn chưa có yêu cầu đổi trả hoặc bảo hành nào</p>
                <p class="text-neutral-400 mt-1 mb-4">Mọi sản phẩm mua tại BeeStyle đều được hưởng quyền lợi đổi size miễn phí tận nhà trong 7 ngày.</p>
                <button type="button" class="px-5 py-2 bg-neutral-950 hover:bg-neutral-800 text-white rounded-lg text-xs font-semibold uppercase tracking-wider transition-colors" onclick="switchProfileTab('orders')">
                  Xem Danh Sách Đơn Hàng
                </button>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 3: PENDING REVIEWS (CHỜ ĐÁNH GIÁ) -->
      <!-- ========================================================================= -->
      <div id="tab-panel-pending-reviews" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100 flex justify-between items-center flex-wrap gap-2">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Sản Phẩm Chờ Đánh Giá</h3>
              <p class="text-neutral-500 mt-0.5">Chia sẻ cảm nhận thực tế sau khi nhận đồ may đo để nhận điểm thưởng Atelier</p>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full font-bold uppercase text-[10px]">
              +50 ĐIỂM THƯỞNG / ĐÁNH GIÁ
            </span>
          </div>

          <div class="space-y-3">
            @forelse($pendingReviewItems as $pItem)
              <div class="p-4 bg-neutral-50 rounded-xl border border-neutral-200 flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                  <img src="{{ asset($pItem->image ?? ($pItem->product->thumbnail ?? 'assets/img/products/1.png')) }}" alt="{{ $pItem->product_name }}" class="w-14 h-16 rounded-lg border border-neutral-200 object-cover bg-white shrink-0">
                  <div>
                    <h4 class="font-semibold text-neutral-900 text-sm line-clamp-1">{{ $pItem->product_name }}</h4>
                    <p class="text-neutral-500 text-[11px] mt-0.5">
                      Đơn hàng: <strong class="font-mono text-neutral-800">{{ $pItem->order->order_code ?? '' }}</strong>
                      @if($pItem->color || $pItem->size)
                        | Phân loại: {{ $pItem->color ?? '' }} / {{ $pItem->size ?? '' }}
                      @endif
                    </p>
                    <span class="text-neutral-950 font-bold mt-1 block">{{ number_format($pItem->price, 0, ',', '.') }}₫</span>
                  </div>
                </div>
                <button type="button" onclick="openQuickReviewModal({{ $pItem->product_id }}, '{{ addslashes($pItem->product_name) }}')" class="px-4 py-2 bg-neutral-950 hover:bg-neutral-800 text-white rounded-lg font-semibold text-xs transition-colors flex items-center gap-1.5">
                  <i data-lucide="star" class="w-3.5 h-3.5 text-amber-400"></i> Viết Đánh Giá
                </button>
              </div>
            @empty
              <div class="text-center py-12 text-neutral-500">
                <i data-lucide="check-circle" class="w-12 h-12 mx-auto text-emerald-500 mb-2 stroke-1"></i>
                <p class="font-medium text-neutral-800 text-sm">Tuyệt vời! Bạn đã đánh giá tất cả sản phẩm đã nhận</p>
                <p class="text-neutral-400 mt-1">Cảm ơn bạn đã luôn đóng góp nhận xét chân thực cho BeeStyle Atelier.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 4: MY REVIEWS (ĐÁNH GIÁ CỦA TÔI) -->
      <!-- ========================================================================= -->
      <div id="tab-panel-my-reviews" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Đánh Giá Của Tôi ({{ $userReviews->count() }})</h3>
            <p class="text-neutral-500 mt-0.5">Xem lại những nhận xét và góp ý bạn đã gửi tới các nghệ nhân xưởng may</p>
          </div>

          <div class="space-y-4">
            @forelse($userReviews as $myRev)
              <div class="p-4 bg-neutral-50 rounded-xl border border-neutral-200 space-y-2">
                <div class="flex justify-between items-start">
                  <div>
                    <h4 class="font-semibold text-neutral-900 text-sm">{{ $myRev->product->name ?? 'Sản phẩm Atelier' }}</h4>
                    <span class="text-neutral-400 text-[10px]">{{ $myRev->created_at ? $myRev->created_at->format('d/m/Y H:i') : '' }}</span>
                  </div>
                  <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                    @for($i = 1; $i <= 5; $i++)
                      <span>{{ $i <= $myRev->rating ? '★' : '☆' }}</span>
                    @endfor
                  </div>
                </div>
                <p class="text-neutral-700 text-xs leading-relaxed italic">"{{ $myRev->comment }}"</p>
              </div>
            @empty
              <div class="text-center py-12 text-neutral-500">
                <i data-lucide="message-square" class="w-12 h-12 mx-auto text-neutral-300 mb-2 stroke-1"></i>
                <p class="font-medium text-neutral-800">Bạn chưa gửi đánh giá nào</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 5: PROFILE INFO & 2-STEP CONTACT CHANGE -->
      <!-- ========================================================================= -->
      <div id="tab-panel-profile" class="profile-panel hidden space-y-6">
        
        <!-- Basic Info Form -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm">
          <div class="pb-4 mb-6 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Thông Tin Cá Nhân</h3>
            <p class="text-xs text-neutral-500 mt-0.5">Cập nhật họ tên, giới tính, ngày sinh và ảnh đại diện</p>
          </div>

          <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-xs">
            @csrf
            @method('PUT')

            <!-- Avatar Upload -->
            <div class="flex items-center gap-4">
              <img id="avatarPreview" src="{{ asset($user->avatar ?? 'assets/img/team/40x40/58.webp') }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-neutral-300 shadow-sm">
              <div>
                <label class="px-4 py-2 bg-white border border-neutral-300 rounded-lg text-neutral-800 hover:bg-neutral-50 cursor-pointer font-semibold inline-block">
                  <i data-lucide="camera" class="w-3.5 h-3.5 inline mr-1"></i> Tải ảnh đại diện mới
                  <input type="file" name="avatar" accept="image/*" class="hidden" onchange="previewAvatar(this)">
                </label>
                <p class="text-[11px] text-neutral-400 mt-1">Định dạng PNG, JPG, WEBP tối đa 2MB</p>
              </div>
            </div>

            <!-- Full Name -->
            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Họ và Tên *</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
            </div>

            <!-- Gender & DOB -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Giới Tính</label>
                <select name="gender" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
                  <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Nam</option>
                  <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Nữ</option>
                  <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Khác</option>
                </select>
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Ngày Sinh</label>
                <input type="date" name="dob" value="{{ old('dob', $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '') }}" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
              </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors shadow">
              Lưu Thay Đổi Hồ Sơ
            </button>
          </form>
        </div>

        <!-- 2-Step Contact Change with OTP -->
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100 flex items-center justify-between">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Thay Đổi Email &amp; Số Điện Thoại (2-Bước OTP)</h3>
              <p class="text-neutral-500 mt-0.5">Bảo vệ tài khoản an toàn với mã xác thực OTP 6 chữ số</p>
            </div>
            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded font-bold uppercase text-[10px]">BẢO MẬT CAO</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="p-4 bg-brand-50 rounded-xl border border-brand-200 space-y-3">
              <span class="text-neutral-400 font-semibold uppercase text-[10px]">Email hiện tại:</span>
              <p class="font-bold text-neutral-900 text-sm truncate">{{ $user->email ?? 'Chưa liên kết' }}</p>
              <button type="button" onclick="openContactModal('email')" class="w-full py-2 bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-800 font-semibold rounded-lg transition-colors">
                Yêu Cầu Đổi Email Mới
              </button>
            </div>

            <div class="p-4 bg-brand-50 rounded-xl border border-brand-200 space-y-3">
              <span class="text-neutral-400 font-semibold uppercase text-[10px]">Số điện thoại hiện tại:</span>
              <p class="font-bold text-neutral-900 text-sm">{{ $user->phone ?? 'Chưa liên kết' }}</p>
              <button type="button" onclick="openContactModal('phone')" class="w-full py-2 bg-white border border-neutral-300 hover:border-neutral-900 text-neutral-800 font-semibold rounded-lg transition-colors">
                Yêu Cầu Đổi Số Điện Thoại
              </button>
            </div>
          </div>
        </div>

      </div>

      <!-- ========================================================================= -->
      <!-- TAB 6: BANK ACCOUNT -->
      <!-- ========================================================================= -->
      <div id="tab-panel-bank" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Tài Khoản Ngân Hàng Nhận Hoàn Tiền</h3>
            <p class="text-neutral-500 mt-0.5">Dùng để nhận tiền hoàn trả khi thực hiện đổi trả sản phẩm tại xưởng may</p>
          </div>

          <form action="{{ route('client.profile.bank') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Ngân Hàng (NAPAS / VietQR) *</label>
              <select name="bank_name" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
                <option value="" disabled {{ empty($user->bank_name) ? 'selected' : '' }}>-- Click chọn ngân hàng của bạn --</option>
                @foreach($vietnamBanks as $groupName => $bankGroup)
                  <optgroup label="{{ $groupName }}">
                    @foreach($bankGroup as $b)
                      <option value="{{ $b['short_name'] }}" {{ (old('bank_name', $user->bank_name) === $b['short_name'] || old('bank_name', $user->bank_name) === $b['full_name']) ? 'selected' : '' }}>
                        {{ $b['full_name'] }}
                      </option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Số Tài Khoản *</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" required placeholder="Ví dụ: 0987654321..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
              </div>
              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Tên Chủ Tài Khoản (IN HOA) *</label>
                <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $user->bank_account_name) }}" required placeholder="NGUYEN XUAN BAC" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs uppercase font-mono text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
              </div>
            </div>

            <button type="submit" class="px-6 py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors shadow">
              Cập Nhật Tài Khoản Ngân Hàng
            </button>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 7: PASSWORD -->
      <!-- ========================================================================= -->
      <div id="tab-panel-password" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100">
            <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Bảo Mật &amp; Đổi Mật Khẩu</h3>
            <p class="text-neutral-500 mt-0.5">Yêu cầu tối thiểu 8 ký tự để bảo vệ tài khoản an toàn</p>
          </div>

          <form action="{{ route('client.profile.password') }}" method="POST" class="space-y-4 max-w-lg">
            @csrf
            @method('PUT')

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Mật Khẩu Hiện Tại *</label>
              <input type="password" name="current_password" required placeholder="Nhập mật khẩu cũ..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
            </div>

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Mật Khẩu Mới *</label>
              <input type="password" name="password" id="new_profile_pass" required placeholder="Tối thiểu 8 ký tự..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
            </div>

            <div>
              <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Xác Nhận Mật Khẩu Mới *</label>
              <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu mới..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg px-3.5 py-2.5 text-xs text-neutral-900 focus:outline-none focus:border-neutral-950 focus:bg-white transition-colors">
            </div>

            <div class="pt-2">
              <label class="flex items-start gap-2.5 cursor-pointer text-neutral-700">
                <input type="checkbox" name="revoke_other_sessions" value="1" checked class="mt-0.5 w-4 h-4 rounded border-neutral-300 text-neutral-900 focus:ring-neutral-900">
                <span><strong>Đăng xuất khỏi tất cả thiết bị khác</strong></span>
              </label>
            </div>

            <button type="submit" class="px-6 py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors shadow">
              Xác Nhận Đổi Mật Khẩu
            </button>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 8: ADDRESS BOOK -->
      <!-- ========================================================================= -->
      <div id="tab-panel-addresses" class="profile-panel hidden space-y-6">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="flex justify-between items-center pb-4 mb-6 border-b border-neutral-100">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Sổ Địa Chỉ Giao Hàng</h3>
              <p class="text-neutral-500 mt-0.5">Quản lý các địa chỉ nhận hàng để thanh toán thuận tiện</p>
            </div>
            <button type="button" onclick="toggleAddAddressForm()" class="px-4 py-2 bg-neutral-950 text-white font-semibold rounded-lg hover:bg-neutral-800 transition-colors flex items-center gap-1.5">
              <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm Địa Chỉ
            </button>
          </div>

          <!-- Add Address Form -->
          <div id="addAddressFormBox" class="hidden p-5 bg-brand-50 rounded-xl border border-brand-200 mb-6 animate-fade-in">
            <h4 class="font-bold text-neutral-900 uppercase tracking-wider text-[11px] mb-4">Thêm Địa Chỉ Nhận Hàng Mới</h4>
            <form action="{{ route('client.profile.address.store') }}" method="POST" class="space-y-4">
              @csrf
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1">Tên Người Nhận *</label>
                  <input type="text" name="receiver_name" required value="{{ old('receiver_name', $user->name) }}" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
                </div>
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1">Số Điện Thoại *</label>
                  <input type="tel" name="receiver_phone" required value="{{ old('receiver_phone', $user->phone) }}" placeholder="0987654321" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
                </div>
              </div>

              <div>
                <label class="block font-semibold uppercase text-neutral-700 mb-1">Địa Chỉ Cụ Thể (Số nhà, Tên đường) *</label>
                <input type="text" name="detailed_address" required placeholder="Ví dụ: 88 Lê Lợi..." class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1">Tỉnh/Thành Phố</label>
                  <input type="text" name="province_name" value="Hà Nội" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
                </div>
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1">Quận/Huyện</label>
                  <input type="text" name="district_name" value="Cầu Giấy" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
                </div>
                <div>
                  <label class="block font-semibold uppercase text-neutral-700 mb-1">Phường/Xã</label>
                  <input type="text" name="ward_name" value="Dịch Vọng" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
                </div>
              </div>

              <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                  <input type="checkbox" name="is_default" value="1" checked class="w-4 h-4 rounded text-neutral-900 focus:ring-neutral-900">
                  <span>Đặt làm địa chỉ nhận hàng mặc định</span>
                </label>
                <div class="flex gap-2">
                  <button type="button" onclick="toggleAddAddressForm()" class="px-4 py-2 border border-neutral-300 text-neutral-700 rounded-lg hover:bg-neutral-100">Hủy</button>
                  <button type="submit" class="px-5 py-2 bg-neutral-950 text-white rounded-lg hover:bg-neutral-800">Lưu Địa Chỉ</button>
                </div>
              </div>
            </form>
          </div>

          <!-- Address Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($addresses as $addr)
              <div class="p-4 rounded-xl border {{ $addr->is_default ? 'border-neutral-950 bg-neutral-50/70' : 'border-neutral-200 bg-white' }} space-y-2 flex flex-col justify-between">
                <div>
                  <div class="flex justify-between items-start mb-1">
                    <span class="font-bold text-neutral-900 text-sm">{{ $addr->receiver_name }}</span>
                    @if($addr->is_default)
                      <span class="px-2 py-0.5 bg-neutral-900 text-white rounded text-[10px] font-bold">MẶC ĐỊNH</span>
                    @endif
                  </div>
                  <span class="text-neutral-500 block mb-1">{{ $addr->receiver_phone }}</span>
                  <p class="text-neutral-700 leading-relaxed">{{ $addr->detail_address }}, {{ $addr->ward_name }}, {{ $addr->district_name }}, {{ $addr->province_name }}</p>
                </div>

                <div class="pt-3 border-t border-neutral-200 flex justify-end gap-2">
                  @if(!$addr->is_default)
                    <form action="{{ route('client.profile.address.default', $addr->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <button type="submit" class="text-neutral-600 hover:text-black font-semibold text-[11px]">Đặt Mặc Định</button>
                    </form>
                  @endif
                  <form action="{{ route('client.profile.address.destroy', $addr->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa địa chỉ này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold text-[11px] ml-2">Xóa</button>
                  </form>
                </div>
              </div>
            @empty
              <div class="col-span-2 text-center py-8 text-neutral-400">
                Chưa có địa chỉ nào trong sổ tay.
              </div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 9: VIP & PRIVILEGES -->
      <!-- ========================================================================= -->
      <div id="tab-panel-vip" class="profile-panel hidden space-y-4">
        <div class="bg-neutral-950 text-white p-8 rounded-2xl shadow-xl relative overflow-hidden">
          <span class="text-amber-400 text-xs tracking-[0.4em] uppercase font-semibold block mb-2">ATELIER PRIVÉ 2026</span>
          <h3 class="font-serif-luxury text-3xl font-light mb-3">Đặc Quyền Hội Viên</h3>
          <p class="text-xs text-neutral-400 font-light leading-relaxed max-w-xl mb-6">
            Với mỗi 100.000₫ mua sắm tại BeeStyle, bạn tích lũy được 10 điểm thưởng. Điểm thưởng có thể quy đổi trực tiếp thành mã giảm giá hoặc nhận vé mời riêng tại các sự kiện Haute Couture.
          </p>

          <!-- VIP Progress Bar -->
          <div class="p-4 bg-neutral-900 rounded-xl border border-neutral-800 mb-6 space-y-2">
            <div class="flex justify-between items-center text-xs">
              <span class="text-neutral-300">Cấp bậc hiện tại: <strong class="text-amber-400">{{ $tierName }}</strong></span>
              <span class="text-neutral-400 text-[11px]">Mục tiêu tiếp theo: <strong class="text-white">{{ $nextTierName }}</strong></span>
            </div>
            <div class="w-full bg-neutral-800 rounded-full h-2 overflow-hidden">
              <div class="bg-amber-400 h-full rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%;"></div>
            </div>
            <div class="flex justify-between text-[11px] text-neutral-400">
              <span>Đã chi tiêu: {{ number_format($totalSpent, 0, ',', '.') }}₫</span>
              @if($neededMore > 0)
                <span>Cần thêm: {{ number_format($neededMore, 0, ',', '.') }}₫</span>
              @else
                <span class="text-amber-400 font-semibold">Đã đạt hạng cao nhất</span>
              @endif
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-neutral-900 rounded-xl border border-neutral-800">
              <span class="font-serif-luxury text-2xl text-amber-400 font-bold block">{{ number_format($user->points ?? 150) }}</span>
              <span class="text-[10px] uppercase text-neutral-400 tracking-wider">Điểm Tích Lũy</span>
            </div>
            <div class="p-4 bg-neutral-900 rounded-xl border border-neutral-800">
              <span class="font-serif-luxury text-2xl text-white font-bold block">15%</span>
              <span class="text-[10px] uppercase text-neutral-400 tracking-wider">Chiết Khấu VIP</span>
            </div>
            <div class="p-4 bg-neutral-900 rounded-xl border border-neutral-800">
              <span class="font-serif-luxury text-2xl text-white font-bold block">0₫</span>
              <span class="text-[10px] uppercase text-neutral-400 tracking-wider">Freeship Vĩnh Viễn</span>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</main>

<!-- ========================================================================= -->
<!-- MODALS -->
<!-- ========================================================================= -->

<!-- 1. MODAL ĐỔI TRẢ (RMA) -->
<div id="returnOrderModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-lg w-full rounded-2xl p-6 sm:p-8 shadow-2xl border border-neutral-200 animate-fade-in text-xs max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <div>
        <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Yêu Cầu Đổi Trả / Hoàn Tiền (RMA)</h3>
        <p class="text-[11px] text-neutral-500" id="returnModalOrderCode">Đơn hàng #BS-000</p>
      </div>
      <button onclick="closeReturnModal()" class="text-neutral-400 hover:text-black text-lg">&times;</button>
    </div>

    <form id="returnOrderForm" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf

      <!-- Khối Chọn Sản Phẩm Cần Đổi Trả -->
      <div>
        <div class="flex justify-between items-center mb-1.5">
          <label class="block font-semibold uppercase text-neutral-700">1. Chọn Sản Phẩm Cần Đổi Trả / Hoàn Tiền *</label>
          <span id="returnSelectedItemBadge" class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">Toàn bộ đơn hàng</span>
        </div>
        <div id="returnOrderItemsList" class="space-y-2 max-h-52 overflow-y-auto pr-1 border border-neutral-200 rounded-xl p-2 bg-neutral-50/60">
          <!-- Populated dynamically by JS -->
        </div>
      </div>
      
      <!-- Hình thức mong muốn -->
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1.5">2. Hình Thức Mong Muốn *</label>
        <div class="grid grid-cols-2 gap-2">
          <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer bg-neutral-50 hover:bg-neutral-100 transition-colors">
            <input type="radio" name="type" value="return_refund" checked onchange="handleProfileReturnTypeChange(this.value)" class="text-neutral-900">
            <span><strong>Trả Hàng &amp; Hoàn Tiền</strong></span>
          </label>
          <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer bg-neutral-50 hover:bg-neutral-100 transition-colors">
            <input type="radio" name="type" value="exchange" onchange="handleProfileReturnTypeChange(this.value)" class="text-neutral-900">
            <span><strong>Đổi Size / Đổi Màu</strong></span>
          </label>
        </div>
      </div>

      <!-- Tùy chọn đổi size / màu nếu chọn Đổi hàng -->
      <div id="profileExchangeFields" class="hidden p-3.5 bg-gradient-to-br from-sky-50/90 to-indigo-50/50 rounded-xl border border-sky-200 space-y-3 shadow-2xs">
        <div class="flex items-center justify-between pb-2 border-b border-sky-200/80">
          <span class="font-bold text-sky-950 uppercase text-xs flex items-center gap-1.5">
            <i data-lucide="refresh-cw" class="w-4 h-4 text-sky-600"></i>
            <span>Yêu Cầu Đổi Size / Đổi Màu Cụ Thể</span>
          </span>
          <span class="text-[10px] bg-sky-200/80 text-sky-900 font-bold px-2 py-0.5 rounded-full">
            Đổi Miễn Phí Tận Nhà 7 Ngày
          </span>
        </div>

        <!-- Khối hiển thị tóm tắt sản phẩm đang chọn đổi -->
        <div id="profileExchangeProductSummary" class="p-2.5 bg-white rounded-xl border border-sky-200 flex items-center gap-3 shadow-2xs">
          <img id="exchangeSummaryThumb" src="https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=200&auto=format&fit=crop" alt="Thumbnail" class="w-12 h-14 object-cover rounded-lg border border-neutral-200 shrink-0">
          <div class="min-w-0 flex-1">
            <p id="exchangeSummaryName" class="font-bold text-xs text-neutral-900 truncate">Chọn sản phẩm cần đổi...</p>
            <div class="flex items-center gap-1.5 mt-1 flex-wrap text-[10px]">
              <span id="exchangeSummaryCurrentColor" class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 text-neutral-800 rounded font-medium">
                Màu hiện tại: Đang tải...
              </span>
              <span id="exchangeSummaryCurrentSize" class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 text-neutral-800 rounded font-medium">
                Size hiện tại: Đang tải...
              </span>
            </div>
          </div>
        </div>

        <!-- 1. CHỌN MÀU SẮC MỚI (Liên kết trực tiếp với sản phẩm đơn hàng đã mua) -->
        <div class="space-y-1.5 pt-1">
          <div class="flex justify-between items-center">
            <label class="block text-[11px] font-bold text-neutral-800 flex items-center gap-1">
              <span>Màu Sắc Mới Mong Muốn <span class="text-rose-600">*</span></span>
            </label>
            <span id="exchangeColorActiveBadge" class="text-[10px] font-semibold text-sky-800 bg-sky-100 px-2 py-0.5 rounded-full">Đang tải màu...</span>
          </div>

          <!-- Color Swatches: Danh sách nút màu sắc chuẩn (Click chọn ngay) -->
          <div>
            <span class="text-[10px] text-neutral-500 block mb-1">Click vào màu bạn mong muốn đổi:</span>
            <div id="profileExchangeColorSwatches" class="flex flex-wrap gap-1.5">
              <!-- Populated dynamically by JS -->
            </div>
          </div>

          <!-- Sổ danh sách Màu sắc chuẩn (Dropdown select đồng bộ) -->
          <div class="pt-0.5">
            <select name="exchange_color" id="profileExchangeColorSelect" onchange="handleExchangeColorSelectChange(this.value)" class="w-full bg-white border border-neutral-300 rounded-lg p-2 text-xs font-semibold text-neutral-900 focus:outline-none focus:border-sky-600 focus:ring-1 focus:ring-sky-600 shadow-2xs">
              <!-- Populated dynamically by JS -->
            </select>
          </div>
        </div>

        <!-- 2. CHỌN SIZE MỚI (Liên kết trực tiếp với sản phẩm đơn hàng đã mua) -->
        <div class="space-y-1.5 pt-1">
          <div class="flex justify-between items-center">
            <label class="block text-[11px] font-bold text-neutral-800 flex items-center gap-1">
              <span>Kích Cỡ (Size) Mới Mong Muốn <span class="text-rose-600">*</span></span>
            </label>
            <span id="exchangeSizeActiveBadge" class="text-[10px] font-semibold text-sky-800 bg-sky-100 px-2 py-0.5 rounded-full">Đang tải size...</span>
          </div>

          <!-- Size Swatches: Danh sách nút size chuẩn (Click chọn ngay) -->
          <div>
            <span class="text-[10px] text-neutral-500 block mb-1">Click vào size bạn mong muốn đổi:</span>
            <div id="profileExchangeSizeSwatches" class="flex flex-wrap gap-1.5">
              <!-- Populated dynamically by JS -->
            </div>
          </div>

          <!-- Sổ danh sách Size chuẩn (Dropdown select đồng bộ) -->
          <div class="pt-0.5">
            <select name="exchange_size" id="profileExchangeSizeSelect" onchange="handleExchangeSizeSelectChange(this.value)" class="w-full bg-white border border-neutral-300 rounded-lg p-2 text-xs font-semibold text-neutral-900 focus:outline-none focus:border-sky-600 focus:ring-1 focus:ring-sky-600 shadow-2xs">
              <!-- Populated dynamically by JS -->
            </select>
          </div>
        </div>

        <div class="p-2.5 bg-sky-100/70 rounded-xl flex items-start gap-2 text-[10px] text-sky-900 border border-sky-200">
          <i data-lucide="truck" class="w-3.5 h-3.5 text-sky-700 shrink-0 mt-0.5"></i>
          <span>Shipper BeeStyle sẽ mang trang phục mới đúng màu &amp; size đến tận nhà giao đồng thời thu hồi sản phẩm cũ. Quý khách vui lòng giữ nguyên tem mác.</span>
        </div>
      </div>

      <!-- Lý do đổi trả -->
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">3. Lý Do Đổi Trả *</label>
        <select name="reason" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
          <option value="" disabled selected>-- Chọn lý do đổi trả --</option>
          <option value="Mặc không vừa kích cỡ (Yêu cầu đổi sang size khác)">Mặc không vừa kích cỡ (Yêu cầu đổi size)</option>
          <option value="Sản phẩm bị lỗi vải, rách hoặc bung chỉ từ xưởng">Sản phẩm bị lỗi chỉ/vải từ xưởng</option>
          <option value="Giao sai mẫu, sai màu hoặc sai kích thước">Giao sai mẫu hoặc sai màu</option>
          <option value="Sản phẩm không đúng với hình ảnh và mô tả">Sản phẩm không giống mô tả</option>
          <option value="Lý do khác">Lý do khác</option>
        </select>
      </div>

      <!-- Mô tả chi tiết -->
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">4. Ghi Chú Chi Tiết (Tùy chọn)</label>
        <textarea name="customer_notes" rows="2" placeholder="Ghi chú thêm về tình trạng sản phẩm, yêu cầu chi tiết..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950"></textarea>
      </div>

      <!-- BẮT BUỘC: Upload Hình Ảnh Minh Chứng -->
      <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
        <div class="flex items-center justify-between">
          <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
            <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
            <span>5. Ảnh Minh Chứng Sản Phẩm / Tem Mác <span class="text-rose-600">*</span></span>
          </label>
          <span class="text-[10px] text-amber-800 font-semibold">1 - 5 ảnh (Tối đa 8MB/ảnh)</span>
        </div>
        <p class="text-[11px] text-neutral-500 leading-tight">
          Vui lòng chụp rõ tem mác Atelier, toàn cảnh sản phẩm và vị trí lỗi (nếu có).
        </p>
        <input type="file" id="returnImageProofsInput" name="image_proofs[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp" required onchange="handleReturnImagesPreview(this)" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-900 file:text-white hover:file:bg-neutral-800 cursor-pointer">
        <div id="returnImagesPreviewList" class="flex gap-2 flex-wrap empty:hidden pt-1"></div>
      </div>

      <!-- TÙY CHỌN: Upload Video Clip Unbox -->
      <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 space-y-2">
        <div class="flex items-center justify-between">
          <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
            <i data-lucide="video" class="w-4 h-4 text-neutral-700"></i>
            <span>6. Video Clip Unbox Mở Hộp (Tùy chọn)</span>
          </label>
          <span class="text-[10px] text-neutral-500">Tối đa 50MB (MP4, MOV)</span>
        </div>
        <input type="file" id="returnVideoUnboxInput" name="video_unbox" accept="video/mp4,video/mov,video/avi,video/webm" onchange="handleReturnVideoPreview(this)" class="w-full text-xs text-neutral-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-neutral-800 file:text-white hover:file:bg-neutral-700 cursor-pointer">
        <div id="returnVideoPreviewBox" class="hidden p-2 bg-neutral-900 text-white rounded-lg flex items-center justify-between text-xs">
          <span id="returnVideoPreviewName" class="truncate max-w-[260px] font-mono text-[11px]">video.mp4</span>
          <button type="button" onclick="removeReturnVideo()" class="text-rose-400 hover:text-white font-bold ml-2">Xóa ✕</button>
        </div>
      </div>

      <!-- Thông tin ngân hàng nhận tiền hoàn (Ẩn nếu chọn Đổi hàng) -->
      <div id="profileBankFields" class="p-3.5 bg-amber-50/70 rounded-xl border border-amber-300 space-y-3 shadow-2xs">
        <div>
          <div class="flex items-center justify-between">
            <span class="font-bold text-neutral-900 uppercase text-xs flex items-center gap-1.5">
              <i data-lucide="landmark" class="w-4 h-4 text-amber-700"></i>
              <span>7. Thông Tin Nhận Tiền Hoàn: <span class="text-rose-600">*</span></span>
            </span>
            <span class="text-[10px] text-amber-900 font-semibold bg-amber-100 px-2 py-0.5 rounded-full">NAPAS 24/7</span>
          </div>
          <p class="text-[11px] text-neutral-600 mt-1">
            Số tiền hoàn sẽ được chuyển khoản trực tiếp vào tài khoản ngân hàng của bạn sau khi sản phẩm được kiểm định hợp lệ.
          </p>
        </div>

        <!-- 1. CHỌN NGÂN HÀNG (Sổ đầy đủ tất cả ngân hàng, click chọn ngay) -->
        <div>
          <label class="block text-[11px] font-bold text-neutral-800 mb-1 flex items-center justify-between">
            <span>Ngân Hàng Thụ Hưởng <span class="text-rose-600">*</span></span>
            <span id="selectedBankBadge" class="text-[10px] font-semibold text-neutral-600 bg-neutral-100 px-2 py-0.5 rounded-full">Chưa chọn ngân hàng</span>
          </label>

          <!-- Top ngân hàng phổ biến (Click chọn nhanh) -->
          <div class="mb-2">
            <span class="text-[10px] text-neutral-500 block mb-1">Ngân hàng phổ biến (Click chọn ngay):</span>
            <div id="quickBankList" class="flex flex-wrap gap-1.5">
              <!-- Rendered dynamically by JS or loop -->
              @foreach(($vietnamBanks['Ngân hàng phổ biến nhất'] ?? []) as $qb)
                <button type="button" onclick="selectQuickBank('{{ $qb['short_name'] }}')" data-bank-name="{{ $qb['short_name'] }}" class="quick-bank-btn px-2.5 py-1 text-[11px] font-semibold rounded-lg border border-neutral-200 bg-white hover:border-amber-400 hover:bg-amber-50 text-neutral-800 transition-all cursor-pointer shadow-2xs flex items-center gap-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                  <span>{{ $qb['short_name'] }}</span>
                </button>
              @endforeach
            </div>
          </div>

          <!-- Sổ danh sách tất cả các ngân hàng (Hơn 40 ngân hàng đầy đủ) -->
          <div>
            <select name="bank_name" id="returnBankSelect" onchange="handleBankSelectChange(this.value)" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 shadow-2xs">
              <option value="" disabled selected>-- Click vào đây để chọn ngân hàng thụ hưởng --</option>
              @foreach($vietnamBanks as $groupName => $bankGroup)
                <optgroup label="{{ $groupName }}">
                  @foreach($bankGroup as $b)
                    <option value="{{ $b['short_name'] }}">{{ $b['full_name'] }}</option>
                  @endforeach
                </optgroup>
              @endforeach
            </select>
          </div>
        </div>

        <!-- 2. SỐ TÀI KHOẢN NGÂN HÀNG (Khách hàng tự nhập) -->
        <div>
          <label class="block text-[11px] font-bold text-neutral-800 mb-1">
            Số Tài Khoản Ngân Hàng <span class="text-rose-600">*</span>
          </label>
          <input type="text" inputmode="numeric" name="bank_account_number" id="returnBankAccountNumber" value="" placeholder="Khách hàng tự nhập số tài khoản ngân hàng (chỉ gồm chữ số)" oninput="this.value = this.value.replace(/[^0-9]/g, '')" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs font-mono font-bold tracking-wider text-neutral-900 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 shadow-2xs">
        </div>

        <!-- 3. TÊN TÀI KHOẢN NGÂN HÀNG (Khách hàng tự nhập, KHÔNG NHẬP SẴN) -->
        <div>
          <label class="block text-[11px] font-bold text-neutral-800 mb-1 flex items-center justify-between">
            <span>Tên Chủ Tài Khoản (Người Thụ Hưởng) <span class="text-rose-600">*</span></span>
            <span class="text-[10px] text-neutral-500 font-normal">VIẾT HOA KHÔNG DẤU</span>
          </label>
          <input type="text" name="bank_account_name" id="returnBankAccountName" value="" placeholder="Ví dụ: NGUYEN VAN A (Khách hàng tự nhập)" oninput="this.value = this.value.toUpperCase()" class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs uppercase font-bold text-neutral-900 focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 shadow-2xs">
          <p class="text-[10px] text-neutral-500 mt-1 italic leading-tight">
            * Khách hàng vui lòng tự nhập đúng họ tên chủ tài khoản ngân hàng để chuyển khoản hoàn tiền chính xác.
          </p>
        </div>

        <!-- 4. CHI NHÁNH NGÂN HÀNG (TÙY CHỌN) -->
        <div>
          <label class="block text-[10px] font-semibold text-neutral-600 mb-0.5">
            Chi Nhánh Ngân Hàng (Tùy chọn)
          </label>
          <input type="text" name="bank_branch" id="returnBankBranch" value="" placeholder="Ví dụ: Chi nhánh Ba Đình, Hà Nội..." class="w-full bg-white border border-neutral-300 rounded-lg p-2 text-xs text-neutral-800 focus:outline-none focus:border-neutral-900">
        </div>
      </div>

      <button type="submit" id="btnSubmitProfileReturn" class="w-full py-3 bg-amber-400 hover:bg-amber-500 text-neutral-950 font-bold uppercase tracking-wider rounded-xl transition-colors shadow flex items-center justify-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        <span>Gửi Yêu Cầu Hoàn Tiền / Đổi Trả</span>
      </button>
    </form>
  </div>
</div>

<!-- 2. MODAL HỦY ĐƠN HÀNG (CLIENT) -->
<div id="cancelOrderModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-md w-full rounded-2xl p-6 shadow-2xl border border-neutral-200 animate-fade-in text-xs">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0">
          <i data-lucide="alert-triangle" class="w-4 h-4"></i>
        </div>
        <div>
          <h3 class="font-serif-luxury text-base font-bold text-neutral-900" id="cancelModalOrderCode">Hủy Đơn Hàng #BS-000</h3>
          <p class="text-[10px] text-neutral-500">Xác nhận yêu cầu hủy đơn hàng</p>
        </div>
      </div>
      <button onclick="closeCancelModal()" class="text-neutral-400 hover:text-black text-lg">&times;</button>
    </div>

    <!-- Tóm tắt đơn hàng cần hủy -->
    <div id="cancelModalOrderSummary" class="p-3 bg-neutral-50 rounded-xl border border-neutral-200 mb-3 flex items-center justify-between">
      <div>
        <span class="text-[10px] text-neutral-500 block">Giá trị đơn hàng:</span>
        <strong id="cancelModalOrderAmount" class="font-serif-luxury text-sm text-neutral-900">0₫</strong>
      </div>
      <span id="cancelModalOrderItemsCount" class="text-[10px] bg-neutral-200 text-neutral-700 font-semibold px-2 py-0.5 rounded-full">1 sản phẩm</span>
    </div>

    <form id="cancelOrderForm" method="POST" onsubmit="handleCancelOrderSubmit(this)" class="space-y-3.5">
      @csrf
      <div class="p-3 bg-rose-50 text-rose-800 rounded-xl text-[11px] border border-rose-100 leading-relaxed">
        <i data-lucide="info" class="w-3.5 h-3.5 inline mr-1 text-rose-600"></i>
        Khi xác nhận hủy đơn, hệ thống sẽ <strong>tự động hoàn lại số lượng tồn kho</strong> và <strong>khôi phục mã giảm giá (voucher)</strong> cho bạn.
      </div>

      <div>
        <label class="block font-bold text-neutral-800 uppercase text-[10px] mb-1">
          1. Lý Do Hủy Đơn Hàng <span class="text-rose-600">*</span>
        </label>
        <select name="reason" required class="w-full bg-white border border-neutral-300 rounded-lg p-2.5 text-xs text-neutral-900 font-medium focus:outline-none focus:border-neutral-950 focus:ring-1 focus:ring-neutral-950 shadow-2xs">
          <option value="" disabled selected>-- Chọn lý do bạn muốn hủy đơn --</option>
          <option value="Tôi muốn thay đổi địa chỉ nhận hàng">Tôi muốn thay đổi địa chỉ nhận hàng</option>
          <option value="Tôi muốn thay đổi Size hoặc Màu sắc sản phẩm">Tôi muốn thay đổi Size hoặc Màu sắc sản phẩm</option>
          <option value="Tôi tìm thấy sản phẩm giá tốt hơn ở nơi khác">Tôi tìm thấy sản phẩm giá tốt hơn ở nơi khác</option>
          <option value="Tôi đổi ý, không có nhu cầu mua nữa">Tôi đổi ý, không có nhu cầu mua nữa</option>
          <option value="Đặt nhầm hoặc bị trùng lặp đơn hàng">Đặt nhầm hoặc bị trùng lặp đơn hàng</option>
          <option value="Thời gian giao hàng dự kiến quá lâu">Thời gian giao hàng dự kiến quá lâu</option>
          <option value="Lý do khác">Lý do khác</option>
        </select>
      </div>

      <div>
        <label class="block font-semibold text-neutral-700 text-[10px] mb-1">
          2. Ghi Chú Thêm Chi Tiết (Không bắt buộc)
        </label>
        <textarea name="notes" rows="2" placeholder="Nhập thêm chi tiết nếu có (Ví dụ: Đổi sang áo sơ mi trắng, giao vào tuần sau...)" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 text-xs text-neutral-800 focus:outline-none focus:border-neutral-950 focus:bg-white"></textarea>
      </div>

      <div class="grid grid-cols-2 gap-2 pt-1">
        <button type="button" onclick="closeCancelModal()" class="py-2.5 px-3 border border-neutral-300 bg-white hover:bg-neutral-50 text-neutral-800 font-bold rounded-xl transition-colors text-center text-xs">
          Giữ Lại Đơn Hàng
        </button>
        <button type="submit" id="btnSubmitCancelOrder" class="py-2.5 px-3 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl transition-colors shadow flex items-center justify-center gap-1.5 text-xs">
          <i data-lucide="x-circle" class="w-4 h-4"></i>
          <span>Xác Nhận Hủy Đơn</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- 3. MODAL ĐÁNH GIÁ NHANH -->
<div id="quickReviewModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-md w-full rounded-2xl p-6 sm:p-8 shadow-2xl border border-neutral-200 animate-fade-in text-xs">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <div>
        <h3 class="font-serif-luxury text-xl font-bold text-neutral-900">Đánh Giá Sản Phẩm</h3>
        <p class="text-[11px] text-neutral-500 line-clamp-1" id="quickReviewProductName">Tên sản phẩm</p>
      </div>
      <button onclick="closeQuickReviewModal()" class="text-neutral-400 hover:text-black">&times;</button>
    </div>

    <form id="quickReviewForm" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1.5">Số Sao Đánh Giá *</label>
        <div class="flex items-center gap-2 text-2xl text-neutral-300">
          <input type="hidden" name="rating" id="quickRatingInput" value="5" required>
          @for($i = 1; $i <= 5; $i++)
            <button type="button" onclick="setQuickModalRating({{ $i }})" class="hover:text-amber-400 quick-rating-star text-amber-400" data-star="{{ $i }}">
              ★
            </button>
          @endfor
          <span id="quickRatingLabel" class="text-xs text-neutral-600 font-semibold ml-2">Tuyệt vời (5/5 sao)</span>
        </div>
      </div>

      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">Nội Dung Nhận Xét *</label>
        <textarea name="comment" rows="4" required placeholder="Chia sẻ cảm nhận về form áo, chất liệu vải..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-3 text-xs focus:outline-none focus:border-neutral-950"></textarea>
      </div>

      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">Ảnh Chụp Thực Tế</label>
        <input type="file" name="images[]" multiple accept="image/*" class="text-xs text-neutral-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-xs file:bg-neutral-200">
      </div>

      <button type="submit" class="w-full py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors shadow">
        Gửi Đánh Giá Ngay
      </button>
    </form>
  </div>
</div>

<!-- 4. MODAL TỪ CHỐI NHẬN HÀNG (CHUYỂN HOÀN) -->
<div id="profileRejectOrderModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-md w-full rounded-2xl p-6 shadow-2xl border border-neutral-200 animate-fade-in text-xs">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <h3 class="font-serif-luxury text-xl font-bold text-rose-600" id="rejectModalOrderTitle">Từ Chối Nhận Hàng</h3>
      <button onclick="closeProfileRejectModal()" class="text-neutral-400 hover:text-black">&times;</button>
    </div>

    <form id="profileRejectOrderForm" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div class="p-3 bg-rose-50 text-rose-800 rounded-xl text-[11px]">
        Bưu tá sẽ lập biên bản và chuyển hoàn kiện hàng về kho tổng BeeStyle. Tồn kho và voucher sẽ được tự động hoàn lại.
      </div>
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">Lý Do Không Nhận *</label>
        <select name="reason" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5">
          <option value="" disabled selected>-- Chọn lý do từ chối --</option>
          <option value="Hộp/Thùng hàng bị móp méo, rách vỡ">Hộp/Thùng hàng bị móp méo, rách vỡ</option>
          <option value="Bưu tá không hỗ trợ đồng kiểm tra hàng">Bưu tá không hỗ trợ đồng kiểm</option>
          <option value="Giao sai mẫu mã, sai màu sắc hoặc kích cỡ">Giao sai mẫu mã hoặc kích cỡ</option>
          <option value="Sản phẩm bị lỗi may mặc hoặc hư hỏng">Sản phẩm bị lỗi vải/may mặc</option>
          <option value="Thời gian giao quá trễ, không còn nhu cầu">Giao quá trễ so với dự kiến</option>
          <option value="Lý do khác">Lý do khác</option>
        </select>
      </div>
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">Ghi chú cụ thể</label>
        <textarea name="notes" rows="2" placeholder="Ghi chú chi tiết..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2"></textarea>
      </div>
      <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold uppercase tracking-wider rounded-xl transition-colors shadow">
        Xác Nhận Không Nhận (Chuyển Hoàn)
      </button>
    </form>
  </div>
</div>

<!-- 5. MODAL OTP 2-BƯỚC THAY ĐỔI EMAIL / SĐT -->
<div id="contactOtpModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-md w-full rounded-2xl p-6 sm:p-8 shadow-2xl border border-neutral-200 animate-fade-in text-xs">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <h3 class="font-serif-luxury text-xl font-bold text-neutral-900" id="contactModalTitle">Thay Đổi Thông Tin Liên Hệ</h3>
      <button onclick="closeContactModal()" class="text-neutral-400 hover:text-black">&times;</button>
    </div>

    <!-- Step 1 -->
    <div id="contactStep1">
      <p class="text-neutral-600 mb-4" id="contactStep1Desc">Vui lòng nhập địa chỉ mới để nhận mã xác thực OTP 6 chữ số.</p>
      <form onsubmit="handleRequestContactOtp(event)" class="space-y-4">
        <input type="hidden" id="contactType" value="email">
        <div>
          <label class="block font-semibold uppercase text-neutral-700 mb-1" id="contactInputLabel">Email Mới</label>
          <input type="text" id="contactNewValue" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950">
        </div>
        <button type="submit" id="requestOtpBtn" class="w-full py-3 bg-neutral-950 hover:bg-neutral-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors">
          Gửi Mã Xác Thực OTP
        </button>
      </form>
    </div>

    <!-- Step 2 -->
    <div id="contactStep2" class="hidden">
      <p class="text-neutral-600 mb-4">Mã OTP đã được gửi. Vui lòng nhập đúng 6 chữ số để hoàn tất:</p>
      <form onsubmit="handleConfirmContactOtp(event)" class="space-y-4">
        <div>
          <label class="block font-semibold uppercase text-neutral-700 mb-1">Mã OTP 6 Chữ Số</label>
          <input type="text" id="contactOtpCode" maxlength="6" required placeholder="123456" class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 text-center text-lg font-mono font-bold tracking-widest focus:outline-none focus:border-neutral-950">
        </div>
        <button type="submit" id="confirmOtpBtn" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold uppercase tracking-wider rounded-xl transition-colors">
          Xác Nhận Thay Đổi
        </button>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@php
  $ordersJsonData = $orders->map(function($o) {
    return [
      'id' => $o->id,
      'code' => $o->order_code,
      'total_amount' => $o->total_amount,
      'items' => $o->items->map(function($it) {
        $product = $it->product;
        $variants = ($product && $product->variants) ? $product->variants : collect();

        // 1. Trích xuất màu sắc chuẩn của sản phẩm từ variants hoặc product->colors
        $colors = collect();
        if ($variants->isNotEmpty()) {
            $colors = $variants->groupBy('color')->map(function($vItems, $colorName) {
                $first = $vItems->first();
                return [
                    'name' => (string) $colorName,
                    'color_code' => $first->color_code ?: null,
                    'sizes' => $vItems->pluck('size')->filter()->unique()->values()->all(),
                    'stock' => $vItems->sum('stock'),
                    'in_stock' => $vItems->sum('stock') > 0,
                    'image' => $first->image ? asset($first->image) : null,
                ];
            })->values();
        } elseif ($product && !empty($product->colors) && is_array($product->colors)) {
            $colors = collect($product->colors)->map(function($c) {
                $cName = is_array($c) ? ($c['name'] ?? 'Chuẩn') : $c;
                $cCode = is_array($c) ? ($c['code'] ?? null) : null;
                return [
                    'name' => (string) $cName,
                    'color_code' => $cCode,
                    'sizes' => [],
                    'stock' => 99,
                    'in_stock' => true,
                    'image' => null,
                ];
            });
        }

        // Fallback màu nếu sản phẩm chưa có
        if ($colors->isEmpty() && !empty($it->color)) {
            $colors = collect([[
                'name' => (string) $it->color,
                'color_code' => null,
                'sizes' => !empty($it->size) ? [(string) $it->size] : [],
                'stock' => 99,
                'in_stock' => true,
                'image' => null,
            ]]);
        }

        // 2. Trích xuất kích cỡ chuẩn của sản phẩm từ variants hoặc product->sizes
        $sizes = collect();
        if ($variants->isNotEmpty()) {
            $sizes = $variants->pluck('size')->filter()->unique()->values();
        } elseif ($product && !empty($product->sizes) && is_array($product->sizes)) {
            $sizes = collect($product->sizes);
        }
        if ($sizes->isEmpty() && !empty($it->size)) {
            $sizes = collect([$it->size]);
        }

        return [
          'id' => $it->id,
          'product_id' => $it->product_id,
          'name' => $it->product_name,
          'color' => $it->color ?: 'Chuẩn',
          'size' => $it->size ?: 'M',
          'quantity' => $it->quantity,
          'price' => $it->price,
          'subtotal' => $it->price * $it->quantity,
          'thumbnail' => asset($it->product->primaryImage->image_path ?? $it->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=200&auto=format&fit=crop'),
          'available_colors' => $colors->values()->all(),
          'available_sizes' => $sizes->values()->all(),
        ];
      })
    ];
  });
@endphp
<script>
  const userOrdersData = @json($ordersJsonData);
  let currentActiveOrder = null;
  let currentActiveReturnItem = null;

  // Ánh xạ mã màu HEX thông minh cho các tên màu thời trang tiếng Việt
  function getColorHex(colorName, defaultHex = null) {
    if (defaultHex && defaultHex.startsWith('#') && defaultHex.length >= 4) {
      return defaultHex;
    }
    const map = {
      'đen': '#111827',
      'den': '#111827',
      'trắng': '#ffffff',
      'trang': '#ffffff',
      'xanh navy': '#1e3a8a',
      'xanh than': '#1e3a8a',
      'navy': '#1e3a8a',
      'xanh lam': '#2563eb',
      'xanh dương': '#0284c7',
      'xanh coban': '#1d4ed8',
      'xanh rêu': '#3f6212',
      'rêu': '#3f6212',
      'xanh lá': '#16a34a',
      'xám': '#64748b',
      'xám ghi': '#64748b',
      'ghi': '#64748b',
      'be': '#d4b996',
      'màu be': '#d4b996',
      'kem': '#fef3c7',
      'nâu': '#78350f',
      'nau': '#78350f',
      'đỏ': '#dc2626',
      'đỏ đô': '#881337',
      'vàng': '#f59e0b',
      'hồng': '#ec4899',
      'tím': '#8b5cf6',
      'cam': '#ea580c'
    };
    const key = (colorName || '').trim().toLowerCase();
    return map[key] || '#111827';
  }

  function switchProfileTab(tabName) {
    document.querySelectorAll('.profile-panel').forEach(p => p.classList.add('hidden'));
    document.querySelectorAll('.profile-tab-btn').forEach(b => {
      b.classList.remove('bg-neutral-950', 'text-white', 'font-semibold');
      b.classList.add('text-neutral-700', 'hover:bg-neutral-50', 'font-medium');
      const badge = b.querySelector('.rounded-full');
      if (badge && !badge.classList.contains('bg-rose-100')) {
        badge.className = 'px-2 py-0.5 bg-neutral-100 text-neutral-600 rounded-full text-[10px]';
      }
    });

    const targetPanel = document.getElementById(`tab-panel-${tabName}`);
    const targetBtn = document.getElementById(`tab-btn-${tabName}`);
    if (targetPanel) targetPanel.classList.remove('hidden');
    if (targetBtn) {
      targetBtn.classList.remove('text-neutral-700', 'hover:bg-neutral-50', 'font-medium');
      targetBtn.classList.add('bg-neutral-950', 'text-white', 'font-semibold');
      const badge = targetBtn.querySelector('.rounded-full');
      if (badge) {
        badge.className = 'px-2 py-0.5 bg-neutral-800 text-white rounded-full text-[10px] font-bold';
      }
    }
  }

  // Handle URL query parameter ?tab=...
  document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab');
    if (tab) {
      switchProfileTab(tab);
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });

  // Chọn nhanh ngân hàng
  function selectQuickBank(bankName) {
    const select = document.getElementById('returnBankSelect');
    if (select) {
      select.value = bankName;
      handleBankSelectChange(bankName);
    }
  }

  // Xử lý khi ngân hàng thay đổi
  function handleBankSelectChange(bankName) {
    const badge = document.getElementById('selectedBankBadge');
    if (badge) {
      if (bankName) {
        badge.textContent = `Đã chọn: ${bankName}`;
        badge.className = 'text-[10px] font-bold text-amber-900 bg-amber-200 px-2 py-0.5 rounded-full';
      } else {
        badge.textContent = 'Chưa chọn ngân hàng';
        badge.className = 'text-[10px] font-semibold text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full';
      }
    }

    // Active highlight trên các nút chip chọn nhanh
    document.querySelectorAll('.quick-bank-btn').forEach(btn => {
      if (btn.dataset.bankName === bankName) {
        btn.className = 'quick-bank-btn px-2.5 py-1 text-[11px] font-bold rounded-lg border-2 border-amber-600 bg-amber-100 text-amber-950 transition-all shadow-xs flex items-center gap-1 ring-1 ring-amber-500';
      } else {
        btn.className = 'quick-bank-btn px-2.5 py-1 text-[11px] font-semibold rounded-lg border border-neutral-200 bg-white hover:border-amber-400 hover:bg-amber-50 text-neutral-800 transition-all cursor-pointer shadow-2xs flex items-center gap-1';
      }
    });
  }

  // Chuyển đổi giao diện Đổi Hàng vs Trả Hàng trong Modal Profile
  function handleProfileReturnTypeChange(type) {
    const exBox = document.getElementById('profileExchangeFields');
    const bankBox = document.getElementById('profileBankFields');
    const btn = document.getElementById('btnSubmitProfileReturn');

    const bankSelect = document.getElementById('returnBankSelect');
    const bankAccNum = document.getElementById('returnBankAccountNumber');
    const bankAccName = document.getElementById('returnBankAccountName');

    if (type === 'exchange') {
      if (exBox) exBox.classList.remove('hidden');
      if (bankBox) bankBox.classList.add('hidden');
      if (btn) {
        btn.querySelector('span').textContent = 'Gửi Yêu Cầu Đổi Hàng Mới';
        btn.className = 'w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold uppercase tracking-wider rounded-xl transition-colors shadow flex items-center justify-center gap-2';
      }

      // Xóa bắt buộc các trường ngân hàng khi đổi hàng
      if (bankSelect) bankSelect.removeAttribute('required');
      if (bankAccNum) bankAccNum.removeAttribute('required');
      if (bankAccName) bankAccName.removeAttribute('required');

      // Khi chọn đổi size/màu, bắt buộc liên kết với 1 sản phẩm cụ thể
      const checkedRadio = document.querySelector('input[name="order_item_id"]:checked');
      if (!checkedRadio || !checkedRadio.value) {
        const firstProductRadio = document.querySelector('#returnOrderItemsList input[name="order_item_id"][value]:not([value=""])');
        if (firstProductRadio) {
          firstProductRadio.checked = true;
          firstProductRadio.dispatchEvent(new Event('change'));
        }
      } else {
        renderExchangeVariants(checkedRadio.value);
      }
    } else {
      if (exBox) exBox.classList.add('hidden');
      if (bankBox) bankBox.classList.remove('hidden');
      if (btn) {
        btn.querySelector('span').textContent = 'Gửi Yêu Cầu Hoàn Tiền / Đổi Trả';
        btn.className = 'w-full py-3 bg-amber-400 hover:bg-amber-500 text-neutral-950 font-bold uppercase tracking-wider rounded-xl transition-colors shadow flex items-center justify-center gap-2';
      }

      // Thêm bắt buộc các trường ngân hàng khi trả hàng hoàn tiền
      if (bankSelect) bankSelect.setAttribute('required', 'required');
      if (bankAccNum) bankAccNum.setAttribute('required', 'required');
      if (bankAccName) bankAccName.setAttribute('required', 'required');
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  // Render biến thể Màu sắc & Size của sản phẩm được chọn
  function renderExchangeVariants(orderItemId) {
    if (!currentActiveOrder) return;

    let item = null;
    if (orderItemId) {
      item = currentActiveOrder.items.find(it => it.id == orderItemId);
    }
    if (!item && currentActiveOrder.items && currentActiveOrder.items.length > 0) {
      item = currentActiveOrder.items[0];
    }
    if (!item) return;

    currentActiveReturnItem = item;

    // 1. Cập nhật khối tóm tắt sản phẩm đang chọn đổi
    const thumbEl = document.getElementById('exchangeSummaryThumb');
    const nameEl = document.getElementById('exchangeSummaryName');
    const colorEl = document.getElementById('exchangeSummaryCurrentColor');
    const sizeEl = document.getElementById('exchangeSummaryCurrentSize');

    if (thumbEl) thumbEl.src = item.thumbnail;
    if (nameEl) nameEl.textContent = item.name;
    if (colorEl) colorEl.textContent = `Màu hiện tại: ${item.color || 'Chuẩn'}`;
    if (sizeEl) sizeEl.textContent = `Size hiện tại: ${item.size || 'M'}`;

    // 2. Render Màu sắc mới (Liên kết chính xác với sản phẩm khách mua)
    const colorSwatchesContainer = document.getElementById('profileExchangeColorSwatches');
    const colorSelect = document.getElementById('profileExchangeColorSelect');
    const colorBadge = document.getElementById('exchangeColorActiveBadge');

    let availableColors = item.available_colors || [];
    if (availableColors.length === 0 && item.color) {
      availableColors = [{
        name: item.color,
        color_code: getColorHex(item.color),
        sizes: [item.size],
        in_stock: true
      }];
    }

    if (colorSwatchesContainer && colorSelect) {
      colorSwatchesContainer.innerHTML = '';
      colorSelect.innerHTML = '<option value="" disabled>-- Click chọn màu sắc bạn muốn đổi --</option>';

      // Chọn mặc định là màu hiện tại của sản phẩm
      let selectedColorName = item.color || (availableColors[0] ? availableColors[0].name : '');

      availableColors.forEach(c => {
        const isCurrentPurchased = (c.name.trim().toLowerCase() === (item.color || '').trim().toLowerCase());
        const isSelected = (c.name.trim().toLowerCase() === selectedColorName.trim().toLowerCase());
        const hex = getColorHex(c.name, c.color_code);

        // Nút Swatch chọn màu trực quan (Chỉ việc click)
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.dataset.colorName = c.name;
        btn.className = `exchange-color-btn px-3 py-1.5 rounded-lg border text-xs flex items-center gap-2 transition-all cursor-pointer ${isSelected ? 'border-sky-600 bg-sky-100/90 text-sky-950 font-bold ring-2 ring-sky-500 shadow-xs' : 'border-neutral-200 bg-white hover:border-sky-300 text-neutral-800'}`;
        btn.onclick = () => selectExchangeColor(c.name);

        const isWhite = hex.toLowerCase() === '#ffffff' || hex.toLowerCase() === '#fff';
        btn.innerHTML = `
          <span class="w-4 h-4 rounded-full shrink-0 ${isWhite ? 'border border-neutral-300' : 'shadow-2xs'}" style="background-color: ${hex}"></span>
          <span>${c.name}</span>
          ${isCurrentPurchased ? '<span class="text-[9px] bg-neutral-200 text-neutral-700 px-1.5 py-0.2 rounded font-normal">(Đang mua)</span>' : ''}
        `;
        colorSwatchesContainer.appendChild(btn);

        // Option trong Dropdown
        const opt = document.createElement('option');
        opt.value = c.name;
        opt.textContent = isCurrentPurchased ? `${c.name} (Giữ nguyên màu đang mua)` : `${c.name} (Màu mới)`;
        if (isSelected) opt.selected = true;
        colorSelect.appendChild(opt);
      });

      if (colorBadge) {
        colorBadge.textContent = selectedColorName ? `Đã chọn: ${selectedColorName}` : 'Chưa chọn';
        colorBadge.className = selectedColorName ? 'text-[10px] font-bold text-sky-900 bg-sky-200 px-2 py-0.5 rounded-full' : 'text-[10px] font-semibold text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full';
      }
    }

    // 3. Render Size mới tương ứng
    renderExchangeSizes(item, item.color || '');
  }

  function renderExchangeSizes(item, selectedColorName) {
    const sizeSwatchesContainer = document.getElementById('profileExchangeSizeSwatches');
    const sizeSelect = document.getElementById('profileExchangeSizeSelect');
    const sizeBadge = document.getElementById('exchangeSizeActiveBadge');

    if (!sizeSwatchesContainer || !sizeSelect) return;

    let availableSizes = item.available_sizes || [];
    if (selectedColorName && item.available_colors) {
      const matched = item.available_colors.find(c => c.name.trim().toLowerCase() === selectedColorName.trim().toLowerCase());
      if (matched && matched.sizes && matched.sizes.length > 0) {
        availableSizes = matched.sizes;
      }
    }
    if (availableSizes.length === 0 && item.size) {
      availableSizes = [item.size];
    }

    sizeSwatchesContainer.innerHTML = '';
    sizeSelect.innerHTML = '<option value="" disabled>-- Click chọn kích cỡ bạn muốn đổi --</option>';

    let selectedSizeName = item.size || (availableSizes[0] || '');

    availableSizes.forEach(s => {
      const isCurrentPurchased = (String(s).trim().toLowerCase() === String(item.size || '').trim().toLowerCase());
      const isSelected = (String(s).trim().toLowerCase() === String(selectedSizeName).trim().toLowerCase());

      const btn = document.createElement('button');
      btn.type = 'button';
      btn.dataset.sizeName = s;
      btn.className = `exchange-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 ${isSelected ? 'border-sky-600 bg-sky-100/90 text-sky-950 font-bold ring-2 ring-sky-500 shadow-xs' : 'border-neutral-200 bg-white hover:border-sky-300 text-neutral-800'}`;
      btn.onclick = () => selectExchangeSize(s);
      btn.innerHTML = `
        <span>Size ${s}</span>
        ${isCurrentPurchased ? '<span class="text-[9px] bg-neutral-200 text-neutral-700 px-1 py-0.2 rounded font-normal">(Đang mặc)</span>' : ''}
      `;
      sizeSwatchesContainer.appendChild(btn);

      const opt = document.createElement('option');
      opt.value = s;
      opt.textContent = isCurrentPurchased ? `Size ${s} (Giữ nguyên kích cỡ đang dùng)` : `Size ${s} (Đổi sang size này)`;
      if (isSelected) opt.selected = true;
      sizeSelect.appendChild(opt);
    });

    if (sizeBadge) {
      sizeBadge.textContent = selectedSizeName ? `Đã chọn: Size ${selectedSizeName}` : 'Chưa chọn';
      sizeBadge.className = selectedSizeName ? 'text-[10px] font-bold text-sky-900 bg-sky-200 px-2 py-0.5 rounded-full' : 'text-[10px] font-semibold text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full';
    }
  }

  function selectExchangeColor(colorName) {
    const select = document.getElementById('profileExchangeColorSelect');
    if (select) select.value = colorName;
    handleExchangeColorSelectChange(colorName);
  }

  function handleExchangeColorSelectChange(colorName) {
    const badge = document.getElementById('exchangeColorActiveBadge');
    if (badge) {
      badge.textContent = colorName ? `Đã chọn: ${colorName}` : 'Chưa chọn';
      badge.className = colorName ? 'text-[10px] font-bold text-sky-900 bg-sky-200 px-2 py-0.5 rounded-full' : 'text-[10px] font-semibold text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full';
    }

    document.querySelectorAll('.exchange-color-btn').forEach(btn => {
      if (btn.dataset.colorName === colorName) {
        btn.className = 'exchange-color-btn px-3 py-1.5 rounded-lg border text-xs flex items-center gap-2 transition-all cursor-pointer border-sky-600 bg-sky-100/90 text-sky-950 font-bold ring-2 ring-sky-500 shadow-xs';
      } else {
        btn.className = 'exchange-color-btn px-3 py-1.5 rounded-lg border text-xs flex items-center gap-2 transition-all cursor-pointer border-neutral-200 bg-white hover:border-sky-300 text-neutral-800';
      }
    });

    if (currentActiveReturnItem) {
      renderExchangeSizes(currentActiveReturnItem, colorName);
    }
  }

  function selectExchangeSize(sizeName) {
    const select = document.getElementById('profileExchangeSizeSelect');
    if (select) select.value = sizeName;
    handleExchangeSizeSelectChange(sizeName);
  }

  function handleExchangeSizeSelectChange(sizeName) {
    const badge = document.getElementById('exchangeSizeActiveBadge');
    if (badge) {
      badge.textContent = sizeName ? `Đã chọn: Size ${sizeName}` : 'Chưa chọn';
      badge.className = sizeName ? 'text-[10px] font-bold text-sky-900 bg-sky-200 px-2 py-0.5 rounded-full' : 'text-[10px] font-semibold text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full';
    }

    document.querySelectorAll('.exchange-size-btn').forEach(btn => {
      if (btn.dataset.sizeName === String(sizeName)) {
        btn.className = 'exchange-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 border-sky-600 bg-sky-100/90 text-sky-950 font-bold ring-2 ring-sky-500 shadow-xs';
      } else {
        btn.className = 'exchange-size-btn px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all cursor-pointer flex items-center gap-1.5 border-neutral-200 bg-white hover:border-sky-300 text-neutral-800';
      }
    });
  }

  function toggleAddAddressForm() {
    document.getElementById('addAddressFormBox')?.classList.toggle('hidden');
  }

  function previewAvatar(input) {
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('avatarPreview').src = e.target.result;
        document.getElementById('sidebarAvatarPreview').src = e.target.result;
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Lightbox xem ảnh review phóng to
  function openReviewImageLightbox(imgUrl) {
    let modal = document.getElementById('profileLightboxModal');
    let img = document.getElementById('profileLightboxImg');
    if (!modal) {
      modal = document.createElement('div');
      modal.id = 'profileLightboxModal';
      modal.className = 'fixed inset-0 z-50 bg-black/80 backdrop-blur-xs flex items-center justify-center p-4 cursor-pointer';
      modal.onclick = function() { modal.classList.add('hidden'); };
      modal.innerHTML = `
        <div class="relative max-w-3xl max-h-[85vh]">
          <img id="profileLightboxImg" src="${imgUrl}" class="max-h-[80vh] w-auto rounded-xl object-contain shadow-2xl">
        </div>
      `;
      document.body.appendChild(modal);
    } else {
      if (img) img.src = imgUrl;
      modal.classList.remove('hidden');
    }
  }

  // RMA Return Modal
  let selectedReturnFiles = [];

  function openReturnModal(orderId, orderCode, totalAmount, preselectedItemId = null) {
    document.getElementById('returnModalOrderCode').textContent = `Đơn hàng #${orderCode} (Tổng: ${Number(totalAmount).toLocaleString('vi-VN')}₫)`;
    document.getElementById('returnOrderForm').action = `/don-hang/${orderId}/yeu-cau-doi-tra`;

    // Find order in JSON
    const order = userOrdersData.find(o => o.id === orderId);
    currentActiveOrder = order;

    const itemsListContainer = document.getElementById('returnOrderItemsList');
    const badge = document.getElementById('returnSelectedItemBadge');

    if (order && itemsListContainer) {
      itemsListContainer.innerHTML = '';

      // Option 0: Toàn bộ đơn hàng (if multiple items)
      if (order.items && order.items.length > 1) {
        const isAllChecked = !preselectedItemId;
        const allCard = document.createElement('label');
        allCard.id = 'returnItemRadioLabel_all';
        allCard.className = `p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all ${isAllChecked ? 'bg-amber-50/80 border-amber-300 ring-1 ring-amber-300' : 'bg-white border-neutral-200 hover:border-neutral-300'}`;
        allCard.innerHTML = `
          <div class="flex items-center gap-2.5">
            <input type="radio" name="order_item_id" value="" ${isAllChecked ? 'checked' : ''} onchange="handleReturnItemSelected(this, 'Toàn bộ đơn hàng', ${order.total_amount})" class="text-neutral-900">
            <div class="w-9 h-9 rounded-lg bg-neutral-100 flex items-center justify-center text-neutral-700 shrink-0">
              <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <div>
              <strong class="text-xs text-neutral-900 block">Toàn bộ đơn hàng (${order.items.length} sản phẩm)</strong>
              <span class="text-[10px] text-neutral-400">Yêu cầu hoàn tiền cho tất cả các món trong đơn #${order.code}</span>
            </div>
          </div>
          <span class="font-serif-luxury font-bold text-neutral-900 text-xs">${Number(order.total_amount).toLocaleString('vi-VN')}₫</span>
        `;
        itemsListContainer.appendChild(allCard);
      }

      // Each product item
      (order.items || []).forEach(it => {
        const isChecked = (preselectedItemId && it.id === preselectedItemId) || (!preselectedItemId && order.items.length === 1);
        const itemCard = document.createElement('label');
        itemCard.id = `returnItemRadioLabel_${it.id}`;
        itemCard.className = `p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all ${isChecked ? 'bg-amber-50/80 border-amber-300 ring-1 ring-amber-300' : 'bg-white border-neutral-200 hover:border-neutral-300'}`;
        itemCard.innerHTML = `
          <div class="flex items-center gap-2.5 min-w-0 pr-2">
            <input type="radio" name="order_item_id" value="${it.id}" ${isChecked ? 'checked' : ''} onchange="handleReturnItemSelected(this, '${it.name.replace(/'/g, "\\'")}', ${it.subtotal})" class="text-neutral-900 shrink-0">
            <img src="${it.thumbnail}" class="w-10 h-12 rounded object-cover border border-neutral-200 shrink-0">
            <div class="min-w-0">
              <strong class="text-xs text-neutral-900 block truncate">${it.name}</strong>
              <span class="text-[11px] text-neutral-500">Màu: <b class="text-neutral-700">${it.color || 'Chuẩn'}</b> | Size: <b class="text-neutral-700">${it.size || 'M'}</b> • SL: x${it.quantity}</span>
            </div>
          </div>
          <span class="font-serif-luxury font-bold text-neutral-900 text-xs shrink-0">${Number(it.subtotal).toLocaleString('vi-VN')}₫</span>
        `;
        itemsListContainer.appendChild(itemCard);
      });

      // Update badge text
      if (preselectedItemId) {
        const selItem = order.items.find(it => it.id === preselectedItemId);
        if (badge && selItem) {
          badge.textContent = `${selItem.name} (${Number(selItem.subtotal).toLocaleString('vi-VN')}₫)`;
        }
      } else {
        if (badge) {
          badge.textContent = (order.items && order.items.length > 1) ? `Toàn bộ đơn hàng (${Number(order.total_amount).toLocaleString('vi-VN')}₫)` : `${order.items[0]?.name} (${Number(order.items[0]?.subtotal).toLocaleString('vi-VN')}₫)`;
        }
      }
    }

    // Reset thông tin ngân hàng: KHÁCH HÀNG TỰ NHẬP, KHÔNG NHẬP SẴN
    const returnBankSelect = document.getElementById('returnBankSelect');
    if (returnBankSelect) returnBankSelect.value = '';
    const returnBankAccNum = document.getElementById('returnBankAccountNumber');
    if (returnBankAccNum) returnBankAccNum.value = '';
    const returnBankAccName = document.getElementById('returnBankAccountName');
    if (returnBankAccName) returnBankAccName.value = '';
    handleBankSelectChange('');

    // Khởi tạo biến thể đổi màu/size cho item được chọn
    const activeItemId = preselectedItemId || (order.items && order.items.length > 0 ? order.items[0].id : null);
    renderExchangeVariants(activeItemId);

    // Reset uploads & fields
    selectedReturnFiles = [];
    const previewContainer = document.getElementById('returnImagesPreviewList');
    if (previewContainer) previewContainer.innerHTML = '';
    const imgInput = document.getElementById('returnImageProofsInput');
    if (imgInput) imgInput.value = '';
    removeReturnVideo();

    // Set radio & fields về default (Trả hàng hoàn tiền)
    const radioRefund = document.querySelector('input[name="type"][value="return_refund"]');
    if (radioRefund) radioRefund.checked = true;
    handleProfileReturnTypeChange('return_refund');

    document.getElementById('returnOrderModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  function handleReturnItemSelected(radio, label, amount) {
    const badge = document.getElementById('returnSelectedItemBadge');
    if (badge) {
      badge.textContent = `${label} (${Number(amount).toLocaleString('vi-VN')}₫)`;
    }
    // Update active highlight on all item cards in the container
    document.querySelectorAll('#returnOrderItemsList label').forEach(lbl => {
      const r = lbl.querySelector('input[type="radio"]');
      if (r && r.checked) {
        lbl.className = 'p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all bg-amber-50/80 border-amber-300 ring-1 ring-amber-300';
      } else {
        lbl.className = 'p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all bg-white border-neutral-200 hover:border-neutral-300';
      }
    });

    // Nếu chọn 1 sản phẩm cụ thể, cập nhật ngay khối Màu & Size chuẩn của sản phẩm đó
    if (radio.value) {
      renderExchangeVariants(radio.value);
    }
  }

  function closeReturnModal() {
    document.getElementById('returnOrderModal').classList.add('hidden');
  }

  // Lắng nghe sự kiện submit của returnOrderForm để kiểm tra tính hợp lệ
  document.getElementById('returnOrderForm')?.addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="type"]:checked')?.value;
    const btn = document.getElementById('btnSubmitProfileReturn');

    if (type === 'return_refund') {
      const bankSelect = document.getElementById('returnBankSelect');
      const bankAccNum = document.getElementById('returnBankAccountNumber');
      const bankAccName = document.getElementById('returnBankAccountName');

      if (!bankSelect || !bankSelect.value) {
        e.preventDefault();
        alert('Vui lòng chọn ngân hàng bạn mong muốn nhận tiền hoàn!');
        bankSelect?.focus();
        return false;
      }
      if (!bankAccNum || !bankAccNum.value.trim()) {
        e.preventDefault();
        alert('Vui lòng tự nhập số tài khoản ngân hàng của bạn!');
        bankAccNum?.focus();
        return false;
      }
      if (!bankAccName || !bankAccName.value.trim()) {
        e.preventDefault();
        alert('Vui lòng tự nhập tên chủ tài khoản ngân hàng (viết hoa không dấu)!');
        bankAccName?.focus();
        return false;
      }
    } else if (type === 'exchange') {
      const checkedItem = document.querySelector('input[name="order_item_id"]:checked');
      if (!checkedItem || !checkedItem.value) {
        e.preventDefault();
        alert('Vui lòng chọn cụ thể sản phẩm trong đơn hàng bạn muốn đổi size / đổi màu!');
        return false;
      }
      const exColor = document.getElementById('profileExchangeColorSelect')?.value;
      const exSize = document.getElementById('profileExchangeSizeSelect')?.value;
      if (!exColor && !exSize) {
        e.preventDefault();
        alert('Vui lòng chọn màu sắc hoặc kích cỡ mới mà bạn muốn đổi!');
        return false;
      }
    }

    if (btn) {
      btn.disabled = true;
      const span = btn.querySelector('span');
      if (span) span.textContent = 'Đang xử lý gửi yêu cầu...';
    }
  });

  function handleReturnImagesPreview(input) {
    if (!input.files || input.files.length === 0) return;

    Array.from(input.files).forEach(f => {
      if (selectedReturnFiles.length < 5) {
        selectedReturnFiles.push(f);
      }
    });

    renderReturnImagesList();
  }

  function renderReturnImagesList() {
    const container = document.getElementById('returnImagesPreviewList');
    const input = document.getElementById('returnImageProofsInput');
    if (!container || !input) return;

    try {
      const dt = new DataTransfer();
      selectedReturnFiles.forEach(f => dt.items.add(f));
      input.files = dt.files;
    } catch(e) {}

    container.innerHTML = '';
    selectedReturnFiles.forEach((file, idx) => {
      const reader = new FileReader();
      reader.onload = function(e) {
        const wrap = document.createElement('div');
        wrap.className = 'relative w-16 h-20 rounded-lg border border-neutral-300 overflow-hidden shrink-0 group shadow-sm bg-neutral-100';
        wrap.innerHTML = `
          <img src="${e.target.result}" class="w-full h-full object-cover">
          <button type="button" onclick="removeReturnImage(${idx})" class="absolute top-1 right-1 bg-neutral-900/80 hover:bg-neutral-950 text-white w-4 h-4 flex items-center justify-center text-[10px] rounded-full opacity-90 transition-opacity">✕</button>
        `;
        container.appendChild(wrap);
      };
      reader.readAsDataURL(file);
    });
  }

  function removeReturnImage(index) {
    selectedReturnFiles.splice(index, 1);
    renderReturnImagesList();
  }

  function handleReturnVideoPreview(input) {
    if (!input.files || input.files.length === 0) return;
    const file = input.files[0];
    const previewBox = document.getElementById('returnVideoPreviewBox');
    const nameEl = document.getElementById('returnVideoPreviewName');
    if (!previewBox || !nameEl) return;

    const sizeMB = (file.size / (1024 * 1024)).toFixed(1);
    nameEl.textContent = `${file.name} (${sizeMB} MB)`;
    previewBox.classList.remove('hidden');
  }

  function removeReturnVideo() {
    const input = document.getElementById('returnVideoUnboxInput');
    const previewBox = document.getElementById('returnVideoPreviewBox');
    if (input) input.value = '';
    if (previewBox) previewBox.classList.add('hidden');
  }

  // Cancel Order Modal
  function openCancelModal(orderId, orderCode, totalAmount = 0, itemsCount = 1) {
    const titleEl = document.getElementById('cancelModalOrderCode');
    if (titleEl) titleEl.textContent = `Hủy Đơn Hàng #${orderCode}`;

    const formEl = document.getElementById('cancelOrderForm');
    if (formEl) {
      formEl.action = `/don-hang/${orderId}/huy`;
      formEl.reset();
    }

    const amountEl = document.getElementById('cancelModalOrderAmount');
    if (amountEl) {
      amountEl.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(totalAmount || 0);
    }

    const itemsCountEl = document.getElementById('cancelModalOrderItemsCount');
    if (itemsCountEl) {
      itemsCountEl.textContent = `${itemsCount || 1} sản phẩm`;
    }

    const btnSubmit = document.getElementById('btnSubmitCancelOrder');
    if (btnSubmit) {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = `<i data-lucide="x-circle" class="w-4 h-4"></i><span>Xác Nhận Hủy Đơn</span>`;
    }

    document.getElementById('cancelOrderModal').classList.remove('hidden');
    if (window.lucide) window.lucide.createIcons();
  }
  function closeCancelModal() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
  }
  function handleCancelOrderSubmit(form) {
    const btn = document.getElementById('btnSubmitCancelOrder');
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Đang xử lý hủy...`;
    }
  }

  // Quick Review Modal
  function openQuickReviewModal(productId, productName) {
    document.getElementById('quickReviewProductName').textContent = productName;
    document.getElementById('quickReviewForm').action = `/san-pham/${productId}/danh-gia`;
    document.getElementById('quickReviewModal').classList.remove('hidden');
  }
  function closeQuickReviewModal() {
    document.getElementById('quickReviewModal').classList.add('hidden');
  }

  function setQuickModalRating(star) {
    document.getElementById('quickRatingInput').value = star;
    const labels = {
      1: 'Rất tệ (1/5 sao)',
      2: 'Chưa hài lòng (2/5 sao)',
      3: 'Bình thường (3/5 sao)',
      4: 'Hài lòng (4/5 sao)',
      5: 'Tuyệt vời (5/5 sao)'
    };
    document.getElementById('quickRatingLabel').textContent = labels[star] || `${star}/5 sao`;
    document.querySelectorAll('.quick-rating-star').forEach(btn => {
      const s = parseInt(btn.getAttribute('data-star'));
      if (s <= star) {
        btn.classList.add('text-amber-400');
        btn.classList.remove('text-neutral-300');
      } else {
        btn.classList.remove('text-amber-400');
        btn.classList.add('text-neutral-300');
      }
    });
  }

  // Reject Order from Profile
  function openProfileRejectModal(orderCode) {
    document.getElementById('rejectModalOrderTitle').textContent = `Từ Chối Nhận Hàng #${orderCode}`;
    document.getElementById('profileRejectOrderForm').action = `{{ url('/tra-cuu-don-hang') }}/${encodeURIComponent(orderCode)}/khong-nhan-hang`;
    document.getElementById('profileRejectOrderModal').classList.remove('hidden');
  }
  function closeProfileRejectModal() {
    document.getElementById('profileRejectOrderModal').classList.add('hidden');
  }

  // 2-Step Contact Change Modals
  function openContactModal(type) {
    document.getElementById('contactType').value = type;
    document.getElementById('contactModalTitle').textContent = type === 'email' ? 'Thay Đổi Địa Chỉ Email' : 'Thay Đổi Số Điện Thoại';
    document.getElementById('contactInputLabel').textContent = type === 'email' ? 'Địa Chỉ Email Mới' : 'Số Điện Thoại Mới';
    document.getElementById('contactNewValue').placeholder = type === 'email' ? 'user@example.com' : '0987654321';
    document.getElementById('contactStep1').classList.remove('hidden');
    document.getElementById('contactStep2').classList.add('hidden');
    document.getElementById('contactOtpModal').classList.remove('hidden');
  }
  function closeContactModal() {
    document.getElementById('contactOtpModal').classList.add('hidden');
  }

  function handleRequestContactOtp(e) {
    e.preventDefault();
    const type = document.getElementById('contactType').value;
    const value = document.getElementById('contactNewValue').value.trim();
    const btn = document.getElementById('requestOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Đang gửi mã OTP...';

    fetch('{{ route("client.profile.contact.request") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ type: type, value: value })
    })
    .then(r => r.json())
    .then(data => {
      btn.disabled = false;
      btn.textContent = 'Gửi Mã Xác Thực OTP';
      if (data.success) {
        alert(data.message || 'Mã OTP đã được gửi!');
        document.getElementById('contactStep1').classList.add('hidden');
        document.getElementById('contactStep2').classList.remove('hidden');
      } else {
        alert(data.message || 'Có lỗi xảy ra.');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.textContent = 'Gửi Mã Xác Thực OTP';
      alert('Không thể kết nối máy chủ.');
    });
  }

  function handleConfirmContactOtp(e) {
    e.preventDefault();
    const type = document.getElementById('contactType').value;
    const otp = document.getElementById('contactOtpCode').value.trim();
    const btn = document.getElementById('confirmOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Đang xác thực...';

    fetch('{{ route("client.profile.contact.confirm") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({ type: type, otp: otp })
    })
    .then(r => r.json())
    .then(data => {
      btn.disabled = false;
      btn.textContent = 'Xác Nhận Thay Đổi';
      if (data.success) {
        alert(data.message || 'Đã cập nhật thông tin thành công!');
        window.location.reload();
      } else {
        alert(data.message || 'Mã OTP không chính xác.');
      }
    })
    .catch(() => {
      btn.disabled = false;
      btn.textContent = 'Xác Nhận Thay Đổi';
      alert('Không thể kết nối máy chủ.');
    });
  }
</script>
@endpush