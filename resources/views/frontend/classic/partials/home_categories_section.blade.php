@if (get_setting('home_categories') != null)
@php
$home_categories = json_decode(get_setting('home_categories'));
$categories = get_category($home_categories);
@endphp
@foreach ($categories as $category_key => $category)
@php
$category_name = $category->getTranslation('name');
@endphp
<section class="mb-5 @if ($category_key != 0) mt-5 @endif">
    <div class="container">
        <!-- Title Section -->
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <h2 class="section-title mb-0 text-left">{{ $category_name }}</h2>
            <div class="d-flex align-items-center">
                <a href="{{ route('products.category', $category->slug) }}" class="btn btn-dark btn-sm rounded-pill px-3 shadow fw-600">{{ translate('View All') }}</a>
            </div>
        </div>
        
        <div class="row gutters-5 mb-40">
            <!-- 🟦 Home category banner: always on left -->
            <div class="col-4 col-md-5 col-lg-4 col-xl-3">
                <div class="h-100" style="min-height: 250px;">
                    <a href="{{ route('products.category', $category->slug) }}" class="d-block h-100 w-100 hov-scale-img overflow-hidden home-category-banner rounded-lg shadow-sm position-relative">
                        <span class="position-absolute h-100 w-100 overflow-hidden">
                            <img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                alt="{{ $category_name }}"
                                class="img-fit h-100 has-transition w-100 object-fit-cover"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </span>
                        <div class="position-absolute absolute-full bg-dark bg-opacity-25" style="background-color: rgba(0,0,0,0.3);"></div>
                        <span class="home-category-name fs-18 fw-700 text-white text-center position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%;">
                            <span>{{ $category_name }}</span>
                        </span>
                    </a>
                </div>
            </div>

            <!-- Category Products: slide only this on small -->
            <div class="col-8 col-md-7 col-lg-8 col-xl-9">
                <div class="aiz-carousel arrow-none home-category"
                    data-items="5" data-xxl-items="4" data-xl-items="3.5"
                    data-lg-items="3" data-md-items="2" data-sm-items="2"
                    data-xs-items="1.5" data-arrows="true" data-infinite="false" style="margin: 0 -10px;">

                    @foreach (get_cached_products($category->id) as $product_key => $product)
                    <div class="carousel-box p-2">
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1', ['product' => $product])
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>

@endforeach
@endif