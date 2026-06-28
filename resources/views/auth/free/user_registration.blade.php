@extends('auth.layouts.authentication')

@section('content')
<style>
    .auth-bg {
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
    }
    .auth-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border-radius: 24px;
        padding: 3rem;
        position: relative;
        z-index: 10;
    }
    .auth-input {
        border-radius: 14px !important;
        border: 1.5px solid #e2e8f0;
        padding: 0.85rem 1.25rem;
        background-color: #f8fafc;
        transition: all 0.3s ease;
        font-size: 14px;
    }
    .auth-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        background-color: #ffffff;
    }
    .auth-btn {
        border-radius: 14px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border: none;
        padding: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.25);
        color: #fff !important;
    }
    .auth-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
    }
    .auth-btn:disabled {
        background: #cbd5e1;
        box-shadow: none;
        transform: none;
        cursor: not-allowed;
    }
    .auth-link {
        color: #64748b;
        transition: color 0.2s ease;
        text-decoration: none;
    }
    .auth-link:hover {
        color: #3b82f6;
        text-decoration: underline;
    }
    .social-btn {
        border-radius: 50% !important;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-size: 1.2rem;
    }
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }
    .img-wrapper {
        position: relative;
        overflow: hidden;
    }
    .img-wrapper::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to right, rgba(0,0,0,0.05), rgba(0,0,0,0.3));
        pointer-events: none;
    }
    .decorative-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(60px);
        z-index: 1;
        opacity: 0.6;
    }
    .shape-1 {
        width: 300px; height: 300px;
        background: rgba(59, 130, 246, 0.3);
        top: -100px; right: -50px;
    }
    .shape-2 {
        width: 250px; height: 250px;
        background: rgba(139, 92, 246, 0.2);
        bottom: -50px; left: -50px;
    }
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #94a3b8;
        font-size: 13px;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }
    .divider:not(:empty)::before { margin-right: .5em; }
    .divider:not(:empty)::after { margin-left: .5em; }
</style>

    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-center auth-bg">
        <section class="overflow-hidden" style="min-height:100vh;">
            <div class="row" style="min-height: 100vh;">
                <!-- Left Side Image-->
                <div class="col-xxl-6 col-lg-6 d-none d-lg-block p-0">
                    <div class="h-100 img-wrapper">
                        <img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}" alt="" class="img-fit h-100 w-100" style="object-fit: cover;">
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="col-xxl-6 col-lg-6 d-flex align-items-center justify-content-center position-relative py-5 py-lg-0">
                    <div class="decorative-shape shape-1"></div>
                    <div class="decorative-shape shape-2"></div>
                    
                    <div class="col-sm-10 col-md-8 col-xl-8">
                        <div class="auth-card my-4 my-lg-0">
                            <!-- Site Icon -->
                            <div class="mb-4 text-center">
                                <a href="{{ route('home') }}">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" height="45">
                                </a>
                            </div>
                            
                            <!-- Titles -->
                            <div class="text-center mb-4">
                                <h1 class="fs-28 fw-700 text-dark mb-1">{{ translate('Create an Account')}}</h1>
                                <p class="fs-14 text-secondary">{{ translate('Join us and start your journey')}}</p>
                            </div>
                            
                            <!-- Register form -->
                            <form id="reg-form" role="form" action="{{ route('register') }}" method="POST">
                                @csrf
                                <input type="hidden" name="referral_code" value="{{ request('referral_code') }}">
                                
                                <!-- Name -->
                                <div class="form-group mb-3">
                                    <label for="name" class="fs-13 fw-600 text-dark mb-2">{{  translate('Full Name') }}</label>
                                    <input type="text" class="form-control auth-input {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('John Doe') }}" name="name">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Email or Phone -->
                                <div>
                                    <div id="emailOrPhoneDiv">
                                        <div class="form-group phone-form-group mb-3 ">
                                            <label for="phone" class="fs-13 fw-600 text-dark mb-2">{{ translate('Phone') }}</label>
                                            <div class="input-group registration-iti">
                                                <input type="tel" phone-number id="phone-code" class="form-control auth-input {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" placeholder="" name="phone" autocomplete="off">
                                                @if(get_setting('customer_registration_verify') == '1')
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary" type="button" id="sendOtpPhoneBtn" onclick="sendVerificationCode(this)">
                                                        {{ translate('Verify') }} 
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                
                                        <input type="hidden" id="country_code" name="country_code" value="">
                                
                                        <div class="form-group email-form-group mb-3 d-none">
                                            <label for="email" class="fs-13 fw-600 text-dark mb-2">{{ translate('Email') }}</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control auth-input {{ $errors->has('email') ? ' is-invalid' : '' }} " value="{{ old('email') }}" placeholder="{{ translate('johndoe@example.com') }}" name="email" id="signinAddonEmail" autocomplete="off">
                                                @if(get_setting('customer_registration_verify') == '1')
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary ms-2" type="button" id="sendOtpBtn" onclick="sendVerificationCode(this)">
                                                        {{ translate('Verify') }} 
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                
                                        <div class="text-right mb-3">
                                            <button class="btn btn-link p-0 auth-link fs-13" type="button" onclick="toggleEmailPhone(this)">
                                                {{ translate('Use Email Instead') }}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 d-none">
                                        <label class="fs-13 fw-600 text-dark mb-2" for="verification_code">{{ translate('Verification Code') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control auth-input @error('verification_code') is-invalid @enderror" name="code" id="verification_code" placeholder="{{ translate('Verification Code') }}" maxlength="6">
                                            <div class="input-group-append">
                                                <span class="btn btn-primary" id="verifyOtpBtn">
                                                    <i class="las la-arrow-right"></i> 
                                                </span>
                                            </div>
                                            @error('otp')
                                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <!-- password -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="password" class="fs-13 fw-600 text-dark mb-2">{{  translate('Password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control auth-input {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{  translate('Password') }}" name="password">
                                            <i class="password-toggle las la-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; font-size: 1.2rem;"></i>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <!-- password Confirm -->
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="password_confirmation" class="fs-13 fw-600 text-dark mb-2">{{  translate('Confirm Password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control auth-input" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation">
                                            <i class="password-toggle las la-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; font-size: 1.2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right mb-3 mt-n2">
                                    <span class="fs-12 text-secondary">{{ translate('Password must contain at least 6 digits') }}</span>
                                </div>

                                <!-- Recaptcha -->
                                @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white d-block" role="alert">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                    @endif
                                @endif

                                <!-- Terms and Conditions -->
                                <div class="mb-4">
                                    <label class="aiz-checkbox mb-0">
                                        <input type="checkbox" name="checkbox_example_1" required>
                                        <span class="fs-13 fw-500 text-secondary ms-2">{{ translate('By signing up you agree to our ')}} <a href="{{ route('terms') }}" class="fw-600 auth-link">{{ translate('terms and conditions') }}</a>.</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn auth-btn btn-block w-100 fs-15" id="createAccountBtn">{{  translate('Create Account') }}</button>
                            </form>
                            
                            <!-- Social Login -->
                            @if(get_setting('google_login') == 1 || get_setting('facebook_login') == 1 || get_setting('twitter_login') == 1 || get_setting('apple_login') == 1)
                                <div class="divider my-4">{{ translate('Or Join With')}}</div>
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
                            @endif

                            <!-- Log In -->
                            <div class="text-center mt-4 pt-2">
                                <p class="fs-14 text-secondary mb-0">
                                    {{ translate('Already have an account?')}}
                                    <a href="{{ route('user.login') }}" class="fw-700 text-primary auth-link ms-1">{{ translate('Log In')}}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // Custom password toggle logic
        document.querySelectorAll('.password-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                var input = this.previousElementSibling;
                if(input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('la-eye');
                    this.classList.add('la-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('la-eye-slash');
                    this.classList.add('la-eye');
                }
            });
        });
    </script>
    
    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        <script type="text/javascript">
            document.getElementById('reg-form').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'register'}).then(function(token) {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'g-recaptcha-response');
                        input.setAttribute('value', token);
                        e.target.appendChild(input);
                        e.target.submit();
                    });
                });
            });
        </script>
    @endif
    @include('auth.verifyEmailOrPhone')

    <script>
        const regVerifyRequired = {{get_setting('customer_registration_verify') ? 'true' : 'false' }};
        const createBtn   = $('#createAccountBtn');
        const termsCheckbox = $('input[name="checkbox_example_1"]');
        
        function toggleCreateBtn() {
            const termsChecked = termsCheckbox.is(':checked');
            const regVerified  = regVerifyRequired ? (typeof verifyBtn !== 'undefined' && verifyBtn && verifyBtn.classList.contains('disabled')) : true;
            let enableBtn = false;
            if (regVerifyRequired) {
                enableBtn = termsChecked && regVerified;
            } else {
                enableBtn = termsChecked;
            }
            createBtn.prop('disabled', !enableBtn);
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCreateBtn(); 
            termsCheckbox.on('change', toggleCreateBtn); 
        });
    </script>
@endsection
