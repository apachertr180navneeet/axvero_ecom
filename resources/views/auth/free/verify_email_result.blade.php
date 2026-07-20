@extends('auth.layouts.authentication')

@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
@php
    $isSuccess = ($type ?? 'success') === 'success';
    $icon = $isSuccess ? 'checkmark.svg' : 'failed.svg';
    $title = $isSuccess ? translate('Verification Successful!') : translate('Verification Failed!');
    $message = $isSuccess
        ? translate('Your account has been verified successfully. You can now access all features and start using our services.')
        : translate("Oops! We couldn't verify your account. Please check your details and try again.");
    $buttonText = $isSuccess ? translate('Start Exploring') : translate('Retry Verification');
    $buttonUrl = $isSuccess ? route('home') : route('verification.notice');
@endphp
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
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 3;
        padding: 2rem 1.75rem;
        box-sizing: border-box;
    }

    .axvero-verify-result-card {
        position: relative;
        width: 100%;
        max-width: 380px;
        background: #fff;
        border-radius: 18px;
        padding: 2.5rem 2rem 2rem;
        text-align: center;
        box-shadow: 0 16px 48px rgba(15, 43, 91, 0.14);
    }

    .axvero-verify-result-close {
        position: absolute;
        top: 14px;
        right: 14px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 16px;
        text-decoration: none;
        border-radius: 50%;
        border: 1px solid #e2e8f0;
        background: #fff;
        transition: background 0.2s ease, color 0.2s ease;
    }

    .axvero-verify-result-close:hover {
        background: #f8fafc;
        color: #64748b;
        text-decoration: none;
    }

    .axvero-verify-result-icon {
        width: 68px;
        height: 68px;
        margin: 0.25rem auto 1.35rem;
        display: block;
    }

    .axvero-verify-result-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: #0f2b5b;
        margin: 0 0 0.85rem;
        line-height: 1.3;
    }

    .axvero-verify-result-text {
        font-size: 0.86rem;
        font-weight: 400;
        color: #64748b;
        line-height: 1.65;
        margin: 0 0 1.75rem;
    }

    .axvero-verify-result-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 46px;
        border: none;
        border-radius: 10px;
        background: #0f2b5b;
        color: #fff;
        font-size: 0.92rem;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 6px 18px rgba(15, 43, 91, 0.22);
    }

    .axvero-verify-result-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(15, 43, 91, 0.28);
        color: #fff;
        text-decoration: none;
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
            padding: 2rem 1.25rem 2.5rem;
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
        .axvero-verify-result-card {
            padding: 2.25rem 1.35rem 1.75rem;
        }

        .axvero-verify-result-title {
            font-size: 1.25rem;
        }
    }
</style>

<div class="axvero-login-page">
    <div class="axvero-login-scene">
        <div class="axvero-login-hero-wrap">
            <img
                src="{{ static_asset('assets/img/demo/login_image.png') }}"
                alt="{{ translate('Login Page Image') }}"
                class="axvero-login-hero-image"
            >
        </div>
        <a href="{{ route('home') }}" class="axvero-login-brand">
            <img src="{{ static_asset('assets/img/demo/AXVERO.png') }}" alt="{{ translate('Site Icon') }}">
        </a>
    </div>

    <div class="axvero-login-panel-col">
        <div class="axvero-login-glass">
            <div class="axvero-verify-result-card">
                <a href="{{ $buttonUrl }}" class="axvero-verify-result-close" aria-label="{{ translate('Close') }}">
                    <i class="las la-times"></i>
                </a>

                <img
                    src="{{ static_asset('assets/img/demo/' . $icon) }}"
                    alt="{{ $title }}"
                    class="axvero-verify-result-icon"
                >

                <h1 class="axvero-verify-result-title">{{ $title }}</h1>
                <p class="axvero-verify-result-text">{{ $message }}</p>

                <a href="{{ $buttonUrl }}" class="axvero-verify-result-btn">{{ $buttonText }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
