@if (get_setting('best_selling') == 1)
@php
    $best_selling = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
@endphp
@if (count($best_selling) > 0)
<div class="bg-best-selling shadow-sm h-100">
    <div class="d-flex mb-4 align-items-center justify-content-between">
        <h2 class="section-title-modern mb-0"><i class="las la-award text-dark"></i> {{ translate('Best Selling') }}</h2>
        <div class="d-flex align-items-center">
            <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow link-disable" onclick="clickToSlide('slick-prev','section_best_selling_carousel')"><i class="las la-angle-left fs-18"></i></a>
            <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow" onclick="clickToSlide('slick-next','section_best_selling_carousel')"><i class="las la-angle-right fs-18"></i></a>
        </div>
    </div>
    
    <div class="aiz-carousel arrow-none section_best_selling_carousel"
        data-items="2" data-xxl-items="2" data-xl-items="2"
        data-lg-items="2" data-md-items="2" data-sm-items="2"
        data-xs-items="2" data-arrows="true" data-infinite="false" style="margin: 0 -10px;">
        
        @foreach ($best_selling as $key => $product)
        <div class="carousel-box p-2">
            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
        </div>
        @endforeach
    </div>
</div>
@endif
@endif
