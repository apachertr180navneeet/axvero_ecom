@php
    $inputClass = $inputClass ?? 'form-control rounded-0';
    $btnClass = $btnClass ?? 'btn btn-primary btn-block fw-700 fs-14 rounded-0';
    $labelClass = $labelClass ?? 'fs-12 fw-700 text-soft-dark';
    $formClass = $formClass ?? 'form-default loginForm';
    $passwordToggleClass = $passwordToggleClass ?? 'las la-2x la-eye';
    $socialLoginStyle = $socialLoginStyle ?? 'default';
    $forgotPasswordTop = $forgotPasswordTop ?? false;
    $demoModeStyle = $demoModeStyle ?? 'default';
    $submitText = $submitText ?? 'Login';
@endphp

<form class="{{ $formClass }}" id="user-login-form" role="form" action="{{ route('login') }}" method="POST">
    @csrf

    <div class="form-group mb-3">
        <label for="email" class="{{ $labelClass }}">{{ translate('Email') }}</label>
        <input type="email" class="{{ $inputClass }}{{ $errors->has('email') ? ' is-invalid' : '' }}"
            value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}"
            name="email" id="email" autocomplete="off">
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span>
        @endif
    </div>

    <div class="password-login-block">
        <div class="form-group">
            @if($forgotPasswordTop)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="password" class="{{ $labelClass }} mb-0">{{ translate('Password') }}</label>
                    <a href="{{ route('password.request') }}" class="auth-link fs-13">{{ translate('Forgot password?') }}</a>
                </div>
            @else
                <label for="password" class="{{ $labelClass }}">{{ translate('Password') }}</label>
            @endif
            <div class="position-relative">
                <input type="password" class="{{ $inputClass }}{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    placeholder="{{ translate('Password') }}" name="password" id="password">
                <i class="password-toggle {{ $passwordToggleClass }}"></i>
            </div>
        </div>

        @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_login') == 1)
            @if ($errors->has('g-recaptcha-response'))
                <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </span>
            @endif
        @endif

        @if($forgotPasswordTop)
            <div class="d-flex justify-content-between align-items-center mb-4">
                <label class="aiz-checkbox mb-0">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="has-transition fs-13 fw-500 text-secondary ms-2">{{ translate('Remember Me') }}</span>
                    <span class="aiz-square-check"></span>
                </label>
                @if(get_setting('login_with_otp'))
                    <a href="javascript:void(0);" class="auth-link fs-13 toggle-login-with-otp"
                        onclick="toggleLoginPassOTP(this)">{{ translate('Login With OTP') }}</a>
                @endif
            </div>
        @else
            <div class="row mb-2">
                <div class="col-5">
                    <label class="aiz-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="has-transition fs-12 fw-400 text-gray-dark hov-text-primary">{{ translate('Remember Me') }}</span>
                        <span class="aiz-square-check"></span>
                    </label>
                </div>
                <div class="col-7 text-right">
                    @if(get_setting('login_with_otp'))
                        <a href="javascript:void(0);" class="text-reset fs-12 fw-400 text-gray-dark hov-text-primary toggle-login-with-otp"
                            onclick="toggleLoginPassOTP(this)">{{ translate('Login With OTP') }} / </a>
                    @endif
                    <a href="{{ route('password.request') }}" class="text-reset fs-12 fw-400 text-gray-dark hov-text-primary">
                        <u>{{ translate('Forgot password?') }}</u>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="mb-4 mt-4">
        <button type="submit" class="{{ $btnClass }} submit-button">{{ translate($submitText) }}</button>
    </div>
</form>

@if (env("DEMO_MODE") == "On")
    @if($demoModeStyle == 'free')
        <div class="mt-4 bg-light p-3 rounded">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fs-13 fw-600">{{ translate('Demo Customer') }}</span>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="autoFillCustomer()">{{ translate('Copy') }}</button>
            </div>
        </div>
    @else
        <div class="mb-4">
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <td>{{ translate('Customer Account') }}</td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm" onclick="autoFillCustomer()">{{ translate('Copy credentials') }}</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
@endif

@if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
    @if($socialLoginStyle == 'free')
        <div class="divider my-4">{{ translate('Or continue with') }}</div>
        <div class="d-flex justify-content-center gap-3">
            @if (get_setting('facebook_login') == 1)
                <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="social-btn mx-2" title="Facebook">
                    <i class="lab la-facebook-f" style="color: #1877F2;"></i>
                </a>
            @endif
            @if (get_setting('twitter_login') == 1)
                <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="social-btn mx-2" title="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#1DA1F2" viewBox="0 0 16 16">
                        <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                    </svg>
                </a>
            @endif
            @if(get_setting('google_login') == 1)
                <a href="{{ route('social.login', ['provider' => 'google']) }}" class="social-btn mx-2" title="Google">
                    <i class="lab la-google" style="color: #DB4437;"></i>
                </a>
            @endif
            @if (get_setting('apple_login') == 1)
                <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="social-btn mx-2" title="Apple">
                    <i class="lab la-apple" style="color: #000;"></i>
                </a>
            @endif
        </div>
    @else
        <div class="text-center mb-3">
            <span class="bg-white fs-12 text-gray">{{ translate('Or Login With') }}</span>
        </div>
        <ul class="list-inline social colored text-center mb-4">
            @if (get_setting('facebook_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook">
                        <i class="lab la-facebook-f"></i>
                    </a>
                </li>
            @endif
            @if (get_setting('twitter_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="x-twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#ffffff" viewBox="0 0 16 16" class="mb-2 pb-1">
                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/>
                        </svg>
                    </a>
                </li>
            @endif
            @if(get_setting('google_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google">
                        <i class="lab la-google"></i>
                    </a>
                </li>
            @endif
            @if (get_setting('apple_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple">
                        <i class="lab la-apple"></i>
                    </a>
                </li>
            @endif
        </ul>
    @endif
@endif
