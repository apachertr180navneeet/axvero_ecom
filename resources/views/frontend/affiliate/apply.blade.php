@extends('frontend.layouts.app')

@section('content')

    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="modern-card border-0 mb-4 shadow-lg">
                        <div class="card-header pt-4 border-bottom-0 pb-0 bg-transparent">
                            <h5 class="mb-0 fs-24 fw-700 text-dark">{{ translate('Affiliate Program') }}</h5>
                        </div>
                        <div class="card-body p-5">
                            <div class="mb-5 text-center">
                                <h6 class="fw-700 fs-18 mb-3">{{ translate('Earn money by sharing products!') }}</h6>
                                <p class="text-muted">
                                    {{ translate('Join our affiliate program and earn commissions on every sale made through your referral links.') }}
                                </p>
                                <ul class="list-unstyled text-left d-inline-block mt-4 mx-auto">
                                    <li class="py-2 d-flex align-items-center">
                                        <div class="modern-icon-btn me-3" style="width: 32px; height: 32px;">
                                            <i class="las la-check text-success"></i>
                                        </div>
                                        <span class="fs-15 fw-500">{{ translate('Share products with your unique referral link') }}</span>
                                    </li>
                                    <li class="py-2 d-flex align-items-center">
                                        <div class="modern-icon-btn me-3" style="width: 32px; height: 32px;">
                                            <i class="las la-check text-success"></i>
                                        </div>
                                        <span class="fs-15 fw-500">{{ translate('Earn commission on every successful purchase') }}</span>
                                    </li>
                                    <li class="py-2 d-flex align-items-center">
                                        <div class="modern-icon-btn me-3" style="width: 32px; height: 32px;">
                                            <i class="las la-check text-success"></i>
                                        </div>
                                        <span class="fs-15 fw-500">{{ translate('Withdraw your earnings anytime') }}</span>
                                    </li>
                                </ul>
                            </div>

                            <form action="{{ route('affiliate.store_affiliate_user') }}" method="POST">
                                @csrf
                                <div class="text-center">
                                    <button type="submit" class="btn modern-btn modern-btn-primary btn-block py-3 fs-16">
                                        {{ translate('Apply for Affiliate Program') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
