@if (count(get_featured_products()) > 0)
    <div class="bg-featured-products shadow-sm">
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <h2 class="section-title-modern mb-0"><i class="las la-star"></i> {{ translate('Featured Products') }}</h2>
            <div class="d-flex align-items-center">
                <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow link-disable" onclick="clickToSlide('slick-prev','section_featured')"><i class="las la-angle-left fs-18"></i></a>
                <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow" onclick="clickToSlide('slick-next','section_featured')"><i class="las la-angle-right fs-18"></i></a>
            </div>
        </div>
        <div class="aiz-carousel arrow-none" data-items="5" data-xl-items="4" data-lg-items="3"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false' style="margin: 0 -10px;">
            @foreach (get_featured_products() as $key => $product)
            <div class="carousel-box p-2">
                @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
            </div>
            @endforeach
        </div>
    </div>   
@endif
