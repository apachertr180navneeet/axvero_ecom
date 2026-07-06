@php
    $footer_logo = get_setting('footer_logo') ?: get_setting('header_logo');
@endphp

<section class="axvero-footer text-light">
    <div class="container">
        <div class="axvero-footer-top d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <a href="{{ route('home') }}" class="axvero-footer-logo d-inline-block">
                @if ($footer_logo != null)
                    <img class="lazyload" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($footer_logo) }}" alt="{{ env('APP_NAME') }}">
                @else
                    <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                @endif
            </a>

            @if (get_setting('show_social_links'))
            <div class="axvero-footer-social text-md-right mt-4 mt-md-0">
                <h5 class="axvero-footer-label mb-3">{{ translate('Follow Us') }}</h5>
                <ul class="list-inline social colored mb-0">
                    @if (!empty(get_setting('facebook_link')))
                        <li class="list-inline-item mr-2">
                            <a href="{{ get_setting('facebook_link') }}" target="_blank" class="facebook"><i class="lab la-facebook-f"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('instagram_link')))
                        <li class="list-inline-item mr-2">
                            <a href="{{ get_setting('instagram_link') }}" target="_blank" class="instagram"><i class="lab la-instagram"></i></a>
                        </li>
                    @endif
                    @if (!empty(get_setting('youtube_link')))
                        <li class="list-inline-item mr-2">
                            <a href="{{ get_setting('youtube_link') }}" target="_blank" class="youtube"><i class="lab la-youtube"></i></a>
                        </li>
                    @endif
                </ul>
            </div>
            @endif
        </div>

        <div class="axvero-footer-divider"></div>

        <div class="row axvero-footer-columns">
            <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                <h4 class="axvero-footer-heading">{{ translate('Contacts') }}</h4>
                <div class="axvero-footer-contact-item">
                    <p class="axvero-footer-sub">{{ translate('Address') }}</p>
                    <p class="axvero-footer-text">{{ get_setting('contact_address', null, App::getLocale()) }}</p>
                </div>
                <div class="axvero-footer-contact-item">
                    <p class="axvero-footer-sub">{{ translate('Phone') }}</p>
                    <p class="axvero-footer-text mb-0">{{ get_setting('contact_phone') }}</p>
                </div>
                <div class="axvero-footer-contact-item">
                    <p class="axvero-footer-sub">{{ translate('Email') }}</p>
                    <p class="axvero-footer-text mb-0">
                        <a href="mailto:{{ get_setting('contact_email') }}" class="axvero-footer-link">{{ get_setting('contact_email') }}</a>
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 mb-4 mb-md-0">
                <h4 class="axvero-footer-heading">{{ translate('My Account') }}</h4>
                <ul class="list-unstyled axvero-footer-links mb-0">
                    @auth
                        <li><a href="{{ route('logout') }}">{{ translate('Logout') }}</a></li>
                    @else
                        <li><a href="{{ route('user.login') }}">{{ translate('Login') }}</a></li>
                    @endauth
                    <li><a href="{{ route('purchase_history.index') }}">{{ translate('Order History') }}</a></li>
                    <li><a href="{{ route('wishlists.index') }}">{{ translate('My Wishlist') }}</a></li>
                    <li><a href="{{ route('orders.track') }}">{{ translate('Track Order') }}</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-4">
                @if (get_setting('vendor_system_activation') == 1)
                <h4 class="axvero-footer-heading">{{ translate('Seller Zone') }}</h4>
                <ul class="list-unstyled axvero-footer-links mb-0">
                    <li class="mb-2">
                        <span class="axvero-footer-text">{{ translate('Become A Seller') }}</span>
                        <a href="{{ route(get_setting('seller_registration_verify') === '1' ? 'shop-reg.verification' : 'shops.create') }}" class="axvero-footer-apply">{{ translate('Apply Now') }}</a>
                    </li>
                    @guest
                        <li><a href="{{ route('seller.login') }}">{{ translate('Login to Seller Panel') }}</a></li>
                    @endguest
                </ul>
                @endif
            </div>
        </div>
    </div>

    <div class="axvero-footer-bottom">
        <div class="container">
            <div class="row align-items-center py-3">
                <div class="col-lg-6 text-center text-lg-left">
                    <div class="axvero-footer-copy fs-14">
                        {!! get_setting('frontend_copyright_text', null, App::getLocale()) ?: '@' . date('Y') . ' ' . env('APP_NAME') . '. ' . translate('All Rights Reserved.') !!}
                    </div>
                </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <div class="text-center text-lg-right">
                        <ul class="list-inline mb-0">
                            @if (get_setting('payment_method_images') != null)
                                @foreach (explode(',', get_setting('payment_method_images')) as $value)
                                    <li class="list-inline-item mr-2">
                                        <img src="{{ uploaded_asset($value) }}" height="20" class="mw-100 h-auto" style="max-height: 20px" alt="{{ translate('payment_method') }}">
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
