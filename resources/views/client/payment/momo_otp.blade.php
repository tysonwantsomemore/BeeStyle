<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác thực OTP 3D-Secure NAPAS — Đơn hàng #{{ $order->order_code }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logos/momo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f7f7;
            color: #303233;
            margin: 0;
            padding: 0;
        }
        .momo-bg {
            background-color: #a50064;
        }
        .payment-container {
            min-height: calc(100vh - 120px);
            background: #f7f7f7;
            background-image: radial-gradient(#ffd6e7 0.75px, #f7f7f7 0.75px);
            background-size: 16px 16px;
        }
        .otp-box {
            width: 46px;
            height: 52px;
            text-align: center;
            font-size: 24px;
            font-weight: 800;
            font-family: monospace;
            border: 2px solid #d9d9d9;
            border-radius: 10px;
            background: #fafafa;
            transition: all 0.15s ease;
        }
        .otp-box:focus {
            background: #ffffff;
            border-color: #a50064;
            box-shadow: 0 0 0 4px rgba(165, 0, 100, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

<!-- ========================================================================= -->
<!-- 1. HEADER CHUẨN CỔNG THANH TOÁN MOMO GỐC -->
<!-- ========================================================================= -->
<header id="header" class="momo-bg h-15 shadow-md flex items-center shrink-0">
    <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('client.home') }}" class="flex items-center gap-2.5 text-white hover:opacity-95 transition-opacity">
                <!-- SVG LOGO GỐC MOMO -->
                <svg class="w-8 h-8 fill-white" viewBox="0 0 96 87" xmlns="http://www.w3.org/2000/svg">
                    <path d="M75.5326 0C64.2284 0 55.0651 8.74843 55.0651 19.5409C55.0651 30.3333 64.2284 39.0818 75.5326 39.0818C86.8368 39.0818 96 30.3333 96 19.5409C96 8.74843 86.8368 0 75.5326 0ZM75.5326 27.8805C70.7368 27.8805 66.8403 24.1604 66.8403 19.5818C66.8403 15.0031 70.7368 11.283 75.5326 11.283C80.3283 11.283 84.2248 15.0031 84.2248 19.5818C84.2248 24.1604 80.3283 27.8805 75.5326 27.8805ZM49.1561 14.6761V39.1226H37.3809V14.5535C37.3809 12.7138 35.8394 11.2421 33.9126 11.2421C31.9857 11.2421 30.4442 12.7138 30.4442 14.5535V39.1226H18.669V14.5535C18.669 12.7138 17.1276 11.2421 15.2007 11.2421C13.2739 11.2421 11.7324 12.7138 11.7324 14.5535V39.1226H0V14.6761C0 6.58176 6.89385 0 15.372 0C18.8403 0 22.0089 1.10377 24.5781 2.9434C27.1472 1.10377 30.3586 0 33.7841 0C42.2623 0 49.1561 6.58176 49.1561 14.6761ZM75.5326 47.544C64.2284 47.544 55.0651 56.2925 55.0651 67.0849C55.0651 77.8774 64.2284 86.6258 75.5326 86.6258C86.8368 86.6258 96 77.8774 96 67.0849C96 56.2925 86.8368 47.544 75.5326 47.544ZM75.5326 75.4245C70.7368 75.4245 66.8403 71.7044 66.8403 67.1258C66.8403 62.5472 70.7368 58.827 75.5326 58.827C80.3283 58.827 84.2248 62.5472 84.2248 67.1258C84.2248 71.7044 80.3283 75.4245 75.5326 75.4245ZM49.1561 62.2201V86.6667H37.3809V62.0975C37.3809 60.2579 35.8394 58.7862 33.9126 58.7862C31.9857 58.7862 30.4442 60.2579 30.4442 62.0975V86.6667H18.669V62.0975C18.669 60.2579 17.1276 58.7862 15.2007 58.7862C13.2739 58.7862 11.7324 60.2579 11.7324 62.0975V86.6667H0V62.2201C0 54.1258 6.89385 47.544 15.372 47.544C18.8403 47.544 22.0089 48.6478 24.5781 50.4874C27.1472 48.6478 30.3158 47.544 33.7841 47.544C42.2623 47.544 49.1561 54.1258 49.1561 62.2201Z"/>
                </svg>
                <span class="text-white text-lg sm:text-xl font-medium tracking-tight">Cổng thanh toán MoMo</span>
            </a>
        </div>
        <div class="flex items-center gap-2 text-white/90 text-xs">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-black/20 rounded-full border border-white/20">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-300"></i>
                <span>Xác Thực 3D-Secure NAPAS</span>
            </span>
        </div>
    </div>
</header>

<!-- ========================================================================= -->
<!-- 2. NỘI DUNG XÁC THỰC OTP (PAYMENT CONTAINER) -->
<!-- ========================================================================= -->
<main class="payment-container flex-grow py-6 sm:py-8 px-4 sm:px-6">
    <div class="max-w-6xl w-full mx-auto space-y-5">

        <!-- Thông báo xác thực bảo mật -->
        <div class="bg-[#fff0f6] border border-[#ffadd2] rounded-xl px-4 py-3 text-xs text-[#a50064] flex items-center gap-2.5 shadow-2xs">
            <i data-lucide="shield" class="w-4 h-4 shrink-0 text-[#eb2f96]"></i>
            <span class="font-medium leading-relaxed">
                Giao dịch đang được bảo vệ bởi chuẩn bảo mật 3D-Secure NAPAS. Vui lòng không chia sẻ mã OTP cho bất kỳ ai.
            </span>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 text-xs text-rose-700 flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- CỘT TRÁI (4 CỘT): THÔNG TIN ĐƠN HÀNG & THẺ ĐÃ NHẬP -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-2xl border border-neutral-200 p-5 shadow-xs space-y-4">
                    <h2 class="font-bold text-base text-neutral-900 border-b border-neutral-100 pb-3">
                        Thông tin giao dịch
                    </h2>

                    <!-- Ngân hàng & Thẻ -->
                    <div class="space-y-1.5">
                        <h4 class="text-[11px] uppercase font-bold text-neutral-400">Ngân hàng phát hành</h4>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-8 rounded-lg border border-neutral-200 bg-white p-1 flex items-center justify-center shrink-0">
                                @if(!empty($cardData['bank_logo']))
                                    <img src="{{ asset('assets/img/banks/' . $cardData['bank_logo']) }}" alt="Bank" class="max-h-6 max-w-full object-contain">
                                @else
                                    <i data-lucide="credit-card" class="w-4 h-4 text-neutral-600"></i>
                                @endif
                            </div>
                            <div>
                                <span class="font-bold text-sm text-neutral-900 block">{{ $cardData['bank_name'] ?? 'Thẻ ATM Nội Địa' }}</span>
                                <span class="font-mono text-xs text-neutral-500">{{ $cardData['card_masked'] ?? '•••• •••• •••• 8888' }}</span>
                            </div>
                        </div>
                    </div>

                    <hr class="border-neutral-100">

                    <!-- Mã đơn hàng -->
                    <div class="space-y-1">
                        <h4 class="text-[11px] uppercase font-bold text-neutral-400">Mã đơn hàng</h4>
                        <p class="font-mono font-bold text-xs text-neutral-900">#{{ $order->order_code }}</p>
                    </div>

                    <!-- Mã tham chiếu NAPAS -->
                    <div class="space-y-1">
                        <h4 class="text-[11px] uppercase font-bold text-neutral-400">Mã tham chiếu NAPAS</h4>
                        <p class="font-mono text-xs text-neutral-700">{{ $cardData['ref_id'] ?? 'NPS_' . strtoupper(\Illuminate\Support\Str::random(10)) }}</p>
                    </div>

                    <!-- Chủ thẻ -->
                    <div class="space-y-1 text-xs">
                        <h4 class="text-[11px] uppercase font-bold text-neutral-400">Chủ thẻ</h4>
                        <p class="text-neutral-900 font-bold uppercase">{{ $cardData['card_holder'] ?? $order->customer_name }}</p>
                    </div>

                    <hr class="border-neutral-100">

                    <!-- Số tiền -->
                    <div class="flex justify-between items-baseline pt-1">
                        <h4 class="text-xs uppercase font-bold text-neutral-500">Số tiền</h4>
                        <h3 class="font-bold text-2xl text-[#a50064]">
                            {{ number_format($order->is_deposit_required ? $order->deposit_amount : $order->total_amount, 0, ',', '.') }}đ
                        </h3>
                    </div>
                </div>

                <!-- Đồng hồ đếm ngược hiệu lực mã OTP -->
                <div class="bg-white rounded-2xl border border-neutral-200 p-4 shadow-xs text-center">
                    <p class="text-xs text-neutral-600 mb-1.5">
                        Mã OTP có hiệu lực trong:
                    </p>
                    <div class="font-mono font-black text-2xl text-[#a50064]" id="otpCountdown">
                        02:00
                    </div>
                </div>

                <div class="text-center text-xs">
                    <a href="{{ route('client.checkout.momo', $order->order_code) }}" class="text-[#a50064] hover:underline font-semibold flex items-center justify-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Quay lại trang nhập thẻ</span>
                    </a>
                </div>
            </div>

            <!-- CỘT PHẢI (8 CỘT): KHUNG XÁC THỰC MÃ OTP -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-neutral-200 p-6 sm:p-8 shadow-xs space-y-6">

                <!-- Header 3 bên liên kết: MoMo + NAPAS + Bank Logo -->
                <div class="text-center pb-4 border-b border-neutral-100 space-y-2">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <img src="{{ asset('assets/img/logos/momo.png') }}" alt="MoMo" class="h-6 object-contain">
                        <span class="text-neutral-300">•</span>
                        <span class="px-2 py-0.5 bg-neutral-900 text-white font-mono font-bold text-[10px] rounded tracking-wider">NAPAS 24/7</span>
                        <span class="text-neutral-300">•</span>
                        @if(!empty($cardData['bank_logo']))
                            <img src="{{ asset('assets/img/banks/' . $cardData['bank_logo']) }}" alt="{{ $cardData['bank_name'] ?? 'Bank' }}" class="h-5 object-contain">
                        @else
                            <span class="font-bold text-xs">{{ $cardData['bank_name'] ?? 'Ngân Hàng' }}</span>
                        @endif
                    </div>
                    <h2 class="font-bold text-lg text-neutral-950 uppercase">Xác thực mật khẩu một lần (OTP)</h2>
                    <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed">
                        Hệ thống đã gửi mã xác thực 6 chữ số đến số điện thoại đăng ký nhận tin nhắn với thẻ ATM của Quý khách.
                    </p>
                </div>

                <!-- Hộp mã OTP Sandbox Mẫu tiện lợi -->
                <div class="bg-gradient-to-r from-pink-50 to-purple-50 border border-pink-200 rounded-xl p-3.5 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-[#a50064] text-white flex items-center justify-center shrink-0">
                            <i data-lucide="key" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <span class="block text-[10px] uppercase font-bold text-neutral-500">Mã OTP thử nghiệm (Sandbox):</span>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-mono font-black text-base text-[#a50064] bg-white px-2 py-0.5 rounded border border-pink-200">123456</span>
                                <span class="text-[11px] text-neutral-500">(Dùng mã này để hoàn tất thanh toán)</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="fillQuickOtp()" class="px-3 py-1.5 bg-white hover:bg-pink-100 text-[#a50064] font-bold text-xs rounded-lg border border-pink-300 shadow-2xs transition-colors cursor-pointer shrink-0">
                        Điền nhanh 123456
                    </button>
                </div>

                <!-- FORM NHẬP 6 CHỮ SỐ OTP -->
                <form action="{{ route('client.checkout.momo.verify-otp', $order->order_code) }}" method="POST" id="otpForm" class="space-y-6 pt-2">
                    @csrf

                    <div>
                        <label class="block font-bold text-center text-neutral-800 text-xs uppercase mb-3">
                            Nhập mã xác thực gồm 6 chữ số:
                        </label>

                        <div class="flex justify-center items-center gap-2 sm:gap-3">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text"
                                       maxlength="1"
                                       inputmode="numeric"
                                       pattern="[0-9]*"
                                       class="otp-box"
                                       data-index="{{ $i }}"
                                       id="otpBox_{{ $i }}"
                                       autocomplete="off"
                                       @if($i === 0) autofocus @endif>
                            @endfor
                        </div>

                        <input type="hidden" name="otp" id="fullOtpInput" value="">
                    </div>

                    <div class="flex items-center justify-between text-xs px-1 text-neutral-500">
                        <span>Chưa nhận được mã?</span>
                        <button type="button" onclick="resendOtp()" id="btnResend" class="text-[#a50064] font-bold hover:underline cursor-pointer flex items-center gap-1">
                            <i data-lucide="rotate-cw" class="w-3.5 h-3.5"></i>
                            <span>Gửi lại mã OTP</span>
                        </button>
                    </div>

                    <div class="pt-2 space-y-2.5">
                        <button type="submit" id="btnSubmitOtp"
                                class="w-full py-3.5 px-6 rounded-xl text-white font-bold text-sm uppercase tracking-wider shadow-md hover:opacity-95 transition-all cursor-pointer flex items-center justify-center gap-2"
                                style="background-color: #a50064;">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                            <span>Xác Nhận OTP &amp; Hoàn Tất Thanh Toán</span>
                        </button>

                        <a href="{{ route('client.checkout.momo', $order->order_code) }}"
                           class="w-full py-2.5 px-4 rounded-xl text-neutral-600 bg-neutral-100 hover:bg-neutral-200 font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1.5 transition-colors">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                            <span>Hủy Giao Dịch &amp; Quay Lại</span>
                        </a>
                    </div>
                </form>

            </div>

        </div>

    </div>
</main>

<!-- ========================================================================= -->
<!-- 3. FOOTER CHUẨN GỐC CỔNG THANH TOÁN MOMO -->
<!-- ========================================================================= -->
<footer id="footer" class="bg-[#f7f7f7] border-t border-neutral-200 py-4 text-neutral-500 text-xs shrink-0">
    <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-center sm:text-left">
        <div>
            <span>© 2026 - Cổng thanh toán MoMo</span>
        </div>
        <div class="flex items-center gap-4 text-[11px] sm:text-xs">
            <span>Hỗ trợ khách hàng:</span>
            <a href="tel:1900545441" class="text-neutral-700 font-semibold hover:text-[#a50064]">
                1900 54 54 41 (1000đ/phút)
            </a>
            <span>•</span>
            <a href="mailto:hotro@momo.vn" class="text-[#a50064] hover:underline font-semibold">
                hotro@momo.vn
            </a>
        </div>
    </div>
</footer>

<script>
    const otpBoxes = document.querySelectorAll('.otp-box');
    const fullOtpInput = document.getElementById('fullOtpInput');
    const otpForm = document.getElementById('otpForm');

    function syncOtp() {
        let code = '';
        otpBoxes.forEach(b => code += b.value);
        if (fullOtpInput) fullOtpInput.value = code;
    }

    otpBoxes.forEach((box, i) => {
        box.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length >= 1) {
                this.value = this.value.charAt(0);
                if (i < otpBoxes.length - 1) {
                    otpBoxes[i + 1].focus();
                }
            }
            syncOtp();
        });

        box.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && i > 0) {
                otpBoxes[i - 1].focus();
            }
        });

        box.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            if (pasted) {
                for (let k = 0; k < otpBoxes.length && k < pasted.length; k++) {
                    otpBoxes[k].value = pasted.charAt(k);
                }
                const next = Math.min(pasted.length, otpBoxes.length - 1);
                otpBoxes[next].focus();
                syncOtp();
            }
        });
    });

    function fillQuickOtp() {
        const demo = '123456';
        otpBoxes.forEach((box, k) => {
            box.value = demo.charAt(k) || '';
        });
        syncOtp();
        if (otpBoxes[5]) otpBoxes[5].focus();
    }

    if (otpForm) {
        otpForm.addEventListener('submit', function(e) {
            syncOtp();
            if (!fullOtpInput.value || fullOtpInput.value.length < 6) {
                e.preventDefault();
                alert('Vui lòng nhập đủ 6 chữ số mã xác thực OTP.');
                const first = Array.from(otpBoxes).find(b => !b.value);
                if (first) first.focus();
            }
        });
    }

    // 120s Countdown
    let remaining = 120;
    const cdEl = document.getElementById('otpCountdown');
    const cdInterval = setInterval(function() {
        if (remaining <= 0) {
            clearInterval(cdInterval);
            if (cdEl) cdEl.textContent = '00:00 (Hết hạn)';
            return;
        }
        remaining--;
        const m = Math.floor(remaining / 60);
        const s = remaining % 60;
        if (cdEl) {
            cdEl.textContent = (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
        }
    }, 1000);

    function resendOtp() {
        remaining = 120;
        otpBoxes.forEach(b => b.value = '');
        syncOtp();
        if (otpBoxes[0]) otpBoxes[0].focus();
        alert('Mã OTP mới đã được gửi lại thành công (Mã mẫu dùng thử: 123456).');
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    });
</script>
</body>
</html>
