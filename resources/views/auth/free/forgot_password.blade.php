<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        width: 68%;
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
        padding-right: 0;
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

    .axvero-login-label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
        display: block;
    }

    .axvero-login-field {
        position: relative;
        margin-bottom: 1rem;
    }

    .axvero-login-input-wrap {
        position: relative;
    }

    .axvero-login-input-wrap .field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 1rem;
        pointer-events: none;
        z-index: 2;
    }

    .axvero-login-input {
        width: 100%;
        border-radius: 10px !important;
        border: 1px solid rgba(255, 255, 255, 0.55) !important;
        background: rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        padding: 0.72rem 2.5rem 0.72rem 2.5rem !important;
        font-size: 0.84rem !important;
        height: 46px;
        transition: border-color 0.2s ease, background 0.2s ease;
        box-sizing: border-box;
    }

    .axvero-login-input::placeholder {
        color: rgba(255, 255, 255, 0.45);
    }

    .axvero-login-input:focus {
        border-color: rgba(255, 255, 255, 0.75) !important;
        background: rgba(255, 255, 255, 0.14) !important;
        box-shadow: none !important;
        outline: none;
    }

    .axvero-login-input.is-invalid {
        border-color: #ff8a8a !important;
    }

    .axvero-login-form .form-group {
        margin-bottom: 0;
    }

    .axvero-login-btn-primary {
        width: 100%;
        border: none;
        border-radius: 10px;
        background: #fff;
        color: #1f2937;
        font-weight: 700;
        font-size: 0.92rem;
        padding: 0.8rem 1rem;
        height: 46px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }

    .axvero-login-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.16);
        color: #1a1a2e;
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

    .invalid-feedback {
        color: #ffc9c9 !important;
        font-size: 0.75rem;
    }

    #phone-code.axvero-login-input {
        padding-left: 2.65rem !important;
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
            padding: 0;
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
            max-width: 100%;
        }

        .axvero-login-title {
            font-size: 1.4rem;
        }
    }
</style>

<div class="axvero-login-page">
    <!-- Left Side Image-->
    <div class="axvero-login-scene">
        <div class="axvero-login-hero-wrap">
            <img
                src="{{ static_asset('assets/img/demo/login_image.png') }}"
                alt="{{ translate('Forgot Password Page Image') }}"
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

            <a href="{{ route('login') }}" class="axvero-login-close" aria-label="{{ translate('Close') }}">
                <i class="las la-times"></i>
            </a>

            <div class="axvero-login-inner">
                <!-- Titles -->
                <div class="axvero-login-header">
                    <h1 class="axvero-login-title">{{ translate('Forgot Your Password?') }}</h1>
                    <p class="axvero-login-subtitle">{{ translate('Don\'t worry, it happens. Enter your registered email to reset your password.') }}</p>
                </div>

                <!-- Send password reset link or code form -->
                <div class="pt-0">
                    <form class="form-default axvero-login-form" id="forgot-pass-form" role="form" action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <!-- Email or Phone -->
                        <div class="form-group phone-form-group axvero-login-field d-none">
                            <label for="phone-code" class="axvero-login-label">{{ translate('Phone') }}</label>
                            <input type="tel" phone-number id="phone-code" class="form-control axvero-login-input {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                        </div>

                        <input type="hidden" name="country_code" value="">

                        <div class="form-group email-form-group axvero-login-field">
                            <label for="email" class="axvero-login-label">{{ translate('Email Address') }}</label>
                            <div class="axvero-login-input-wrap">
                                <i class="las la-envelope field-icon"></i>
                                <input type="email" class="form-control axvero-login-input {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{ translate('Email address') }}" name="email" id="email" autocomplete="off">
                            </div>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        {{-- <div class="form-group text-right">
                            <button class="btn btn-link p-0 text-primary" type="button" onclick="toggleEmailPhone(this)"><i>*{{ translate('Use Email Instead') }}</i></button>
                        </div> --}}

                        <!-- Recaptcha -->
                        @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_forgot_password') == 1)
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback d-block rounded p-2 mb-3" role="alert" style="background: rgba(220,53,69,0.25);">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                            @endif
                        @endif

                        <!-- Submit Button -->
                        <div class="mb-0 mt-4">
                            <button type="submit" class="axvero-login-btn-primary">{{ translate('Send Code') }}</button>
                        </div>
                    </form>
                </div>

                <!-- Go Back -->
                <div class="axvero-login-register">
                    <span>{{ translate('Remember Password?') }}</span>
                    <a href="{{ route('login') }}">{{ translate('Login Now') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

