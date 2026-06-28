@extends('frontend.layouts.app')

@section('content')

    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Affiliate Program') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="fw-600">{{ translate('Earn money by sharing products!') }}</h6>
                                <p class="text-muted">
                                    {{ translate('Join our affiliate program and earn commissions on every sale made through your referral links.') }}
                                </p>
                                <ul class="list-unstyled">
                                    <li class="py-1">
                                        <i class="las la-check-circle text-success mr-2"></i>
                                        {{ translate('Share products with your unique referral link') }}
                                    </li>
                                    <li class="py-1">
                                        <i class="las la-check-circle text-success mr-2"></i>
                                        {{ translate('Earn commission on every successful purchase') }}
                                    </li>
                                    <li class="py-1">
                                        <i class="las la-check-circle text-success mr-2"></i>
                                        {{ translate('Withdraw your earnings anytime') }}
                                    </li>
                                </ul>
                            </div>

                            <form action="{{ route('affiliate.store_affiliate_user') }}" method="POST">
                                @csrf
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
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
