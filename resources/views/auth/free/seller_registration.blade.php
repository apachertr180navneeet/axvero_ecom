@extends('auth.layouts.authentication')
<style>
.right-content{
    width:100%;
    max-width:900px;
    margin:auto;
}
.right-content .row{
    width:100%;
    justify-content:center;
}

.right-content .col-xl-5,
.right-content .col-lg-6,
.right-content .col-md-8{
    flex:0 0 100%;
    max-width:100%;
}

.pt-lg-4.bg-white{
    padding:30px;
    border-radius:8px;
    box-shadow:0 8px 25px rgba(0,0,0,0.08);
}

.form-control{
    width:100%;
    height:44px;
}

.form-group{
    margin-bottom:16px;
}

.text-center.text-lg-left{
    text-align:center !important;
}
</style>
@section('content')
   <!-- aiz-main-wrapper -->
   <div class="aiz-main-wrapper d-flex flex-column justify-content-center bg-white">
        <section class="bg-white overflow-hidden" style="min-height:100vh;">
            <div class="row" style="min-height: 100vh;">
                <!-- Left Side Image-->
                <!--<div class="col-xxl-6 col-lg-7">-->
                <!--    <div class="h-100">-->
                <!--        <img src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}" alt="" class="img-fit h-100">-->
                <!--    </div>-->
                <!--</div>-->
                
                <!-- Right Side -->
                <!--<div class="col-xxl-6 col-lg-5">-->
                <div class="col-12 d-flex justify-content-center">
                    <div class="right-content">
                        <div class="row align-items-center justify-content-center justify-content-lg-start h-100">
                           <div class="col-xl-5 col-lg-6 col-md-8 col-12 p-4 p-lg-5">
                                <!-- Site Icon -->
                                <div class=" size-48px mb-3 mx-auto mx-lg-0">
                                    <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                                </div>
                                <!-- Titles -->
                                <div class="text-center text-lg-left">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Register your shop')}}</h1>
                                </div>
                                <!-- Register form -->
                                <div class="pt-3 pt-lg-4 bg-white">
                                    <div class="">
                                        <form id="reg-form" class="form-default" role="form" action="{{ route('shops.store') }}" method="POST">
                                            @csrf

                                            <div class="fs-15 fw-600 pb-2">{{ translate('Personal Info')}}</div>
                                            <!-- Name -->
                                            <div class="form-group">
                                                <label for="name" class="fs-12 fw-700 text-soft-dark">{{  translate('Your Name') }}</label>
                                                <input type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Full Name') }}" name="name" required>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label>{{ translate('Your Email')}}</label>
                                                <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" required>
                                                @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                             <div class="form-group">
                                                    <label>{{ translate('Your Phone')}}</label>
                                                    <input type="tel" class="form-control rounded-0{{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ $phone ?? old('phone') }}" placeholder="{{  translate('Phone') }}" name="phone" required  {{$phone  ? 'readonly' : ''}}>
                                                    @if ($errors->has('phone'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                             <!--state-->
                                              <div class="form-group">
                                                    <label>{{ translate('State')}}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('state') ? ' is-invalid' : '' }}"  placeholder="{{  translate('State') }}" name="state" required  >
                                                    @if ($errors->has('state'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('state') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                                  <!--city-->
                                              <div class="form-group">
                                                    <label>{{ translate('City')}}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('city') ? ' is-invalid' : '' }}"  placeholder="{{  translate('City') }}" name="city" required  >
                                                    @if ($errors->has('city'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('city') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
        
                                                     <!--portal email-->
                                              <div class="form-group">
                                                    <label>{{ translate('Portal Email Id')}}</label>
                                                    <input type="email" class="form-control rounded-0{{ $errors->has('portal_email') ? ' is-invalid' : '' }}"  placeholder="{{  translate('Portal Email Id') }}" name="portal_email" required  >
                                                    @if ($errors->has('portal_email'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('portal_email') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                                  <!--Senior Manager Name-->
                                              <div class="form-group">
                                                    <label>{{ translate('Senior Manager Name')}}</label>
                                                    <input type="text" class="form-control rounded-0{{ $errors->has('manager_name') ? ' is-invalid' : '' }}"  placeholder="{{  translate('Senior Manager Name') }}" name="manager_name" required  >
                                                    @if ($errors->has('manager_name'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('manager_name') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                                   <!--Senior Manager Mobile-->
                                              <div class="form-group">
                                                    <label>{{ translate('Senior Manager Mobile')}}</label>
                                                    <input type="tel" class="form-control rounded-0{{ $errors->has('manager_mobile') ? ' is-invalid' : '' }}"  placeholder="{{  translate('Senior Manager Mobile') }}" name="manager_mobile" required  >
                                                    @if ($errors->has('manager_mobile'))
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $errors->first('manager_mobile') }}</strong>
                                                        </span>
                                                    @endif
                                            </div>
                                            <!-- password -->
                                            <div class="form-group mb-0">
                                                <label for="password" class="fs-12 fw-700 text-soft-dark">{{  translate('Password') }}</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control rounded-0{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{  translate('Password') }}" name="password" required>
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                                <div class="text-right mt-1">
                                                    <span class="fs-12 fw-400 text-gray-dark">{{ translate('Password must contain at least 6 digits') }}</span>
                                                </div>
                                                @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- password Confirm -->
                                            <div class="form-group">
                                                <label for="password_confirmation" class="fs-12 fw-700 text-soft-dark">{{  translate('Confirm Password') }}</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control rounded-0" placeholder="{{  translate('Confirm Password') }}" name="password_confirmation" required>
                                                    <i class="password-toggle las la-2x la-eye"></i>
                                                </div>
                                            </div>


                                            <div class="fs-15 fw-600 py-2">{{ translate('Basic Info')}}</div>
                                            
                                            <div class="form-group">
                                                <label for="shop_name" class="fs-12 fw-700 text-soft-dark">{{  translate('Shop Name') }}</label>
                                                <input type="text" class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" value="{{ old('shop_name') }}" placeholder="{{  translate('Shop Name') }}" name="shop_name" required>
                                                @if ($errors->has('shop_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('shop_name') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                <label for="address" class="fs-12 fw-700 text-soft-dark">{{  translate('Address') }}</label>
                                                <input type="text" class="form-control rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{  translate('Address') }}" name="address" required>
                                                @if ($errors->has('address'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('address') }}</strong>
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Recaptcha -->
                                            @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_seller_register') == 1)
                                                
                                                @if ($errors->has('g-recaptcha-response'))
                                                    <span class="border invalid-feedback rounded p-2 mb-3 bg-danger text-white" role="alert" style="display: block;">
                                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                    </span>
                                                @endif
                                            @endif
                                        
                                            <!-- Submit Button -->
                                            <div class="mb-4 mt-4">
                                                <button type="submit" class="btn btn-primary btn-block fw-600 rounded-0">{{  translate('Register Your Shop') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- Log In -->
                                    <p class="fs-12 text-gray mb-0">
                                        {{ translate('Already have an account?')}}
                                        <a href="{{ route('seller.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In')}}</a>
                                    </p>
                                    <!-- Go Back -->
                                    <a href="{{ url()->previous() }}" class="mt-3 fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
                                        <i class="las la-arrow-left fs-20 mr-1"></i>
                                        {{ translate('Back to Previous Page')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    @if(get_setting('google_recaptcha') == 1 && get_setting('recaptcha_customer_register') == 1)
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('CAPTCHA_KEY') }}"></script>
        
        <script type="text/javascript">
                document.getElementById('reg-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.ready(function() {
                        grecaptcha.execute(`{{ env('CAPTCHA_KEY') }}`, {action: 'selller_registration'}).then(function(token) {
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
@endsection