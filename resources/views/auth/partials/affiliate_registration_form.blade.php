@php
    $inputClass = $inputClass ?? 'form-control rounded-0';
    $btnClass = $btnClass ?? 'btn btn-primary btn-block fw-600 rounded-0';
    $labelClass = $labelClass ?? 'fs-12 fw-700 text-soft-dark';
    $passwordToggleClass = $passwordToggleClass ?? 'las la-2x la-eye';
    $socialLoginStyle = $socialLoginStyle ?? 'default';
    $sideBySidePassword = $sideBySidePassword ?? false;
@endphp

<form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST">
    @csrf
    <input type="hidden" name="is_affiliate" value="1">
    <input type="hidden" name="referral_code" value="{{ request('referral_code') }}">

    <div class="form-group">
        <label for="name" class="{{ $labelClass }}">{{ translate('Full Name') }}</label>
        <input type="text" class="{{ $inputClass }}{{ $errors->has('name') ? ' is-invalid' : '' }}"
            value="{{ old('name') }}" placeholder="{{ translate('Full Name') }}" name="name">
        @if ($errors->has('name'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('name') }}</strong></span>
        @endif
    </div>

    <div class="form-group phone-form-group mb-3">
        <label for="phone" class="{{ $labelClass }}">{{ translate('Phone') }}</label>
        <div class="input-group">
            <input type="text" id="phone" class="{{ $inputClass }}{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
        </div>
    </div>

    <div class="form-group email-form-group mb-3">
        <label for="email" class="{{ $labelClass }}">{{ translate('Email') }}</label>
        <div class="input-group">
            <input type="email" class="{{ $inputClass }}{{ $errors->has('email') ? ' is-invalid' : '' }}"
                value="{{ old('email') }}" placeholder="{{ translate('Email') }}" name="email" autocomplete="off">
        </div>
        @if ($errors->has('email'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('email') }}</strong></span>
        @endif
    </div>

    @if($sideBySidePassword)
    <div class="row">
        <div class="col-md-6 form-group mb-3">
            <label for="password" class="{{ $labelClass }}">{{ translate('Password') }}</label>
            <div class="position-relative">
                <input type="password" class="{{ $inputClass }}{{ $errors->has('password') ? ' is-invalid' : '' }}"
                    placeholder="{{ translate('Password') }}" name="password">
                <i class="password-toggle {{ $passwordToggleClass }}"></i>
            </div>
            @if ($errors->has('password'))
                <span class="invalid-feedback d-block" role="alert"><strong>{{ $errors->first('password') }}</strong></span>
            @endif
        </div>
        <div class="col-md-6 form-group mb-3">
            <label for="password_confirmation" class="{{ $labelClass }}">{{ translate('Confirm Password') }}</label>
            <div class="position-relative">
                <input type="password" class="{{ $inputClass }}" placeholder="{{ translate('Confirm Password') }}" name="password_confirmation">
                <i class="password-toggle {{ $passwordToggleClass }}"></i>
            </div>
        </div>
    </div>
    <div class="text-right mb-3 mt-n2">
        <span class="fs-12 text-secondary">{{ translate('Password must contain at least 6 digits') }}</span>
    </div>
    @else
    <div class="form-group mb-0">
        <label for="password" class="{{ $labelClass }}">{{ translate('Password') }}</label>
        <div class="position-relative">
            <input type="password" class="{{ $inputClass }}{{ $errors->has('password') ? ' is-invalid' : '' }}"
                placeholder="{{ translate('Password') }}" name="password">
            <i class="password-toggle {{ $passwordToggleClass }}"></i>
        </div>
        <div class="text-right mt-1">
            <span class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
        </div>
        @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert"><strong>{{ $errors->first('password') }}</strong></span>
        @endif
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="{{ $labelClass }}">{{ translate('Confirm Password') }}</label>
        <div class="position-relative">
            <input type="password" class="{{ $inputClass }}" placeholder="{{ translate('Confirm Password') }}" name="password_confirmation">
            <i class="password-toggle {{ $passwordToggleClass }}"></i>
        </div>
    </div>
    @endif

    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
        @if ($errors->has('g-recaptcha-response'))
            <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
            </span>
        @endif
    @endif

    <div class="mb-3">
        <label class="aiz-checkbox">
            <input type="checkbox" name="checkbox_example_1" required>
            <span>{{ translate('By signing up you agree to our ') }}
                <a href="{{ route('terms') }}" class="fw-500">{{ translate('terms and conditions.') }}</a>
            </span>
            <span class="aiz-square-check"></span>
        </label>
    </div>

    <div class="mb-4 mt-4">
        <button type="submit" class="{{ $btnClass }}" id="createAccountBtn">{{ translate('Create Account') }}</button>
    </div>
</form>

@if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
    @if($socialLoginStyle == 'free')
        <div class="divider my-4">{{ translate('Or Join With') }}</div>
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
            <span class="bg-white fs-12 text-gray">{{ translate('Or Join With') }}</span>
        </div>
        <ul class="list-inline social colored text-center mb-4">
            @if (get_setting('facebook_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="facebook"><i class="lab la-facebook-f"></i></a>
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
                    <a href="{{ route('social.login', ['provider' => 'google']) }}" class="google"><i class="lab la-google"></i></a>
                </li>
            @endif
            @if (get_setting('apple_login') == 1)
                <li class="list-inline-item">
                    <a href="{{ route('social.login', ['provider' => 'apple']) }}" class="apple"><i class="lab la-apple"></i></a>
                </li>
            @endif
        </ul>
    @endif
@endif
