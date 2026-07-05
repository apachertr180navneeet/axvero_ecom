@if (get_setting('home_categories') != null)
@php
$home_categories = json_decode(get_setting('home_categories'));
$categories = get_category($home_categories);
@endphp

@if(count($categories) > 0)
<div class="bg-featured-categories shadow-sm">
    <div class="d-flex mb-4 align-items-center justify-content-between">
        <h2 class="section-title-modern mb-0"><i class="las la-layer-group text-dark"></i> {{ translate('Featured Categories') }}</h2>
        <div class="d-flex align-items-center">
            <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow link-disable" onclick="clickToSlide('slick-prev','home-category-carousel-modern')"><i class="las la-angle-left fs-18"></i></a>
            <a type="button" class="btn btn-light btn-sm rounded-circle shadow-sm mx-1 slide-arrow" onclick="clickToSlide('slick-next','home-category-carousel-modern')"><i class="las la-angle-right fs-18"></i></a>
        </div>
    </div>
    
    <div class="aiz-carousel arrow-none home-category-carousel-modern"
        data-items="5" data-xxl-items="4" data-xl-items="4"
        data-lg-items="3" data-md-items="2" data-sm-items="2"
        data-xs-items="1" data-arrows="true" data-infinite="false" style="margin: 0 -10px;">
        
        @foreach ($categories as $category_key => $category)
            @php
                $category_name = $category->getTranslation('name');
            @endphp
            <div class="carousel-box p-2">
                <div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100 position-relative category-tile bg-white" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                    <!-- Image Section -->
                    <div class="position-relative img-fit overflow-hidden" style="height: 250px;">
                        <a href="{{ route('products.category', $category->slug) }}" class="d-block h-100 w-100">
                            <img class="img-fluid w-100 h-100 object-fit-cover"
                                src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                alt="{{ $category_name }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </a>
                        <div class="position-absolute" style="bottom: 10px; right: 10px;">
                            <a href="{{ route('products.category', $category->slug) }}" class="btn btn-dark btn-sm rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm">
                                <i class="las la-arrow-right fs-16"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="card-body text-center d-flex flex-column justify-content-center p-3" style="background-color: #fff;">
                        <h5 class="card-title fs-16 fw-600 mb-1">
                            <a href="{{ route('products.category', $category->slug) }}" class="text-dark hov-text-primary">
                                {{ $category_name }}
                            </a>
                        </h5>
                        <p class="text-muted fs-12 mb-0">{{ translate('View collection') }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
@endif
