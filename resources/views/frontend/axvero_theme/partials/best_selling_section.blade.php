@php
    $best_selling_products = get_best_selling_products(20);
@endphp
@if (get_setting('best_selling') == 1 && count($best_selling_products) > 0)
    <div class="container mb-5 mt-2 mt-md-3">
        <!-- Top Section -->
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <h2 class="section-title mb-0 text-left">{{ translate('Best Selling') }}</h2>
            <div class="d-flex align-items-center">
                <a type="button" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm mx-1 slide-arrow link-disable" onclick="clickToSlide('slick-prev','section_best_selling')"><i class="las la-angle-left fs-18"></i></a>
                <a type="button" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm mx-1 slide-arrow" onclick="clickToSlide('slick-next','section_best_selling')"><i class="las la-angle-right fs-18"></i></a>
            </div>
        </div>
        <!-- Product Section -->
        <div>
            <div class="aiz-carousel arrow-none" data-items="5" data-xl-items="4" data-lg-items="3"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false' style="margin: 0 -10px;">
                @foreach ($best_selling_products as $key => $product)
                    <div class="carousel-box p-2">
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif