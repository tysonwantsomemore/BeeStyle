@extends('layouts.client')

@section('title', 'Tra Cứu Trạng Thái Giao Dịch MoMo — MoMo Query API')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-10 px-4 sm:px-6 lg:px-8 font-sans">
  <div class="max-w-4xl mx-auto space-y-6">
    
    <!-- HEADER -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-[#a50064] text-white flex flex-col items-center justify-center font-black leading-none shadow-sm shrink-0">
          <span class="text-[10px] tracking-tighter">mo</span>
          <span class="text-[10px] tracking-tighter">mo</span>
        </div>
        <div>
          <span class="text-[10px] font-bold uppercase tracking-wider text-pink-600 block">MoMo Sandbox Query API</span>
          <h1 class="text-xl font-bold text-neutral-900 tracking-tight">
            Kiểm Tra Trạng Thái Giao Dịch MoMo (Query Transaction)
          </h1>
        </div>
      </div>
      <a href="{{ route('client.home') }}" class="text-xs text-neutral-500 hover:text-neutral-900 font-semibold flex items-center gap-1.5">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Về trang chủ</span>
      </a>
    </div>

    <!-- FORM QUERY -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-neutral-200">
      <form action="{{ route('payment.momo.query.submit') }}" method="POST" class="space-y-4 text-xs">
        @csrf
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Partner Code -->
          <div>
            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Partner Code</label>
            <input type="text" name="partnerCode" value="{{ $partnerCode }}" readonly
                   class="w-full bg-neutral-100 border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs text-neutral-600 focus:outline-none cursor-not-allowed">
          </div>

          <!-- Order ID -->
          <div>
            <label class="block text-[11px] font-bold text-neutral-700 mb-1">
              Mã đơn hàng cần kiểm tra (OrderId) <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="orderId" value="{{ $orderId }}" required placeholder="Ví dụ: BEE-20260919-Q74Y_1710000000"
                   class="w-full bg-[#f8fafc] border border-neutral-300 rounded-xl px-3.5 py-2.5 font-mono text-xs text-neutral-900 focus:outline-none focus:border-[#a50064] focus:bg-white transition-all">
          </div>
        </div>

        <div class="pt-2">
          <button type="submit" class="w-full py-3 bg-[#a50064] hover:bg-[#8f0653] active:bg-[#720042] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i>
            <span>Check Payment / Tra Cứu Máy Chủ MoMo</span>
          </button>
        </div>
      </form>
    </div>

    <!-- DEBUGGER RESPONSE AREA -->
    @if(isset($response))
      <div class="bg-white rounded-3xl shadow-sm border border-neutral-200 overflow-hidden">
        <div class="p-4 sm:p-5 bg-neutral-100/80 border-b border-neutral-200 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i data-lucide="terminal" class="w-4 h-4 text-neutral-700"></i>
            <h3 class="font-bold text-xs text-neutral-900 uppercase tracking-wider">
              Debugger / MoMo Server Response
            </h3>
          </div>
          @if($isPassChecksum)
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1">
              <i data-lucide="check" class="w-3 h-3"></i> Pass Checksum
            </span>
          @endif
        </div>

        <div class="p-5 sm:p-6 space-y-3">
          <span class="text-[11px] font-bold text-neutral-700 block">Response JSON từ MoMo Sandbox Gateway:</span>
          <pre class="p-4 bg-neutral-950 text-emerald-400 font-mono text-xs rounded-2xl overflow-x-auto leading-relaxed">{{ $response }}</pre>
        </div>
      </div>
    @endif

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
