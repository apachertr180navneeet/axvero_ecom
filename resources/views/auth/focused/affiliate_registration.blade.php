@extends('auth.layouts.authentication')

@section('content')
    <!-- aiz-main-wrapper -->
    <div class="aiz-main-wrapper d-flex flex-column justify-content-center bg-white">
        <section class="bg-white overflow-hidden" style="min-height:100vh;">
            <div class="row no-gutters" style="min-height: 100vh;">
                <!-- Left Side Image-->
                <div class="col-xxl-9 col-lg-8">
                    <div class="h-100" style="max-height: 100vh">
                        <img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}" alt="" class="img-fit h-100">
                    </div>
                </div>
                
                <!-- Right Side -->
                <div class="col-xxl-3 col-lg-4">
                    <div class="d-flex align-items-center right-content">
                        <div class="py-3 py-lg-4 px-3 px-xl-5 flex-grow-1">
                            <!-- Site Icon -->
                            <div class="size-48px mb-3 mx-auto mx-lg-0">
                                <img src="{{ uploaded_asset(get_setting('site_icon')) }}" alt="{{ translate('Site Icon')}}" class="img-fit h-100">
                            </div>
                            <!-- Titles -->
                            <div class="text-center text-lg-left">
                                <h1 class="fs-20 fs-md-24 fw-700 text-primary" style="text-transform: uppercase;">{{ translate('Register as Affiliate ')}}</h1>
                            </div>
                            <div class="pt-3 pt-lg-4">
                                <div class="">
                                    @include('auth.partials.affiliate_registration_form')

                                    <p class="fs-12 text-gray mb-0">
                                        {{ translate('Already have an account?')}}
                                        <a href="{{ route('affiliate.login') }}" class="ml-2 fs-14 fw-700 animate-underline-primary">{{ translate('Log In')}}</a>
                                    </p>
                                </div>
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
        </section>
    </div>
@endsection

@section('script')
    @include('auth.partials.affiliate_registration_scripts')
@endsection
