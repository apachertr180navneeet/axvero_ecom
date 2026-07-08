@if($topSellingProducts->count() > 0)
<section class="my-2 my-md-3">
    <div class="container-fluid px-xl-4 px-2">
        <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
            <h3 class="fs-16 fw-700 mb-2 mb-sm-0">
                <span>{{ translate('Top Selling Products') }}</span>
            </h3>
            <div class="d-flex">
                <a type="button"
                   class="arrow-prev slide-arrow link-disable text-secondary mr-2"
                   onclick="clickToSlide('slick-prev','section_top_selling_products')">
                    <i class="las la-angle-left fs-20 fw-600"></i>
                </a>
                <a type="button"
                   class="arrow-next slide-arrow text-secondary ml-2"
                   onclick="clickToSlide('slick-next','section_top_selling_products')">
                    <i class="las la-angle-right fs-20 fw-600"></i>
                </a>
            </div>
        </div>
        <div class="px-sm-2">
            <div class="aiz-carousel slick-left sm-gutters-16 arrow-none"
                 data-items="7"
                 data-xl-items="6"
                 data-lg-items="5"
                 data-md-items="3"
                 data-sm-items="2"
                 data-xs-items="2"
                 data-arrows="true"
                 data-infinite="false"
                 id="section_top_selling_products">
                @foreach ($topSellingProducts as $key => $top_product)
                    <div class="carousel-box px-2 position-relative">
                        @include('frontend.product_box_for_listing_page', ['product' => $top_product])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif