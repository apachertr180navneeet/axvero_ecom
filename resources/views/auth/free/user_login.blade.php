@extends('auth.layouts.authentication')

@php
    $has_social = get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1;
@endphp

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

    /* Full-page background scene (blue + hero image) */
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

    /* Clip hero to left zone — blue bg shows through, no blend-mode tint */
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

    /* Right: full-height glass login panel (foreground overlay) */
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
        max-width: 320px;
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

    .axvero-login-input-wrap .axvero-login-password-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.7);
        font-size: 1.05rem;
        cursor: pointer;
        z-index: 2;
        line-height: 1;
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

    .axvero-login-input.axvero-login-input-plain {
        padding-left: 1rem !important;
        padding-right: 2.5rem !important;
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

    /* Extra: phone toggle styles (not in reference UI)
    .axvero-login-toggle-phone {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.78rem;
        padding: 0;
        margin-bottom: 0.75rem;
        cursor: pointer;
        text-decoration: underline;
    }

    .axvero-login-toggle-phone:hover {
        color: #fff;
    }
    */

    .axvero-login-form .form-group {
        margin-bottom: 0;
    }

    .axvero-login-password-toggle {
        cursor: pointer;
    }

    .axvero-login-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.25rem;
        margin-bottom: 1.1rem;
        gap: 0.75rem;
    }

    .axvero-login-remember {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        color: rgba(255, 255, 255, 0.88);
        font-size: 0.8rem;
        margin: 0;
        cursor: pointer;
    }

    .axvero-login-remember input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        border: 1.5px solid rgba(255, 255, 255, 0.85);
        border-radius: 3px;
        background: transparent;
        cursor: pointer;
        flex-shrink: 0;
    }

    .axvero-login-remember input[type="checkbox"]:checked {
        background: #fff;
        border-color: #fff;
    }

    .axvero-login-link {
        color: #1a237e;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: color 0.2s ease;
    }

    .axvero-login-link:hover {
        color: #0d1545;
        text-decoration: underline;
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

    .axvero-login-btn-ghost {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.65);
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        font-size: 0.86rem;
        font-weight: 600;
        padding: 0.75rem 1rem;
        height: 46px;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
        box-sizing: border-box;
    }

    .axvero-login-btn-ghost:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.75);
        color: #fff;
        text-decoration: none;
    }

    .axvero-login-divider {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin: 1rem 0;
        color: rgba(255, 255, 255, 0.75);
        font-size: 0.76rem;
    }

    .axvero-login-divider::before,
    .axvero-login-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.28);
    }

    .axvero-login-social-row {
        display: flex;
        gap: 0.5rem;
        flex-wrap: nowrap;
        overflow: visible;
        width: 100%;
    }

    .axvero-social-btn {
        flex: 1 1 0;
        min-width: 0;
        min-height: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.55);
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 0.72rem;
        font-weight: 600;
        line-height: 1;
        padding: 0 0.35rem;
        text-decoration: none;
        transition: background 0.2s ease, border-color 0.2s ease;
        white-space: nowrap;
        box-sizing: border-box;
        overflow: visible;
    }

    .axvero-social-btn:hover {
        background: rgba(255, 255, 255, 0.14);
        border-color: rgba(255, 255, 255, 0.75);
        color: #fff;
        text-decoration: none;
    }

    .axvero-social-btn i,
    .axvero-social-btn svg {
        line-height: 1;
        flex-shrink: 0;
        display: block;
        margin: 0;
        padding: 0;
        vertical-align: middle;
    }

    .axvero-social-btn span {
        line-height: 1;
        display: block;
    }

    .axvero-social-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
        display: block;
    }
    .axvero-login-register {
        text-align: center;
        margin-top: 1.1rem;
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

    /* Extra: demo mode styles (not in reference UI)
    .axvero-login-demo {
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .axvero-login-demo span {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.8rem;
        font-weight: 600;
    }

    .axvero-login-demo button {
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        background: transparent;
        color: #fff;
        font-size: 0.75rem;
        padding: 0.25rem 0.85rem;
    }
    */

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

        .axvero-login-social-row {
            flex-wrap: nowrap;
            gap: 0.35rem;
        }

        .axvero-social-btn {
            flex: 1 1 0;
            font-size: 0.68rem;
            padding: 0.65rem 0.25rem;
            gap: 0.25rem;
        }

        .axvero-social-btn span {
            font-size: 0.68rem;
        }

        .axvero-login-title {
            font-size: 1.4rem;
        }
    }
</style>

<div class="axvero-login-page">
    <div class="axvero-login-scene">
        <div class="axvero-login-hero-wrap">
            <img
                src="{{ static_asset('assets/img/demo/login_image.png') }}"
                alt="{{ translate('Shopping') }}"
                class="axvero-login-hero-image"
            >
        </div>
        <a href="{{ route('home') }}" class="axvero-login-brand">
            <img src="{{ static_asset('assets/img/demo/AXVERO.png') }}" alt="{{ env('APP_NAME') }}">
        </a>
    </div>

    <div class="axvero-login-panel-col">
        <div class="axvero-login-glass">
            <div class="axvero-login-glass-bg"></div>
            <div class="axvero-login-glass-blob"></div>

            <a href="{{ route('home') }}" class="axvero-login-close" aria-label="{{ translate('Close') }}">
                <i class="las la-times"></i>
            </a>

            <div class="axvero-login-inner">
                <div class="axvero-login-header">
                    <h1 class="axvero-login-title">{{ translate('Login to Continue') }}</h1>
                    <p class="axvero-login-subtitle">{{ translate('Enter your details to manage your Order') }}</p>
                </div>

                <form class="loginForm axvero-login-form" id="user-login-form" role="form" action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Phone login (hidden — kept for backend/JS compatibility) --}}
                    <div class="form-group phone-form-group axvero-login-field d-none">
                        <label for="phone-code" class="axvero-login-label">{{ translate('Phone') }}</label>
                        <input type="tel" phone-number id="phone-code" class="form-control axvero-login-input {{ $errors->has('phone') ? 'is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="{{ translate('Phone number') }}" name="phone" autocomplete="off">
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

                    {{-- Extra: email/phone toggle (not in reference UI)
                    <button class="axvero-login-toggle-phone" type="button" onclick="toggleEmailPhone(this)">{{ translate('Use Phone Instead') }}</button>
                    --}}

                    <div class="password-login-block">
                        <div class="form-group axvero-login-field">
                            <label for="password" class="axvero-login-label">{{ translate('Password') }}</label>
                            <div class="axvero-login-input-wrap">
                                <input type="password" class="form-control axvero-login-input axvero-login-input-plain {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ translate('Password') }}" name="password" id="password">
                                <i class="las la-eye axvero-login-password-toggle password-toggle"></i>
                            </div>
                        </div>

                        @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
                            @if ($errors->has('g-recaptcha-response'))
                                <span class="invalid-feedback d-block rounded p-2 mb-3" role="alert" style="background: rgba(220,53,69,0.25);">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </span>
                            @endif
                        @endif

                        <div class="axvero-login-row">
                            <label class="axvero-login-remember mb-0">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span>{{ translate('Remember Me') }}</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="axvero-login-link">{{ translate('Forgot Password?') }}</a>
                        </div>

                        {{-- Extra: OTP login (not in reference UI)
                        @if(get_setting('login_with_otp'))
                            <div class="text-right mb-3">
                                <a href="javascript:void(0);" class="axvero-login-link toggle-login-with-otp" onclick="toggleLoginPassOTP(this)">{{ translate('Login With OTP') }}</a>
                            </div>
                        @endif
                        --}}
                    </div>

                    <button type="submit" class="axvero-login-btn-primary submit-button">{{ translate('Login') }}</button>
                </form>

                {{-- Extra: demo mode helper (not in reference UI)
                @if (env('DEMO_MODE') == 'On')
                    <div class="axvero-login-demo d-flex justify-content-between align-items-center">
                        <span>{{ translate('Demo Customer') }}</span>
                        <button type="button" onclick="autoFillCustomer()">{{ translate('Copy') }}</button>
                    </div>
                @endif
                --}}

                <div class="axvero-login-divider">{{ translate('Or') }}</div>

                <a href="{{ route('home') }}" class="axvero-login-btn-ghost">{{ translate('Continue as Guest') }}</a>

                @if ($has_social)
                    <div class="axvero-login-divider">{{ translate('Or') }}</div>
                    <div class="axvero-login-social-row">
                        @if (get_setting('google_login') == 1)
                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="axvero-social-btn" title="Google">
                                <svg class="axvero-social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <span>Google</span>
                            </a>
                        @endif
                        @if (get_setting('facebook_login') == 1)
                            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="axvero-social-btn" title="Facebook">
                                <svg class="axvero-social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12S0 5.446 0 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.543c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span>Facebook</span>
                            </a>
                        @endif
                        @if (get_setting('apple_login') == 1)
                            <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="axvero-social-btn" title="Apple">
                                <svg class="axvero-social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill="#ffffff" d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z"/>
                                </svg>
                                <span>Apple</span>
                            </a>
                        @endif
                        @if (get_setting('twitter_login') == 1)
                            <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="axvero-social-btn" title="Twitter">
                                <svg class="axvero-social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#ffffff" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span>X</span>
                            </a>
                        @endif
                    </div>
                @endif

                <div class="axvero-login-register">
                    <span>{{ translate('Don\'t have an account?') }}</span>
                    <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{-- Extra: demo autofill (not in reference UI)
    <script type="text/javascript">
        function autoFillCustomer() {
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
    </script>
    --}}

    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        <script type="text/javascript">
            document.getElementById('user-login-form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'login'}).then(function(token) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'g-recaptcha-response');
                        input.setAttribute('value', token);
                        e.target.appendChild(input);

                        var actionInput = document.createElement('input');
                        actionInput.setAttribute('type', 'hidden');
                        actionInput.setAttribute('name', 'recaptcha_action');
                        actionInput.setAttribute('value', 'recaptcha_customer_login');
                        e.target.appendChild(actionInput);

                        e.target.submit();
                    });
                });
            });
        </script>
    @endif
@endsection
