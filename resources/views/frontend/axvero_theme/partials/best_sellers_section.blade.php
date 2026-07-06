@if (get_setting('vendor_system_activation') == 1)
    <div class="container mb-5 mt-2 mt-md-3">
        <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded-lg">
            <!-- Title Section -->
            <div class="d-flex mb-4 align-items-center justify-content-between border-bottom pb-3">
                <h2 class="section-title mb-0 text-left" style="font-size: 2rem;">{{ translate('Best Sellers') }}</h2>
                <div class="d-flex align-items-center">
                    <a href="{{ route('sellers') }}" class="btn btn-dark btn-sm rounded-pill px-3 shadow fw-600">{{ translate('View All Sellers') }}</a>
                </div>
            </div>
            
            <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="3" data-lg-items="3"  data-md-items="2" data-sm-items="2" data-xs-items="1" data-rows="2">
                @foreach (get_best_sellers(20) as $key => $seller)
                    @if ($seller->user != null)
                        <div class="carousel-box">
                            <div class="row no-gutters box-3 align-items-center border-0 shadow-sm rounded-lg hov-shadow-md my-2 has-transition bg-light">
                                <div class="col-4">
                                    <a href="{{ route('shop.visit', $seller->slug) }}" class="d-block p-3 text-center">
                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="@if ($seller->logo !== null) {{ uploaded_asset($seller->logo) }} @else {{ static_asset('assets/img/placeholder.jpg') }} @endif"
                                            alt="{{ $seller->name }}"
                                            class="img-fluid lazyload rounded-circle shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                    </a>
                                </div>
                                <div class="col-8">
                                    <div class="p-3 text-left">
                                        <h2 class="h6 fw-600 text-truncate">
                                            <a href="{{ route('shop.visit', $seller->slug) }}" class="text-dark hov-text-primary">{{ $seller->name }}</a>
                                        </h2>
                                        <div class="rating rating-sm mb-2 text-warning">
                                            {{ renderStarRating($seller->rating) }}
                                        </div>
                                        <a href="{{ route('shop.visit', $seller->slug) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
                                            {{ translate('Visit Store') }} <i class="las la-angle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
