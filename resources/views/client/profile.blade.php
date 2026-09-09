@extends('layouts.client')

@section('title', 'Tài Khoản & Thiết Lập Atelier — BEESTYLE Studio')

@section('content')
@php
  $addresses = $addresses ?? ($user->addresses ?? collect());
  $pendingReviewItems = $pendingReviewItems ?? collect();
  $userReviews = $user->reviews ?? collect();
  
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
                        <strong>Yêu Cầu Đổi Trả #RMA-{{ str_pad($order->latestReturn->id, 5, '0', STR_PAD_LEFT) }}:</strong>
                        <span>{{ $order->latestReturn->type_label ?? 'Đổi trả hàng' }}</span>
                      </div>
                      <button type="button" onclick="switchProfileTab('returns')" class="px-2.5 py-1 bg-white border border-neutral-300 rounded font-semibold text-[10px] hover:bg-neutral-50">
                        Xem Tiến Trình
                      </button>
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
                      <!-- Hủy đơn hàng -->
                      @if(method_exists($order, 'canBeCancelledByCustomer') ? $order->canBeCancelledByCustomer() : in_array($order->shipping_status, ['pending', 'processing']))
                        <button type="button" onclick="openCancelModal({{ $order->id }}, '{{ $order->order_code }}')" class="px-3 py-1.5 bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 rounded-lg text-xs font-semibold transition-colors">
                          Hủy Đơn
                        </button>
                      @endif

                      <!-- Xác nhận đã nhận / Không nhận hàng cho đơn đang giao -->
                      @if(in_array($order->shipping_status, ['shipping', 'delivered']) || (isset($order->status_step) && in_array($order->status_step, [4, 5])))
                        <form action="{{ route('client.order-tracking.confirm-delivered', $order->order_code) }}" method="POST" class="inline" onsubmit="return confirm('Bạn xác nhận đã nhận được kiện hàng và muốn hoàn tất đơn #{{ $order->order_code }}?');">
                          @csrf
                          <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors">
                            Đã Nhận Hàng
                          </button>
                        </form>
                        <button type="button" onclick="openProfileRejectModal('{{ $order->order_code }}')" class="px-3 py-1.5 border border-rose-300 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-semibold transition-colors">
                          Không Nhận
                        </button>
                      @endif

                      <!-- Yêu cầu đổi trả RMA 30 ngày (Toàn bộ đơn hàng) -->
                      @if(in_array($order->shipping_status, ['delivered', 'completed']) || $order->status === 'completed')
                        <button type="button" onclick="openReturnModal({{ $order->id }}, '{{ $order->order_code }}', {{ $order->total_amount }})" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-neutral-950 font-bold rounded-lg text-xs transition-colors flex items-center gap-1 shadow-sm">
                          <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Đổi Trả / Hoàn Tiền (30 Ngày)
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
      <!-- TAB 2: PENDING REVIEWS (CHỜ ĐÁNH GIÁ) -->
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
      <!-- TAB 3: MY REVIEWS (ĐÁNH GIÁ CỦA TÔI) -->
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
      <!-- TAB 4: RETURNS & REFUNDS (RMA) -->
      <!-- ========================================================================= -->
      <div id="tab-panel-returns" class="profile-panel hidden space-y-4">
        <div class="bg-white p-6 md:p-8 rounded-2xl border border-neutral-200 shadow-sm text-xs">
          <div class="pb-4 mb-6 border-b border-neutral-100 flex justify-between items-center">
            <div>
              <h3 class="font-serif-luxury text-2xl font-bold text-neutral-900">Tiến Trình Đổi Trả &amp; Hoàn Tiền</h3>
              <p class="text-neutral-500 mt-0.5">Theo dõi trạng thái xử lý yêu cầu hoàn tiền và đổi size tận nơi</p>
            </div>
            <span class="px-3 py-1 bg-amber-100 text-amber-900 rounded-full font-bold uppercase text-[10px]">
              CHÍNH SÁCH 30 NGÀY
            </span>
          </div>

          @if(!isset($returns) || $returns->isEmpty())
            <div class="text-center py-12 text-neutral-500">
              <i data-lucide="rotate-ccw" class="w-12 h-12 mx-auto text-neutral-300 mb-3 stroke-1"></i>
              <p class="font-medium text-neutral-800 text-sm">Bạn chưa có yêu cầu đổi trả hoặc hoàn tiền nào</p>
              <p class="text-neutral-400 mt-1 max-w-md mx-auto">
                Khi nhận được hàng, nếu không vừa size hoặc không ưng ý, bạn có thể bấm nút <strong>"Đổi Trả"</strong> tại từng sản phẩm hoặc <strong>"Đổi Trả / Hoàn Tiền"</strong> của đơn hàng để được hỗ trợ 100% miễn phí.
              </p>
            </div>
          @else
            <div class="space-y-6">
              @foreach($returns as $ret)
                <div class="p-6 rounded-2xl border border-neutral-200 bg-neutral-50/70 space-y-4">
                  <div class="flex flex-wrap justify-between items-center gap-2 pb-3 border-b border-neutral-200">
                    <div>
                      <span class="font-bold text-neutral-950 text-sm">Yêu cầu #RMA-{{ str_pad($ret->id, 5, '0', STR_PAD_LEFT) }}</span>
                      <span class="text-neutral-400 ml-2">Đơn hàng: <strong>#{{ $ret->order->order_code ?? '' }}</strong></span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $ret->status === 'completed' || $ret->status === 'refunded' ? 'bg-emerald-100 text-emerald-800' : ($ret->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-900') }}">
                      {{ $ret->status_label ?? $ret->status }}
                    </span>
                  </div>

                  <!-- 4-Step Refund Progress Tracker -->
                  <div class="p-4 bg-white rounded-xl border border-neutral-200">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-neutral-400 block mb-3">TIẾN TRÌNH XỬ LÝ:</span>
                    <div class="grid grid-cols-4 gap-2 text-center text-[11px]">
                      <div class="space-y-1">
                        <div class="w-7 h-7 mx-auto rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs">✓</div>
                        <span class="font-semibold text-neutral-800 block">1. Đã Gửi</span>
                      </div>
                      <div class="space-y-1">
                        <div class="w-7 h-7 mx-auto rounded-full {{ in_array($ret->status, ['approved', 'received', 'completed', 'refunded']) ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-600' }} flex items-center justify-center font-bold text-xs">2</div>
                        <span class="font-semibold text-neutral-800 block">2. Xưởng Duyệt</span>
                      </div>
                      <div class="space-y-1">
                        <div class="w-7 h-7 mx-auto rounded-full {{ in_array($ret->status, ['received', 'completed', 'refunded']) ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-600' }} flex items-center justify-center font-bold text-xs">3</div>
                        <span class="font-semibold text-neutral-800 block">3. Nhận Hàng</span>
                      </div>
                      <div class="space-y-1">
                        <div class="w-7 h-7 mx-auto rounded-full {{ in_array($ret->status, ['completed', 'refunded']) ? 'bg-emerald-600 text-white' : 'bg-neutral-200 text-neutral-600' }} flex items-center justify-center font-bold text-xs">4</div>
                        <span class="font-semibold text-neutral-800 block">4. Hoàn Tất</span>
                      </div>
                    </div>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-neutral-700">
                    <div class="space-y-2">
                      <p><strong>Hình thức:</strong> {{ $ret->type === 'exchange' ? 'Đổi Size / Đổi Màu' : 'Trả Hàng & Hoàn Tiền' }}</p>
                      <p><strong>Lý do:</strong> {{ $ret->reason }}</p>
                      
                      @if($ret->exchange_size || $ret->exchange_color)
                        <p class="text-amber-800 font-semibold bg-amber-50 p-2 rounded border border-amber-200">
                          Yêu cầu đổi sang: {{ $ret->exchange_size ? 'Size ' . $ret->exchange_size : '' }} {{ $ret->exchange_color ? 'Màu ' . $ret->exchange_color : '' }}
                        </p>
                      @endif

                      <!-- Sản phẩm đổi trả cụ thể -->
                      @if($ret->orderItem)
                        <div class="p-2.5 bg-white rounded-xl border border-neutral-200 flex items-center gap-3">
                          <img src="{{ asset($ret->orderItem->product->primaryImage->image_path ?? $ret->orderItem->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=200&auto=format&fit=crop') }}" class="w-12 h-14 rounded-lg object-cover border border-neutral-200 shrink-0">
                          <div class="min-w-0 text-xs">
                            <span class="text-[10px] font-bold uppercase text-amber-800 block">Sản phẩm đổi trả:</span>
                            <strong class="text-neutral-900 block truncate">{{ $ret->orderItem->product_name }}</strong>
                            <span class="text-[11px] text-neutral-500">Màu: {{ $ret->orderItem->color ?? 'Chuẩn' }} | Size: {{ $ret->orderItem->size ?? 'M' }} • SL: x{{ $ret->orderItem->quantity }}</span>
                          </div>
                        </div>
                      @else
                        <div class="p-2 bg-neutral-100 rounded-lg text-[11px] text-neutral-600">
                          <strong>Phạm vi:</strong> Toàn bộ sản phẩm trong đơn hàng
                        </div>
                      @endif

                      @if($ret->customer_notes)
                        <p class="text-[11px] text-neutral-600 bg-white p-2 rounded-lg border border-neutral-200"><strong>Ghi chú:</strong> {{ $ret->customer_notes }}</p>
                      @endif

                      @if($ret->admin_notes)
                        <div class="p-3 bg-amber-50 rounded-lg border border-amber-200 text-amber-900 text-[11px]">
                          <strong>Ghi chú từ Xưởng may:</strong> {{ $ret->admin_notes }}
                        </div>
                      @endif
                    </div>

                    <div class="space-y-3">
                      <div class="bg-white p-3.5 rounded-xl border border-neutral-200 space-y-1">
                        <span class="font-semibold block text-neutral-900">Tài khoản nhận tiền hoàn:</span>
                        <p class="font-mono text-neutral-900">{{ $ret->bank_name ?? $user->bank_name ?? 'Chưa cung cấp' }} — {{ $ret->bank_account_number ?? $user->bank_account_number ?? '' }}</p>
                        <p class="text-neutral-500 font-mono text-[11px]">{{ $ret->bank_account_name ?? $user->bank_account_name ?? '' }}</p>
                        @if($ret->refund_amount)
                          <p class="mt-2 text-rose-700 font-bold font-serif-luxury text-sm">Số tiền hoàn trả: {{ number_format($ret->refund_amount, 0, ',', '.') }}₫</p>
                        @endif
                      </div>

                      <!-- Ảnh minh chứng đã tải lên -->
                      @if(!empty($ret->image_proofs) && is_array($ret->image_proofs) && count($ret->image_proofs) > 0)
                        <div class="space-y-1 bg-white p-3 rounded-xl border border-neutral-200">
                          <span class="text-[10px] uppercase font-bold text-neutral-600 block">Ảnh minh chứng đã gửi ({{ count($ret->image_proofs) }}):</span>
                          <div class="flex gap-2 overflow-x-auto py-1">
                            @foreach($ret->image_proofs as $img)
                              <img src="{{ asset($img) }}" class="w-12 h-14 rounded-lg object-cover border border-neutral-200 shrink-0 cursor-pointer hover:opacity-80 transition-opacity" onclick="window.open('{{ asset($img) }}', '_blank')">
                            @endforeach
                          </div>
                        </div>
                      @endif
                    </div>
                  </div>

                </div>
              @endforeach
            </div>
          @endif
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
                <option value="" disabled selected>-- Chọn ngân hàng --</option>
                <option value="Techcombank" {{ old('bank_name', $user->bank_name) === 'Techcombank' ? 'selected' : '' }}>Techcombank (Ngân hàng Kỹ Thương)</option>
                <option value="Vietcombank" {{ old('bank_name', $user->bank_name) === 'Vietcombank' ? 'selected' : '' }}>Vietcombank (Ngân hàng TMCP Ngoại Thương)</option>
                <option value="MB Bank" {{ old('bank_name', $user->bank_name) === 'MB Bank' ? 'selected' : '' }}>MB Bank (Ngân hàng Quân Đội)</option>
                <option value="ACB" {{ old('bank_name', $user->bank_name) === 'ACB' ? 'selected' : '' }}>ACB (Ngân hàng Á Châu)</option>
                <option value="VPBank" {{ old('bank_name', $user->bank_name) === 'VPBank' ? 'selected' : '' }}>VPBank (Ngân hàng Việt Nam Thịnh Vượng)</option>
                <option value="BIDV" {{ old('bank_name', $user->bank_name) === 'BIDV' ? 'selected' : '' }}>BIDV (Ngân hàng Đầu tư & Phát triển)</option>
                <option value="VietinBank" {{ old('bank_name', $user->bank_name) === 'VietinBank' ? 'selected' : '' }}>VietinBank (Ngân hàng Công Thương)</option>
                <option value="TPBank" {{ old('bank_name', $user->bank_name) === 'TPBank' ? 'selected' : '' }}>TPBank (Ngân hàng Tiên Phong)</option>
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

      <!-- Khối Chọn Sản Phẩm Cần Đổi Trả (Kèm Ảnh Minh Họa, Tên, Phân Loại, Giá) -->
      <div>
        <div class="flex justify-between items-center mb-1.5">
          <label class="block font-semibold uppercase text-neutral-700">1. Chọn Sản Phẩm Cần Đổi Trả / Hoàn Tiền *</label>
          <span id="returnSelectedItemBadge" class="text-[10px] font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-full">Toàn bộ đơn hàng</span>
        </div>
        <div id="returnOrderItemsList" class="space-y-2 max-h-52 overflow-y-auto pr-1 border border-neutral-200 rounded-xl p-2 bg-neutral-50/60">
          <!-- Populated dynamically by JS with thumbnail images, names, colors, sizes, prices -->
        </div>
      </div>
      
      <!-- Hình thức mong muốn -->
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1.5">2. Hình Thức Mong Muốn *</label>
        <div class="grid grid-cols-2 gap-2">
          <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer bg-neutral-50 hover:bg-neutral-100 transition-colors">
            <input type="radio" name="type" value="return_refund" checked onchange="toggleReturnTypeFields('return_refund')" class="text-neutral-900">
            <span><strong>Trả Hàng &amp; Hoàn Tiền</strong></span>
          </label>
          <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer bg-neutral-50 hover:bg-neutral-100 transition-colors">
            <input type="radio" name="type" value="exchange" onchange="toggleReturnTypeFields('exchange')" class="text-neutral-900">
            <span><strong>Đổi Size / Đổi Màu</strong></span>
          </label>
        </div>
      </div>

      <!-- Tùy chọn đổi size / màu nếu chọn Đổi hàng -->
      <div id="exchangeFieldsBox" class="hidden p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
        <span class="font-bold text-amber-900 uppercase text-[10px] block">Yêu Cầu Đổi Size / Đổi Màu Cụ Thể:</span>
        <div class="grid grid-cols-2 gap-2">
          <input type="text" name="exchange_size" placeholder="Size mong muốn (VD: L, XL, 41...)" class="w-full bg-white border border-neutral-300 rounded p-2 text-xs">
          <input type="text" name="exchange_color" placeholder="Màu mong muốn (VD: Đen, Trắng...)" class="w-full bg-white border border-neutral-300 rounded p-2 text-xs">
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
        <label class="block font-semibold uppercase text-neutral-700 mb-1">4. Mô Tả Chi Tiết Vấn Đề (Tùy chọn)</label>
        <textarea name="customer_notes" rows="2" placeholder="Ghi chú chi tiết về tình trạng sản phẩm, yêu cầu đổi size/màu mong muốn..." class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5 focus:outline-none focus:border-neutral-950"></textarea>
      </div>

      <!-- BẮT BUỘC: Upload Hình Ảnh Minh Chứng -->
      <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200 space-y-2">
        <div class="flex items-center justify-between">
          <label class="font-bold uppercase text-neutral-900 text-xs flex items-center gap-1.5">
            <i data-lucide="camera" class="w-4 h-4 text-amber-700"></i>
            <span>5. Ảnh Minh Chứng Sản Phẩm / Tem Mác <span class="text-rose-600">* (Bắt buộc)</span></span>
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

      <!-- Thông tin ngân hàng nhận tiền hoàn -->
      <div id="returnBankInfoBox" class="p-3 bg-brand-50 rounded-xl border border-brand-200 space-y-2">
        <span class="font-bold text-neutral-900 uppercase text-[10px] block">7. Thông Tin Nhận Tiền Hoàn:</span>
        <input type="text" name="bank_name" value="{{ $user->bank_name ?? 'Vietcombank' }}" placeholder="Tên Ngân Hàng (VD: Vietcombank, MB Bank...)" class="w-full bg-white border border-neutral-300 rounded p-2 text-xs">
        <input type="text" name="bank_account_number" value="{{ $user->bank_account_number ?? '' }}" placeholder="Số Tài Khoản Ngân Hàng" class="w-full bg-white border border-neutral-300 rounded p-2 text-xs font-mono">
        <input type="text" name="bank_account_name" value="{{ $user->bank_account_name ?? $user->name }}" placeholder="Tên Chủ Tài Khoản (IN HOA)" class="w-full bg-white border border-neutral-300 rounded p-2 text-xs uppercase">
      </div>

      <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-neutral-950 font-bold uppercase tracking-wider rounded-xl transition-colors shadow flex items-center justify-center gap-2">
        <i data-lucide="check-circle" class="w-4 h-4"></i>
        <span>Gửi Yêu Cầu Hoàn Tiền / Đổi Trả</span>
      </button>
    </form>
  </div>
</div>

<!-- 2. MODAL HỦY ĐƠN HÀNG -->
<div id="cancelOrderModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
  <div class="bg-white max-w-md w-full rounded-2xl p-6 shadow-2xl border border-neutral-200 animate-fade-in text-xs">
    <div class="flex justify-between items-center pb-3 mb-4 border-b border-neutral-100">
      <h3 class="font-serif-luxury text-xl font-bold text-rose-600" id="cancelModalOrderCode">Hủy Đơn Hàng</h3>
      <button onclick="closeCancelModal()" class="text-neutral-400 hover:text-black">&times;</button>
    </div>

    <form id="cancelOrderForm" method="POST" class="space-y-4">
      @csrf
      <div class="p-3 bg-rose-50 text-rose-800 rounded-xl text-[11px]">
        Khi xác nhận hủy đơn, hệ thống sẽ tự động hoàn lại số lượng tồn kho và mã giảm giá cho bạn.
      </div>
      <div>
        <label class="block font-semibold uppercase text-neutral-700 mb-1">Lý Do Hủy Đơn *</label>
        <select name="reason" required class="w-full bg-neutral-50 border border-neutral-200 rounded-lg p-2.5">
          <option value="Tôi muốn đổi địa chỉ nhận hàng">Tôi muốn đổi địa chỉ nhận hàng</option>
          <option value="Tôi muốn thay đổi Size hoặc Màu sắc áo">Tôi muốn thay đổi Size hoặc Màu sắc áo</option>
          <option value="Tôi đổi ý, không có nhu cầu mua nữa">Tôi đổi ý, không có nhu cầu mua nữa</option>
          <option value="Lý do khác">Lý do khác</option>
        </select>
      </div>
      <button type="submit" class="w-full py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold uppercase tracking-wider rounded-xl transition-colors shadow">
        Xác Nhận Hủy Đơn
      </button>
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
        return [
          'id' => $it->id,
          'product_id' => $it->product_id,
          'name' => $it->product_name,
          'color' => $it->color,
          'size' => $it->size,
          'quantity' => $it->quantity,
          'price' => $it->price,
          'subtotal' => $it->price * $it->quantity,
          'thumbnail' => asset($it->product->primaryImage->image_path ?? $it->product->thumbnail ?? 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=200&auto=format&fit=crop'),
        ];
      })
    ];
  });
@endphp
<script>
  const userOrdersData = @json($ordersJsonData);

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

  // RMA Return Modal
  let selectedReturnFiles = [];

  function openReturnModal(orderId, orderCode, totalAmount, preselectedItemId = null) {
    document.getElementById('returnModalOrderCode').textContent = `Đơn hàng #${orderCode} (Tổng: ${Number(totalAmount).toLocaleString('vi-VN')}₫)`;
    document.getElementById('returnOrderForm').action = `/don-hang/${orderId}/yeu-cau-doi-tra`;

    // Find order in JSON
    const order = userOrdersData.find(o => o.id === orderId);
    const itemsListContainer = document.getElementById('returnOrderItemsList');
    const badge = document.getElementById('returnSelectedItemBadge');

    if (order && itemsListContainer) {
      itemsListContainer.innerHTML = '';

      // Option 0: Toàn bộ đơn hàng (if multiple items)
      if (order.items && order.items.length > 1) {
        const isAllChecked = !preselectedItemId;
        const allCard = document.createElement('label');
        allCard.className = `p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all ${isAllChecked ? 'bg-amber-50/80 border-amber-300 ring-1 ring-amber-300' : 'bg-white border-neutral-200 hover:border-neutral-300'}`;
        allCard.innerHTML = `
          <div class="flex items-center gap-2.5">
            <input type="radio" name="order_item_id" value="" ${isAllChecked ? 'checked' : ''} onchange="handleReturnItemSelected(this, 'Toàn bộ đơn hàng', ${order.total_amount})" class="text-neutral-900">
            <div class="w-9 h-9 rounded-lg bg-neutral-100 flex items-center justify-center text-neutral-700 shrink-0">
              <i data-lucide="package" class="w-4 h-4"></i>
            </div>
            <div>
              <strong class="text-xs text-neutral-900 block">Toàn bộ đơn hàng (${order.items.length} sản phẩm)</strong>
              <span class="text-[10px] text-neutral-400">Yêu cầu đổi trả cho tất cả các món trong đơn #${order.code}</span>
            </div>
          </div>
          <span class="font-serif-luxury font-bold text-neutral-900 text-xs">${Number(order.total_amount).toLocaleString('vi-VN')}₫</span>
        `;
        itemsListContainer.appendChild(allCard);
      }

      // Each product item
      (order.items || []).forEach(it => {
        const isChecked = (preselectedItemId && it.id === preselectedItemId) || (order.items.length === 1);
        const itemCard = document.createElement('label');
        itemCard.className = `p-2.5 border rounded-xl flex items-center justify-between cursor-pointer transition-all ${isChecked ? 'bg-amber-50/80 border-amber-300 ring-1 ring-amber-300' : 'bg-white border-neutral-200 hover:border-neutral-300'}`;
        itemCard.innerHTML = `
          <div class="flex items-center gap-2.5 min-w-0 pr-2">
            <input type="radio" name="order_item_id" value="${it.id}" ${isChecked ? 'checked' : ''} onchange="handleReturnItemSelected(this, '${it.name.replace(/'/g, "\\'")}', ${it.subtotal})" class="text-neutral-900 shrink-0">
            <img src="${it.thumbnail}" class="w-10 h-12 rounded object-cover border border-neutral-200 shrink-0">
            <div class="min-w-0">
              <strong class="text-xs text-neutral-900 block truncate">${it.name}</strong>
              <span class="text-[11px] text-neutral-500">Màu: ${it.color || 'Chuẩn'} | Size: ${it.size || 'M'} • SL: x${it.quantity}</span>
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

    // Reset uploads
    selectedReturnFiles = [];
    const previewContainer = document.getElementById('returnImagesPreviewList');
    if (previewContainer) previewContainer.innerHTML = '';
    const imgInput = document.getElementById('returnImageProofsInput');
    if (imgInput) imgInput.value = '';
    removeReturnVideo();
    toggleReturnTypeFields('return_refund');

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
  }
  function closeReturnModal() {
    document.getElementById('returnOrderModal').classList.add('hidden');
  }

  function toggleReturnTypeFields(type) {
    const exchangeBox = document.getElementById('exchangeFieldsBox');
    if (exchangeBox) {
      if (type === 'exchange') {
        exchangeBox.classList.remove('hidden');
      } else {
        exchangeBox.classList.add('hidden');
      }
    }
  }

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

    // Sync input files with DataTransfer
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
  function openCancelModal(orderId, orderCode) {
    document.getElementById('cancelModalOrderCode').textContent = `Hủy Đơn Hàng #${orderCode}`;
    document.getElementById('cancelOrderForm').action = `/don-hang/${orderId}/huy`;
    document.getElementById('cancelOrderModal').classList.remove('hidden');
  }
  function closeCancelModal() {
    document.getElementById('cancelOrderModal').classList.add('hidden');
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
    document.getElementById('profileRejectOrderForm').action = `{{ url('/don-hang/tra-cuu') }}/${orderCode}/tu-choi-nhan`;
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