@extends('layouts.client')

@section('title', 'Thanh Toán MoMo ATM (Không Dùng Mã QR) — Đơn Hàng #' . $order->order_code)

@section('content')
<main class="w-full flex-grow py-10 px-4 sm:px-6 max-w-5xl mx-auto">

  <!-- Thông báo hệ thống -->
  @if(session('error'))
    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5">
      <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
      <span class="font-medium">{{ session('error') }}</span>
    </div>
  @endif

  @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5">
      <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
      <span class="font-medium">{{ session('success') }}</span>
    </div>
  @endif

  <!-- Tiêu đề trang & Đồng hồ đếm ngược -->
  <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-neutral-200">
    <div>
      <span class="text-xs tracking-widest uppercase text-[#a50064] font-bold block mb-1">
        CỔNG THANH TOÁN MOMO PAYMENT (ATM NỘI ĐỊA / NAPAS 247)
      </span>
      <h1 class="font-serif text-2xl md:text-3xl font-bold text-neutral-900">
        Đơn Hàng #{{ $order->order_code }}
      </h1>
    </div>
    <div class="px-3.5 py-1.5 bg-pink-50 border border-pink-200 text-[#a50064] rounded-full text-xs font-semibold flex items-center gap-1.5 shadow-2xs">
      <i data-lucide="clock" class="w-3.5 h-3.5 text-[#a50064]"></i>
      <span>Hiệu lực thanh toán: <strong id="momoCountdown" class="font-mono">14:59</strong></span>
    </div>
  </div>

  @php
    $isDeposit = ($order->is_deposit_required && $order->deposit_status !== 'paid');
    $payAmount = $isDeposit ? (int)$order->deposit_amount : (int)$order->total_amount;
  @endphp

  <!-- Thẻ chính MoMo Gateway -->
  <div class="bg-white rounded-2xl border border-neutral-200 shadow-xl overflow-hidden">

    <!-- Header Banner Màu Hồng Đặc Trưng MoMo -->
    <div class="bg-gradient-to-r from-[#a50064] via-[#b8006f] to-[#7f004d] text-white p-6 sm:p-7 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 rounded-2xl bg-white p-1.5 shadow-md flex items-center justify-center shrink-0">
          <svg class="w-9 h-9 fill-[#a50064]" viewBox="0 0 96 87" xmlns="http://www.w3.org/2000/svg">
            <path d="M75.5326 0C64.2284 0 55.0651 8.74843 55.0651 19.5409C55.0651 30.3333 64.2284 39.0818 75.5326 39.0818C86.8368 39.0818 96 30.3333 96 19.5409C96 8.74843 86.8368 0 75.5326 0ZM75.5326 27.8805C70.7368 27.8805 66.8403 24.1604 66.8403 19.5818C66.8403 15.0031 70.7368 11.283 75.5326 11.283C80.3283 11.283 84.2248 15.0031 84.2248 19.5818C84.2248 24.1604 80.3283 27.8805 75.5326 27.8805ZM49.1561 14.6761V39.1226H37.3809V14.5535C37.3809 12.7138 35.8394 11.2421 33.9126 11.2421C31.9857 11.2421 30.4442 12.7138 30.4442 14.5535V39.1226H18.669V14.5535C18.669 12.7138 17.1276 11.2421 15.2007 11.2421C13.2739 11.2421 11.7324 12.7138 11.7324 14.5535V39.1226H0V14.6761C0 6.58176 6.89385 0 15.372 0C18.8403 0 22.0089 1.10377 24.5781 2.9434C27.1472 1.10377 30.3586 0 33.7841 0C42.2623 0 49.1561 6.58176 49.1561 14.6761ZM75.5326 47.544C64.2284 47.544 55.0651 56.2925 55.0651 67.0849C55.0651 77.8774 64.2284 86.6258 75.5326 86.6258C86.8368 86.6258 96 77.8774 96 67.0849C96 56.2925 86.8368 47.544 75.5326 47.544ZM75.5326 75.4245C70.7368 75.4245 66.8403 71.7044 66.8403 67.1258C66.8403 62.5472 70.7368 58.827 75.5326 58.827C80.3283 58.827 84.2248 62.5472 84.2248 67.1258C84.2248 71.7044 80.3283 75.4245 75.5326 75.4245ZM49.1561 62.2201V86.6667H37.3809V62.0975C37.3809 60.2579 35.8394 58.7862 33.9126 58.7862C31.9857 58.7862 30.4442 60.2579 30.4442 62.0975V86.6667H18.669V62.0975C18.669 60.2579 17.1276 58.7862 15.2007 58.7862C13.2739 58.7862 11.7324 60.2579 11.7324 62.0975V86.6667H0V62.2201C0 54.1258 6.89385 47.544 15.372 47.544C18.8403 47.544 22.0089 48.6478 24.5781 50.4874C27.1472 48.6478 30.3158 47.544 33.7841 47.544C42.2623 47.544 49.1561 54.1258 49.1561 62.2201Z"/>
          </svg>
        </div>
        <div>
          <h2 class="font-serif text-xl sm:text-2xl font-bold tracking-tight">Thanh Toán Trực Tuyến Qua Cổng MoMo</h2>
          <p class="text-xs text-pink-100 font-light mt-0.5">
            Thanh toán an toàn qua Thẻ ATM nội địa (Napas 24/7) — <strong>Hoàn toàn không yêu cầu quét mã QR</strong>
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2 text-white/90 text-xs">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-black/25 rounded-full border border-white/20">
          <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-300"></i>
          <span>Bảo mật MoMo PCI-DSS</span>
        </span>
      </div>
    </div>

    <div class="p-6 md:p-8 space-y-8">

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start text-xs">

        <!-- ============================================================== -->
        <!-- CỘT 1 (5 COLS): THÔNG TIN ĐƠN HÀNG & SỐ TIỀN THANH TOÁN -->
        <!-- ============================================================== -->
        <div class="lg:col-span-5 space-y-4">

          <!-- Card thông tin khách hàng -->
          <div class="p-5 bg-neutral-50 rounded-2xl border border-neutral-200 space-y-3 shadow-2xs">
            <div class="flex justify-between items-center pb-2.5 border-b border-neutral-200 font-bold text-neutral-900">
              <span class="uppercase tracking-wider text-[11px]">Thông Tin Đơn Hàng</span>
              <span class="text-[#a50064] text-[10px] font-mono px-2 py-0.5 bg-pink-100 rounded">BeeStyle Store</span>
            </div>
            <div class="flex justify-between">
              <span class="text-neutral-500">Mã đơn hàng:</span>
              <strong class="font-mono text-neutral-950 font-bold text-sm">#{{ $order->order_code }}</strong>
            </div>
            <div class="flex justify-between">
              <span class="text-neutral-500">Khách hàng:</span>
              <strong class="text-neutral-900">{{ $order->customer_name }}</strong>
            </div>
            <div class="flex justify-between">
              <span class="text-neutral-500">Số điện thoại:</span>
              <strong class="text-neutral-900">{{ $order->customer_phone }}</strong>
            </div>
            <div class="flex justify-between">
              <span class="text-neutral-500">Phương thức:</span>
              <span class="font-semibold text-[#a50064] flex items-center gap-1">
                <i data-lucide="credit-card" class="w-3.5 h-3.5"></i> Thẻ ATM MoMo (Không QR)
              </span>
            </div>
          </div>

          <!-- Box Số tiền thanh toán -->
          @if($isDeposit)
            <div class="p-4 rounded-2xl text-center bg-amber-50 border-2 border-dashed border-amber-300 space-y-1 shadow-2xs">
              <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-200 text-amber-900 font-bold rounded-full text-[10px]">
                <i data-lucide="shield-alert" class="w-3 h-3"></i> CHÍNH SÁCH ĐẶT CỌC 50%
              </span>
              <span class="text-neutral-600 uppercase font-semibold text-[10px] block pt-1">Số tiền cọc cần thanh toán (50%)</span>
              <h2 class="font-serif text-2xl sm:text-3xl font-bold text-rose-600 font-mono">
                {{ number_format($order->deposit_amount, 0, ',', '.') }}₫
              </h2>
              <p class="text-[11px] text-neutral-600 pt-1.5 border-t border-amber-200">
                Còn lại thu COD khi nhận hàng: <strong class="text-neutral-900 font-mono">{{ number_format($order->remaining_amount, 0, ',', '.') }}₫</strong>
              </p>
            </div>
          @else
            <div class="p-4 rounded-2xl text-center bg-pink-50 border-2 border-dashed border-pink-300 space-y-1 shadow-2xs">
              <span class="text-neutral-600 uppercase font-semibold text-[10px] block">Số tiền cần thanh toán</span>
              <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[#a50064] font-mono">
                {{ number_format($order->total_amount, 0, ',', '.') }}₫
              </h2>
              <span class="text-[10px] text-neutral-500 block">Thanh toán 100% đơn hàng qua MoMo Sandbox</span>
            </div>
          @endif

          <!-- Danh sách sản phẩm mua tóm tắt -->
          @if($order->items && $order->items->count() > 0)
            <div class="p-4 bg-white rounded-2xl border border-neutral-200 space-y-2.5 shadow-2xs">
              <span class="text-[10px] uppercase font-bold text-neutral-400 block pb-1 border-b border-neutral-100">Sản phẩm trong đơn ({{ $order->items->count() }})</span>
              <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                @foreach($order->items as $item)
                  <div class="flex items-center justify-between gap-2 text-[11px]">
                    <span class="text-neutral-800 line-clamp-1 font-medium">{{ $item->product->name ?? 'Sản phẩm BeeStyle' }}</span>
                    <span class="text-neutral-500 shrink-0 font-mono">x{{ $item->quantity }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif

          <!-- 3 Bước Hướng Dẫn Thanh Toán ATM (Không cần mã QR) -->
          <div class="bg-neutral-50 p-4 rounded-2xl border border-neutral-200 text-[11px] text-neutral-600 space-y-2">
            <h4 class="font-bold text-neutral-900 uppercase text-[10px] tracking-wider text-[#a50064] flex items-center gap-1.5">
              <i data-lucide="info" class="w-3.5 h-3.5"></i>
              <span>Quy trình thanh toán ATM (Không dùng mã QR)</span>
            </h4>
            <div class="space-y-1.5 leading-relaxed">
              <div><strong class="text-neutral-900">Bước 1:</strong> Chọn ngân hàng phát hành thẻ ATM của bạn ở khung bên cạnh.</div>
              <div><strong class="text-neutral-900">Bước 2:</strong> Bấm <strong>"Bắt Đầu Thanh Toán MoMo ATM"</strong> để chuyển sang Cổng MoMo Sandbox.</div>
              <div><strong class="text-neutral-900">Bước 3:</strong> Nhập mã <strong>OTP</strong> gửi về để hoàn tất đơn hàng và tự động quay về trang thành công!</div>
            </div>
          </div>

        </div>

        <!-- ============================================================== -->
        <!-- CỘT 2 (7 COLS): CHỌN NGÂN HÀNG & KHỞI TẠO THANH TOÁN MOMO -->
        <!-- ============================================================== -->
        <div class="lg:col-span-7 space-y-5">

          <!-- FORM KHỞI TẠO THANH TOÁN MOMO ATM GỐC (PAYWITHATM) -->
          <div class="p-6 bg-white rounded-2xl border border-neutral-200 shadow-sm space-y-5">

            <div class="border-b border-neutral-100 pb-3">
              <h3 class="font-bold text-base text-neutral-900 flex items-center justify-between">
                <span>Chọn ngân hàng phát hành thẻ ATM</span>
                <span class="text-[11px] font-normal text-emerald-600 flex items-center gap-1">
                  <i data-lucide="check" class="w-3.5 h-3.5"></i> NAPAS 24/7 Hoạt Động
                </span>
              </h3>
              <p class="text-neutral-500 text-[11px] mt-0.5">
                Chọn ngân hàng của bạn để thanh toán qua cổng MoMo (Không cần mở app quét mã QR):
              </p>
            </div>

            <!-- Form Submit MoMo ATM Payment -->
            <form action="{{ route('client.checkout.momo', $order->order_code) }}" method="POST" id="momoPaymentForm" class="space-y-5">
              @csrf

              <!-- Các tham số MoMo Gateway V2 (Hidden theo chuẩn video & sample) -->
              <input type="hidden" name="partnerCode" value="{{ $partnerCode }}">
              <input type="hidden" name="accessKey" value="{{ $accessKey }}">
              <input type="hidden" name="secretKey" value="{{ $secretKey }}">
              <input type="hidden" name="orderId" value="{{ $orderId }}">
              <input type="hidden" name="extraData" value="{{ $extraData }}">
              <input type="hidden" name="orderInfo" value="{{ $orderInfo }}">
              <input type="hidden" name="amount" value="{{ $amount }}">
              <input type="hidden" name="ipnUrl" value="{{ $ipnUrl }}">
              <input type="hidden" name="redirectUrl" value="{{ $redirectUrl }}">

              <!-- Grid 12 Ngân hàng ATM Nội Địa -->
              @php
                $banks = [
                  ['code' => 'VCB', 'name' => 'Vietcombank', 'logo' => 'vcb.png'],
                  ['code' => 'TCB', 'name' => 'Techcombank', 'logo' => 'tcb.png'],
                  ['code' => 'MBB', 'name' => 'MB Bank', 'logo' => 'mb.png'],
                  ['code' => 'CTG', 'name' => 'VietinBank', 'logo' => 'ctg.png'],
                  ['code' => 'BIDV', 'name' => 'BIDV', 'logo' => 'bidv.png'],
                  ['code' => 'VBA', 'name' => 'Agribank', 'logo' => 'vba.png'],
                  ['code' => 'ACB', 'name' => 'ACB', 'logo' => 'acb.png'],
                  ['code' => 'VPB', 'name' => 'VPBank', 'logo' => 'vpb.png'],
                  ['code' => 'TPB', 'name' => 'TPBank', 'logo' => 'tpb.png'],
                  ['code' => 'STB', 'name' => 'Sacombank', 'logo' => 'stb.png'],
                  ['code' => 'HDB', 'name' => 'HDBank', 'logo' => 'hdb.png'],
                  ['code' => 'SHB', 'name' => 'SHB', 'logo' => 'shb.png'],
                ];
              @endphp

              <div class="grid grid-cols-3 sm:grid-cols-4 gap-2.5" id="bankSelectorGrid">
                @foreach($banks as $index => $b)
                  <label class="bank-item relative flex flex-col items-center justify-center p-2.5 rounded-xl border-2 transition-all cursor-pointer text-center bg-white hover:border-[#a50064] {{ $index === 0 ? 'border-[#a50064] bg-pink-50/40 shadow-xs' : 'border-neutral-200 hover:bg-neutral-50' }}">
                    <input type="radio" name="bank_code" value="{{ $b['code'] }}" class="sr-only" {{ $index === 0 ? 'checked' : '' }} onchange="selectBank(this)">
                    <img src="{{ asset('assets/img/banks/' . $b['logo']) }}" alt="{{ $b['name'] }}" class="h-6 max-w-full object-contain mb-1">
                    <span class="text-[10px] font-bold text-neutral-800 block truncate w-full">{{ $b['name'] }}</span>
                  </label>
                @endforeach
              </div>

              <!-- Thẻ nhắc thông tin thẻ ATM Demo MoMo Sandbox -->
              <div class="p-3.5 rounded-xl bg-pink-50/60 border border-pink-200 text-neutral-700 text-[11px] flex items-start gap-2.5">
                <i data-lucide="shield-alert" class="w-4 h-4 text-[#a50064] shrink-0 mt-0.5"></i>
                <div class="space-y-0.5">
                  <span class="font-bold text-[#a50064]">Thông tin Thẻ ATM Test MoMo Sandbox (Không cần QR):</span>
                  <p class="text-neutral-600">
                    Số thẻ: <strong class="font-mono text-neutral-900">9704000000000018</strong> &bull; 
                    Tên chủ thẻ: <strong class="text-neutral-900">NGUYEN VAN A</strong> &bull; 
                    Ngày cấp: <strong class="font-mono text-neutral-900">03/20</strong> &bull; 
                    Mã OTP: <strong class="font-mono text-[#a50064]">000000</strong> hoặc <strong class="font-mono text-[#a50064]">123456</strong>
                  </p>
                </div>
              </div>

              <!-- NÚT BẤM THANH TOÁN CHÍNH (START MOMO PAYMENT) -->
              <div class="space-y-2.5 pt-1">
                <button type="submit" name="submit_action" value="momo_gateway" id="btnStartMomo"
                        class="w-full py-3.5 px-6 rounded-xl text-white font-bold text-sm uppercase tracking-wider shadow-lg hover:opacity-95 transition-all cursor-pointer flex items-center justify-center gap-2"
                        style="background-color: #a50064;">
                  <i data-lucide="credit-card" class="w-4 h-4"></i>
                  <span>Bắt Đầu Thanh Toán MoMo ATM (Chuyển Sang Cổng MoMo)</span>
                </button>

                <!-- NÚT BẤM TEST NHANH OTP (MÔ PHỎNG NỘI BỘ) -->
                <button type="submit" name="submit_action" value="direct_otp"
                        class="w-full py-2.5 px-4 rounded-xl text-[#a50064] bg-pink-50 hover:bg-pink-100 border border-pink-300 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 transition-colors cursor-pointer">
                  <i data-lucide="key" class="w-3.5 h-3.5"></i>
                  <span>Chuyển Sang Trang Xác Thực OTP (Mô Phỏng 3D-Secure Test)</span>
                </button>
              </div>

            </form>
          </div>

          <!-- ============================================================== -->
          <!-- KHỐI COLLAPSIBLE: KIỂM TRA TRẠNG THÁI GIAO DỊCH & DEBUGGER -->
          <!-- ============================================================== -->
          <div class="border border-neutral-200 rounded-2xl overflow-hidden bg-white shadow-2xs">
            <button type="button" onclick="toggleDebuggerSection()" class="w-full p-4 bg-neutral-50 hover:bg-neutral-100 flex items-center justify-between text-left text-xs font-bold text-neutral-800 transition-colors">
              <span class="flex items-center gap-2">
                <i data-lucide="terminal" class="w-4 h-4 text-[#a50064]"></i>
                <span>Công Cụ Kiểm Tra Trạng Thái Giao Dịch &amp; Debugger MoMo</span>
              </span>
              <i data-lucide="chevron-down" id="chevronDebugger" class="w-4 h-4 text-neutral-500 transition-transform"></i>
            </button>

            <div id="debuggerSection" class="p-5 space-y-4 {{ !empty($response) ? 'block' : 'hidden' }}">
              
              <!-- Form Query Transaction -->
              <form action="{{ route('client.checkout.momo', $order->order_code) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="action_type" value="query">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <label class="block text-[10px] uppercase font-bold text-neutral-500 mb-1">Partner Code</label>
                    <input type="text" name="partnerCode" value="{{ $partnerCode }}" class="w-full px-3 py-2 bg-neutral-100 border border-neutral-200 rounded-lg text-xs font-mono" readonly>
                  </div>
                  <div>
                    <label class="block text-[10px] uppercase font-bold text-neutral-500 mb-1">Mã đơn hàng cần kiểm tra</label>
                    <input type="text" name="orderId" value="{{ $orderId }}" class="w-full px-3 py-2 bg-white border border-neutral-300 rounded-lg text-xs font-mono focus:border-[#a50064] focus:outline-none">
                  </div>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 bg-neutral-800 hover:bg-neutral-900 text-white font-bold text-xs uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5">
                  <i data-lucide="search" class="w-3.5 h-3.5"></i>
                  <span>Check Payment (Truy Vấn MoMo API Query)</span>
                </button>
              </form>

              <!-- Khung Debugger Response -->
              <div>
                <span class="block text-[10px] uppercase font-bold text-neutral-500 mb-1">Response JSON từ máy chủ MoMo:</span>
                <pre class="bg-neutral-900 text-emerald-400 p-3.5 rounded-xl font-mono text-[11px] overflow-x-auto max-h-64 leading-relaxed border border-neutral-800">{{ $response ?? "Chưa có dữ liệu phản hồi. Nhấn \"Check Payment\" để tra cứu giao dịch." }}</pre>
              </div>

            </div>
          </div>

          <!-- Nút quay lại đơn hàng -->
          <div class="text-center pt-1">
            <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="text-xs text-neutral-500 hover:text-neutral-900 font-semibold transition-colors inline-flex items-center gap-1">
              <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
              <span>Quay lại theo dõi thông tin đơn hàng #{{ $order->order_code }}</span>
            </a>
          </div>

        </div>

      </div>

    </div>

  </div>

</main>
@endsection

@push('scripts')
<script>
  // Chọn ngân hàng ATM
  function selectBank(radio) {
    document.querySelectorAll('.bank-item').forEach(item => {
      item.classList.remove('border-[#a50064]', 'bg-pink-50/40', 'shadow-xs');
      item.classList.add('border-neutral-200');
    });
    const parent = radio.closest('.bank-item');
    if (parent) {
      parent.classList.remove('border-neutral-200');
      parent.classList.add('border-[#a50064]', 'bg-pink-50/40', 'shadow-xs');
    }
  }

  // Bật / tắt khung Debugger
  function toggleDebuggerSection() {
    const el = document.getElementById('debuggerSection');
    const chevron = document.getElementById('chevronDebugger');
    if (el) {
      el.classList.toggle('hidden');
      if (chevron) chevron.classList.toggle('rotate-180');
    }
  }

  // Đếm ngược 15 phút
  let sec = 15 * 60 - 1;
  const timer = setInterval(() => {
    sec--;
    if (sec <= 0) {
      clearInterval(timer);
      const cdEl = document.getElementById('momoCountdown');
      if (cdEl) cdEl.textContent = '00:00 (Hết hạn)';
      return;
    }
    const m = String(Math.floor(sec / 60)).padStart(2, '0');
    const s = String(sec % 60).padStart(2, '0');
    const cdEl = document.getElementById('momoCountdown');
    if (cdEl) cdEl.textContent = `${m}:${s}`;
  }, 1000);

  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endpush

