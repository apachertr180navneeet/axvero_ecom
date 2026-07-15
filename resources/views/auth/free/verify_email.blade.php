@extends('auth.layouts.authentication')

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<style>
    body {
        margin: 0;
        overflow-x: hidden;
    }

    .axvero-login-page {
        font-family: 'Poppins', sans-serif;
        display: flex;
        min-height: 100vh;
        position: relative;
        background: #5fb2e5;
        overflow: hidden;
    }

    .axvero-login-scene {
        position: absolute;
        inset: 0;
        z-index: 1;
        background: #5fb2e5;
        overflow: hidden;
    }

    .axvero-login-brand {
        position: absolute;
        top: 32px;
        left: 40px;
        z-index: 5;
        display: inline-block;
        line-height: 0;
    }

    .axvero-login-brand img {
        max-height: 72px;
        width: auto;
        object-fit: contain;
        mix-blend-mode: lighten;
    }

    .axvero-login-hero-wrap {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 58%;
        overflow: hidden;
        background: #5fb2e5;
        z-index: 2;
    }

    .axvero-login-hero-image {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 108%;
        height: 100%;
        min-height: 100vh;
        object-fit: cover;
        object-position: 42% bottom;
        transform: translateX(10%);
        pointer-events: none;
        user-select: none;
    }

    .axvero-login-panel-col {
        position: relative;
        z-index: 10;
        margin-left: auto;
        width: 40%;
        min-width: 380px;
        max-width: 520px;
        flex-shrink: 0;
        display: flex;
        align-items: stretch;
        min-height: 100vh;
        padding: 0;
        background: linear-gradient(
            160deg,
            rgba(121, 188, 232, 0.55) 0%,
            rgba(142, 176, 230, 0.5) 28%,
            rgba(176, 164, 222, 0.55) 58%,
            rgba(201, 187, 233, 0.6) 100%
        );
        overflow: hidden;
    }

    .axvero-login-panel-col::before,
    .axvero-login-panel-col::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        z-index: 0;
    }

    .axvero-login-panel-col::before {
        width: 260px;
        height: 260px;
        background: rgba(255, 220, 120, 0.5);
        top: 6%;
        right: 10%;
    }

    .axvero-login-panel-col::after {
        width: 280px;
        height: 280px;
        background: rgba(180, 140, 235, 0.45);
        bottom: 8%;
        left: -5%;
    }

    .axvero-login-glass {
        position: relative;
        width: 100%;
        min-height: 100vh;
        margin: 0;
        border-radius: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 3;
        isolation: isolate;
    }

    .axvero-login-glass-bg {
        position: absolute;
        inset: 0;
        z-index: 1;
        border-radius: 0;
        background: linear-gradient(
            165deg,
            rgba(255, 255, 255, 0.16) 0%,
            rgba(195, 220, 255, 0.1) 40%,
            rgba(215, 200, 245, 0.14) 100%
        );
        backdrop-filter: blur(48px);
        -webkit-backdrop-filter: blur(48px);
        border-left: 1px solid rgba(255, 255, 255, 0.28);
        pointer-events: none;
    }

    .axvero-login-glass-bg::before,
    .axvero-login-glass-bg::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        filter: blur(64px);
        pointer-events: none;
    }

    .axvero-login-glass-bg::before {
        width: 220px;
        height: 220px;
        background: rgba(255, 210, 110, 0.35);
        top: 10%;
        right: 5%;
        left: auto;
    }

    .axvero-login-glass-bg::after {
        width: 240px;
        height: 240px;
        background: rgba(120, 220, 160, 0.28);
        bottom: 12%;
        left: -8%;
    }

    .axvero-login-glass-blob {
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(230, 140, 200, 0.32);
        border-radius: 50%;
        filter: blur(60px);
        bottom: 18%;
        left: 12%;
        pointer-events: none;
        z-index: 1;
    }

    .axvero-login-inner {
        position: relative;
        z-index: 20;
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        padding: 3rem 2.75rem 2.5rem;
    }

    .axvero-login-close {
        position: absolute;
        top: 24px;
        right: 28px;
        z-index: 30;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.9);
        font-size: 18px;
        text-decoration: none;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.35);
        background: rgba(255, 255, 255, 0.1);
        transition: background 0.2s ease, color 0.2s ease;
    }

    .axvero-login-close:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
        text-decoration: none;
    }

    .axvero-login-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .axvero-login-title {
        font-size: 1.85rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.45rem;
        line-height: 1.3;
    }

    .axvero-login-subtitle {
        font-size: 0.84rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 0;
        line-height: 1.55;
        max-width: 340px;
        margin-left: auto;
        margin-right: auto;
    }

    .axvero-login-btn-primary {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border: none;
        border-radius: 10px;
        background: #fff;
        color: #1f2937;
        font-weight: 700;
        font-size: 0.92rem;
        padding: 0.8rem 1rem;
        height: 46px;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }

    .axvero-login-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.16);
        color: #1a1a2e;
        text-decoration: none;
    }

    .axvero-login-btn-primary:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }

    .axvero-verify-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.92);
        margin-bottom: 0.65rem;
    }

    .axvero-otp-row {
        display: flex;
        justify-content: center;
        gap: 0.55rem;
        margin-bottom: 1.35rem;
    }

    .axvero-otp-digit {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.55);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 1.1rem;
        font-weight: 700;
        text-align: center;
        padding: 0;
        outline: none;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .axvero-otp-digit:focus {
        border-color: rgba(255, 255, 255, 0.85);
        background: rgba(255, 255, 255, 0.16);
    }

    .axvero-resend-wrap {
        text-align: center;
        margin-top: 1rem;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.9);
    }

    .axvero-resend-wrap a {
        color: #fff;
        font-weight: 700;
        text-decoration: underline;
        margin-left: 0.25rem;
    }

    .axvero-resend-wrap a.is-disabled {
        pointer-events: none;
        opacity: 0.55;
        text-decoration: none;
    }

    .axvero-verify-error {
        margin-bottom: 1rem;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        background: rgba(220, 53, 69, 0.22);
        border: 1px solid rgba(255, 140, 140, 0.45);
        color: #fff;
        font-size: 0.78rem;
        text-align: center;
    }

    .axvero-verify-success {
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #fff;
        font-size: 0.8rem;
        line-height: 1.5;
        text-align: center;
    }

    .axvero-login-register {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.8rem;
        color: #fff;
        line-height: 1.5;
    }

    .axvero-login-register a {
        color: #1a237e;
        font-weight: 700;
        text-decoration: none;
        margin-left: 0.15rem;
    }

    .axvero-login-register a:hover {
        color: #0d1545;
        text-decoration: underline;
    }

    @media (max-width: 991.98px) {
        .axvero-login-page {
            flex-direction: column;
        }

        .axvero-login-scene {
            position: relative;
            height: 38vh;
            min-height: 280px;
            flex-shrink: 0;
        }

        .axvero-login-hero-wrap {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .axvero-login-hero-image {
            left: 50%;
            transform: translateX(-50%);
            min-height: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center bottom;
        }

        .axvero-login-panel-col {
            width: 100%;
            min-width: 0;
            max-width: none;
            margin-left: 0;
            min-height: auto;
            flex: 1;
            background: linear-gradient(160deg, #79bce8 0%, #b0a4de 58%, #c9bbe9 100%);
        }

        .axvero-login-glass {
            min-height: auto;
            border-radius: 28px 28px 0 0;
        }

        .axvero-login-glass-bg {
            border-radius: 28px 28px 0 0;
            border-left: none;
            border-top: 1px solid rgba(255, 255, 255, 0.32);
        }

        .axvero-login-inner {
            padding: 2.25rem 1.75rem 2rem;
            max-width: 100%;
        }

        .axvero-login-brand {
            left: 20px;
            top: 18px;
        }

        .axvero-login-brand img {
            max-height: 56px;
        }
    }

    @media (max-width: 575.98px) {
        .axvero-login-inner {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .axvero-login-title {
            font-size: 1.4rem;
        }
        .axvero-otp-digit {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .axvero-otp-row {
            gap: 0.4rem;
        }
    }
</style>

<div class="axvero-login-page">
    <!-- Left Side Image-->
    <div class="axvero-login-scene">
        <div class="axvero-login-hero-wrap">
            <img
                src="{{ static_asset('assets/img/demo/login_image.png') }}"
                alt="{{ translate('Password Reset Page Image') }}"
                class="axvero-login-hero-image"
            >
        </div>
        <!-- Site Icon -->
        <a href="{{ route('home') }}" class="axvero-login-brand">
            @if(get_setting('system_logo_white') != null)
                <img src="{{ uploaded_asset(get_setting('system_logo_white')) }}" alt="{{ translate('Site Icon') }}">
            @elseif(get_setting('system_logo_black') != null)
                <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" alt="{{ translate('Site Icon') }}">
            @else
                <img src="{{ static_asset('assets/img/demo/AXVERO.png') }}" alt="{{ translate('Site Icon') }}">
            @endif
        </a>
    </div>

    <!-- Right Side -->
    <div class="axvero-login-panel-col">
        <div class="axvero-login-glass">
            <div class="axvero-login-glass-bg"></div>
            <div class="axvero-login-glass-blob"></div>

            <a href="{{ route('home') }}" class="axvero-login-close" aria-label="{{ translate('Close') }}">
                <i class="las la-times"></i>
            </a>

            <div class="axvero-login-inner">
                <!-- Titles -->
                <div class="axvero-login-header">
                    <h1 class="axvero-login-title">{{ translate('Verify Your Account') }}</h1>
                    <p class="axvero-login-subtitle">{{ translate("We've sent a 6-digit code to your registered email address.") }}</p>
                </div>

                @if (session('resent'))
                    <div class="axvero-verify-success" role="alert">
                        {{ translate('A fresh verification code has been sent to your email address.') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.code.confirm') }}" id="verifyEmailForm">
                    @csrf
                    <input type="hidden" name="verification_code" id="verification_code" value="{{ old('verification_code') }}">

                    <label class="axvero-verify-label" for="otp-1">{{ translate('Enter Code') }}</label>

                    <div class="axvero-otp-row" id="otpInputs">
                        @for ($i = 1; $i <= 6; $i++)
                            <input
                                type="text"
                                id="otp-{{ $i }}"
                                class="axvero-otp-digit"
                                maxlength="1"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                aria-label="{{ translate('Digit') }} {{ $i }}"
                            >
                        @endfor
                    </div>

                    <button type="submit" class="axvero-login-btn-primary" id="verifySubmitBtn" disabled>
                        {{ translate('Verify') }}
                    </button>
                </form>

                <div class="axvero-resend-wrap">
                    <span>{{ translate('Resend Code') }}</span>
                    <a href="{{ route('verification.resend') }}" id="resendCodeLink" class="is-disabled">
                        <span id="resendTimer">01:00</span>
                    </a>
                </div>

                <!-- Go Back -->
                <div class="axvero-login-register">
                    <a href="{{ url()->previous() }}">
                        <i class="las la-arrow-left"></i> {{ translate('Back to Previous Page') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    (function () {
        const digits = Array.from(document.querySelectorAll('.axvero-otp-digit'));
        const hiddenCode = document.getElementById('verification_code');
        const submitBtn = document.getElementById('verifySubmitBtn');
        const resendLink = document.getElementById('resendCodeLink');
        const resendTimer = document.getElementById('resendTimer');
        let remaining = 60;

        function updateHiddenCode() {
            const code = digits.map((input) => input.value).join('');
            hiddenCode.value = code;
            submitBtn.disabled = code.length !== 6;
        }

        digits.forEach((input, index) => {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 1);
                if (this.value && digits[index + 1]) {
                    digits[index + 1].focus();
                }
                updateHiddenCode();
            });

            input.addEventListener('keydown', function (event) {
                if (event.key === 'Backspace' && !this.value && digits[index - 1]) {
                    digits[index - 1].focus();
                }
            });

            input.addEventListener('paste', function (event) {
                event.preventDefault();
                const pasted = (event.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
                pasted.split('').forEach((char, i) => {
                    if (digits[i]) {
                        digits[i].value = char;
                    }
                });
                if (digits[pasted.length - 1]) {
                    digits[pasted.length - 1].focus();
                }
                updateHiddenCode();
            });
        });

        if (hiddenCode.value.length === 6) {
            hiddenCode.value.split('').forEach((char, i) => {
                if (digits[i]) {
                    digits[i].value = char;
                }
            });
            updateHiddenCode();
        } else if (digits[0]) {
            digits[0].focus();
        }

        function formatTimer(seconds) {
            const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
            const secs = String(seconds % 60).padStart(2, '0');
            return mins + ':' + secs;
        }

        const timerInterval = setInterval(function () {
            remaining -= 1;
            resendTimer.textContent = formatTimer(remaining);

            if (remaining <= 0) {
                clearInterval(timerInterval);
                resendLink.classList.remove('is-disabled');
                resendTimer.textContent = '{{ translate('Send again') }}';
            }
        }, 1000);
    })();
</script>
@endsection

