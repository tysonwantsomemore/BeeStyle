@extends('layouts.client')

@section('title', 'Payment Status / Kết Quả Thanh Toán MoMo' . ($order ? ' #' . $order->order_code : ''))

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-10 px-4 sm:px-6 lg:px-8 font-sans">
  <div class="max-w-5xl mx-auto space-y-8">

    <!-- ========================================================================= -->
    <!-- THẺ KẾT QUẢ THANH TOÁN CHÍNH (MAIN PAYMENT RESULT CARD) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-3xl shadow-[0_10px_40px_rgba(0,0,0,0.06)] border border-neutral-200/80 overflow-hidden">
      
      <!-- HEADER BANNER BRANDING MOMO -->
      <div class="p-6 sm:p-8 text-white relative overflow-hidden" 
           style="background: linear-gradient(135deg, #a50064 0%, #d82d8b 50%, #b81772 100%);">
        
        <!-- Họa tiết lượn sóng mờ nền -->
        <div class="absolute -right-16 -bottom-16 w-64 h-64 rounded-full border-8 border-white/10 pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-48 h-48 rounded-full bg-white/5 blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-white text-[#a50064] flex flex-col items-center justify-center font-black leading-none shadow-md shrink-0">
              <span class="text-xs tracking-tighter">mo</span>
              <span class="text-xs tracking-tighter">mo</span>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-xs">
                  MoMo ATM / Napas Gateway
                </span>
                <span class="text-pink-200 text-xs">• Sandbox v2 API</span>
              </div>
              <h1 class="text-xl sm:text-2xl font-black text-white mt-1 tracking-tight">
                Payment Status / Kết Quả Thanh Toán
              </h1>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="px-3.5 py-1.5 rounded-xl bg-black/20 border border-white/15 text-right backdrop-blur-xs">
              <span class="text-[10px] uppercase text-pink-200 block font-semibold">Mã đơn hàng</span>
              <strong class="font-mono text-sm font-bold text-white tracking-wide">
                {{ $order ? $order->order_code : ($orderId ?: 'N/A') }}
              </strong>
            </div>
          </div>
        </div>

      </div>

      <!-- MAIN CONTENT AREA -->
      <div class="p-6 sm:p-8 space-y-6">

        <!-- ========================================================================= -->
        <!-- 1. KHỐI TRẠNG THÁI GIAO DỊCH (STATUS ALERT BANNER) -->
        <!-- ========================================================================= -->
        @if($isSuccess)
          <div class="p-5 sm:p-6 rounded-2xl bg-emerald-50/80 border border-emerald-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                <i data-lucide="check-circle-2" class="w-7 h-7"></i>
              </div>
              <div>
                <span class="text-xs uppercase tracking-wider font-bold text-emerald-800 block">Trạng thái thanh toán</span>
                <h3 class="text-lg font-black text-emerald-950">GIAO DỊCH THÀNH CÔNG (Capture Payment Success)</h3>
                <p class="text-xs text-emerald-700 mt-0.5">
                  {{ $localMessage ?: ($message ?: 'Giao dịch qua Cổng MoMo ATM đã được xác thực thành công.') }}
                </p>
              </div>
            </div>
            <div class="text-right shrink-0">
              <span class="text-[11px] text-neutral-500 block">Số tiền thanh toán</span>
              <span class="font-mono text-xl sm:text-2xl font-black text-emerald-700">
                {{ number_format((float)$amount, 0, ',', '.') }}đ
              </span>
            </div>
          </div>
        @elseif(!$isSignatureValid)
          <div class="p-5 sm:p-6 rounded-2xl bg-rose-50/90 border border-rose-300 flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
              <i data-lucide="shield-alert" class="w-7 h-7"></i>
            </div>
            <div>
              <span class="text-xs uppercase tracking-wider font-bold text-rose-800 block">Cảnh báo bảo mật</span>
              <h3 class="text-lg font-black text-rose-950">LỖI XÁC THỰC CHỮ KÝ (Fail Checksum / Invalid Signature)</h3>
              <p class="text-xs text-rose-800 mt-1">
                Chữ ký số trả về từ MoMo không khớp với chữ ký tính toán của Partner. Giao dịch có thể bị can thiệp hoặc sai SecretKey.
              </p>
            </div>
          </div>
        @else
          <div class="p-5 sm:p-6 rounded-2xl bg-amber-50/80 border border-amber-300 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center text-2xl shadow-sm shrink-0">
                <i data-lucide="alert-circle" class="w-7 h-7"></i>
              </div>
              <div>
                <span class="text-xs uppercase tracking-wider font-bold text-amber-900 block">Trạng thái thanh toán</span>
                <h3 class="text-lg font-black text-amber-950">
                  {{ (int)$resultCode === 1006 ? 'GIAO DỊCH BỊ HỦY BỞI NGƯỜI DÙNG' : 'THANH TOÁN KHÔNG THÀNH CÔNG' }}
                </h3>
                <p class="text-xs text-amber-800 mt-0.5">
                  <strong>Mã lỗi: {{ $errorCode ?: $resultCode }}</strong> — {{ $localMessage ?: ($message ?: 'Giao dịch chưa hoàn tất hoặc người dùng đã hủy.') }}
                </p>
              </div>
            </div>
            <div class="text-right shrink-0">
              <span class="text-[11px] text-neutral-500 block">Số tiền yêu cầu</span>
              <span class="font-mono text-xl sm:text-2xl font-black text-neutral-800">
                {{ number_format((float)$amount, 0, ',', '.') }}đ
              </span>
            </div>
          </div>
        @endif

        <!-- ========================================================================= -->
        <!-- 2. BẢNG CHI TIẾT 12 TRƯỜNG THAM SỐ (THEO RESULT_ATM.PHP) -->
        <!-- ========================================================================= -->
        <div class="space-y-3">
          <div class="flex items-center justify-between border-b border-neutral-200 pb-2">
            <h4 class="text-xs uppercase tracking-wider font-bold text-neutral-700 flex items-center gap-2">
              <i data-lucide="list-filter" class="w-4 h-4 text-[#a50064]"></i>
              Chi Tiết Tham Số Phản Hồi MoMo ATM (Result Parameters)
            </h4>
            <span class="text-[11px] text-neutral-400 font-mono">12 fields</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5 text-xs">
            
            <!-- PartnerCode -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">PartnerCode</label>
              <input type="text" readonly value="{{ $partnerCode }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 font-semibold focus:outline-none select-all">
            </div>

            <!-- AccessKey -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">AccessKey</label>
              <input type="text" readonly value="{{ $accessKey }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 font-semibold focus:outline-none select-all">
            </div>

            <!-- OrderId -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">OrderId</label>
              <input type="text" readonly value="{{ $orderId }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 font-bold focus:outline-none select-all text-[#a50064]">
            </div>

            <!-- TransId -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">transId (Mã GD MoMo)</label>
              <input type="text" readonly value="{{ $transId ?: 'N/A' }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 font-bold focus:outline-none select-all">
            </div>

            <!-- OrderInfo -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">OrderInfo</label>
              <input type="text" readonly value="{{ $orderInfo }}" class="w-full bg-transparent text-xs text-neutral-900 font-medium focus:outline-none select-all">
            </div>

            <!-- OrderType -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">orderType</label>
              <input type="text" readonly value="{{ $orderType }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 focus:outline-none select-all">
            </div>

            <!-- Amount -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">Amount (VNĐ)</label>
              <input type="text" readonly value="{{ $amount }}" class="w-full bg-transparent font-mono text-xs font-black text-emerald-700 focus:outline-none select-all">
            </div>

            <!-- Message -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">Message</label>
              <input type="text" readonly value="{{ $message }}" class="w-full bg-transparent text-xs text-neutral-900 focus:outline-none select-all">
            </div>

            <!-- LocalMessage -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">localMessage</label>
              <input type="text" readonly value="{{ $localMessage }}" class="w-full bg-transparent text-xs text-neutral-900 focus:outline-none select-all">
            </div>

            <!-- PayType -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">payType</label>
              <input type="text" readonly value="{{ $payType }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 uppercase font-semibold focus:outline-none select-all">
            </div>

            <!-- ExtraData -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">ExtraData</label>
              <input type="text" readonly value="{{ $extraData ?: 'None' }}" class="w-full bg-transparent font-mono text-xs text-neutral-900 focus:outline-none select-all truncate">
            </div>

            <!-- ResponseTime / RequestId -->
            <div class="p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">RequestId / ResponseTime</label>
              <input type="text" readonly value="{{ $requestId }} ({{ $responseTime }})" class="w-full bg-transparent font-mono text-xs text-neutral-900 focus:outline-none select-all truncate">
            </div>

            <!-- Signature (Full width) -->
            <div class="md:col-span-3 p-3 bg-neutral-50 rounded-xl border border-neutral-200">
              <label class="text-[10px] uppercase font-bold text-neutral-500 block mb-1">signature (MoMo Checksum)</label>
              <input type="text" readonly value="{{ $m2signature }}" class="w-full bg-transparent font-mono text-[11px] text-neutral-800 focus:outline-none select-all">
            </div>

          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- 3. CÁC NÚT ĐIỀU HƯỚNG (ACTION BUTTONS) -->
        <!-- ========================================================================= -->
        <div class="pt-4 border-t border-neutral-200 flex flex-wrap items-center justify-between gap-3">
          <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('client.home') }}" class="px-5 py-2.5 rounded-xl bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs transition-all shadow-sm flex items-center gap-2">
              <i data-lucide="shopping-bag" class="w-4 h-4"></i>
              <span>Back to continue payment... (Về Trang Chủ)</span>
            </a>

            @if($order)
              <a href="{{ route('client.order-tracking', ['code' => $order->order_code]) }}" class="px-4 py-2.5 rounded-xl bg-pink-50 hover:bg-pink-100 text-[#a50064] font-bold text-xs border border-pink-200 transition-all flex items-center gap-1.5">
                <i data-lucide="truck" class="w-4 h-4"></i>
                <span>Tra Cứu Đơn Hàng #{{ $order->order_code }}</span>
              </a>
            @endif

            @if(!$isSuccess && $order)
              <form action="{{ route('client.checkout.momo.redirect', $order->order_code) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2.5 rounded-xl bg-[#d82d8b] hover:bg-[#b81772] text-white font-bold text-xs transition-all flex items-center gap-1.5 shadow-sm">
                  <i data-lucide="rotate-cw" class="w-4 h-4"></i>
                  <span>Thử Thanh Toán Lại</span>
                </button>
              </form>
            @endif
          </div>

          <a href="{{ route('payment.momo.query', ['orderId' => $orderId]) }}" class="text-neutral-500 hover:text-neutral-900 text-xs font-semibold flex items-center gap-1">
            <i data-lucide="search-check" class="w-3.5 h-3.5"></i>
            <span>Tra cứu trực tiếp (Query API)</span>
          </a>
        </div>

      </div>

    </div>

    <!-- ========================================================================= -->
    <!-- 4. KHỐI DEBUGGER CHECK CHỮ KÝ (THEO RESULT_ATM.PHP) -->
    <!-- ========================================================================= -->
    <div class="bg-white rounded-3xl shadow-sm border border-neutral-200 overflow-hidden">
      <div class="p-4 sm:p-5 bg-neutral-100/80 border-b border-neutral-200 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i data-lucide="terminal" class="w-4 h-4 text-neutral-700"></i>
          <h3 class="font-bold text-xs text-neutral-900 uppercase tracking-wider">
            Debugger &amp; Checksum Verification (Chuẩn Developer atm/result_atm.php)
          </h3>
        </div>
        @if($isSignatureValid)
          <span class="px-3 py-1 rounded-full text-[11px] font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
            <i data-lucide="check" class="w-3.5 h-3.5"></i> Pass Checksum
          </span>
        @else
          <span class="px-3 py-1 rounded-full text-[11px] font-mono font-bold bg-rose-100 text-rose-800 border border-rose-300 flex items-center gap-1">
            <i data-lucide="x" class="w-3.5 h-3.5"></i> Fail Checksum
          </span>
        @endif
      </div>

      <div class="p-5 sm:p-6 space-y-4 text-xs">
        
        <!-- SecretKey -->
        <div>
          <span class="font-bold text-neutral-700 block mb-1">
            SecretKey: <span class="text-neutral-400 font-normal text-[11px]">(Giá trị cấu hình trên hệ thống)</span>
          </span>
          <div class="p-3 bg-neutral-900 text-pink-300 font-mono text-xs rounded-xl overflow-x-auto select-all">
            {{ $debugger['secretKey'] ?? 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa' }}
          </div>
        </div>

        <!-- RawData -->
        <div>
          <span class="font-bold text-neutral-700 block mb-1">RawData (Chuỗi dữ liệu ký HMAC SHA-256):</span>
          <div class="p-3 bg-neutral-900 text-emerald-300 font-mono text-[11px] rounded-xl overflow-x-auto select-all leading-relaxed whitespace-pre-wrap break-all">
            {{ $debugger['rawHash'] ?? 'N/A' }}
          </div>
        </div>

        <!-- MoMo signature vs Partner signature -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <span class="font-bold text-neutral-700 block mb-1">MoMo signature:</span>
            <div class="p-3 bg-neutral-900 text-amber-300 font-mono text-[11px] rounded-xl overflow-x-auto select-all break-all">
              {{ $debugger['momoSignature'] ?? ($m2signature ?: 'N/A') }}
            </div>
          </div>
          <div>
            <span class="font-bold text-neutral-700 block mb-1">Partner signature (Calculated):</span>
            <div class="p-3 bg-neutral-900 text-cyan-300 font-mono text-[11px] rounded-xl overflow-x-auto select-all break-all">
              {{ $debugger['partnerSignature'] ?? 'N/A' }}
            </div>
          </div>
        </div>

        <!-- Result Alert Box -->
        <div class="pt-2">
          @if($isSignatureValid)
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-900 font-medium flex items-center gap-2">
              <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i>
              <span><strong>INFO:</strong> Pass Checksum — Chữ ký số MoMo và Partner trùng khớp hoàn toàn.</span>
            </div>
          @else
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-rose-900 font-medium flex items-center gap-2">
              <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
              <span><strong>ERROR!:</strong> Fail Checksum — Chữ ký không khớp, vui lòng kiểm tra lại SecretKey hoặc chuỗi tham số RawData.</span>
            </div>
          @endif
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  });
</script>
@endpush