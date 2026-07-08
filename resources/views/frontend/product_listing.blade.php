@extends('frontend.layouts.app')
@php
    $form_all_preorder_page = session('preorder_all_page');
    session()->forget('preorder_all_page');
@endphp

@if (isset($category_id))
    @php
        $category_search = $category;
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
        $meta_keywords = $category->meta_keywords;
    @endphp
@elseif (isset($brand_id))
    @php
        $brand_name = get_single_brand($brand_id)->name;
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
        $meta_keywords = get_single_brand($brand_id)->meta_keywords;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop
@section('meta_keywords'){{ $meta_keywords ?? '' }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

    <section class="mb-1">
        <div class="container-fluid sm-px-0 pt-1 px-xl-4 px-2">
            <form class="" id="search-form" action="" method="GET">
                <input type="hidden" name="product_type" value="{{ $product_type ?? 'general_product' }}">
                <div class="row axv-listing-scroll-layout">

                    <!-- Sidebar Filters -->
                    <div class="col-xl-3 axv-listing-sidebar-col">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                                data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar scroll-bar-show c-scrollbar-light text-left">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
                                    <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb"
                                        data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                        <i class="las la-times la-2x"></i>
                                    </button>
                                </div>

                                <!-- Categories -->
                                <div class="modern-card mb-3">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_1"
                                            class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse">

                                            {{ translate('Categories') }}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_1">
                                        <!-- Product Category -->
                                        <div class="">
                                            <div class=" @if ($errors->has('category_ids') || $errors->has('category_id')) border border-danger @endif">
                                                @php
                                                    if ($category_id) {
                                                        $old_categories = [$category_id];
                                                    } else {
                                                        $old_categories = [];
                                                    }
                                                @endphp
                                                {{-- general category list  --}}
                                                <div class="px-20px pb-10px display-none" id="general_cagegories_box">
                                                    <div id="category_filter" class="h-300px overflow-auto no-scrollbar">
                                                        <ul class="hummingbird-treeview-converter2 list-unstyled"
                                                            data-checkbox-name="categories[]">
                                                            @foreach ($categories as $category)
                                                                {{-- @if ($category->products_count > 0) --}}
                                                                <li d-item="{{ $category->products_count }}"
                                                                    id="generel_{{ $category->id }}">
                                                                    {{ $category->getTranslation('name') }}
                                                                    @if ($category->products_count > 0)
                                                                        {{ '   (' . $category->products_count . ')' }}
                                                                    @endif
                                                                </li>
                                                                {{-- @endif --}}
                                                                @foreach ($category->childrenCategories as $childCategory)
                                                                    @include(
                                                                        'frontend.product_listing_page_child_category',
                                                                        ['child_category' => $childCategory]
                                                                    )
                                                                @endforeach
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- preorder category list  --}}
                                                <div class="px-20px pb-10px display-none" id="preorder_cagegories_box">
                                                    <div id="category_filter_preorder"
                                                        class="h-300px overflow-auto no-scrollbar">
                                                        <ul class="hummingbird-treeview-converter2 list-unstyled"
                                                            data-checkbox-name="categories_preorder[]">
                                                            @foreach ($preorder_categories as $category)
                                                                @if ($category->products_count > 0)
                                                                    <li d-item="{{ $category->products_count }}"
                                                                        id="preorder_{{ $category->id }}">
                                                                        {{ $category->getTranslation('name') }}{{ '   (' . $category->products_count . ')' }}
                                                                    </li>
                                                                @endif
                                                                @foreach ($category->childrenCategories as $childCategory)
                                                                    @include(
                                                                        'frontend.product_listing_page_child_category_preorder',
                                                                        ['child_category' => $childCategory]
                                                                    )
                                                                @endforeach
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Price range -->
                                <div class="modern-card mb-3">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#collapse_price"
                                            class="dropdown-toggle collapsed filter-section text-dark d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_price">
                                            {{ translate('Price range') }}
                                        </a>
                                    </div>
                                    <div class="collapse" id="collapse_price">
                                        <div class="px16px py22px hover-effect">
                                            @php
                                                $product_count = get_products_count();
                                            @endphp

                                            <div class="aiz-range-slider">


                                                <div id="input-slider-range"
                                                    data-range-value-min="@if (true) 0 @else {{ get_product_min_unit_price() }} @endif"
                                                    data-range-value-max="@if ($product_count < 1) 0 @else {{ get_product_max_unit_price() }} @endif">
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; left: -1px;  ">
                                                    </div>
                                                    <div
                                                        style="width: 4px; height: 16px; background-color: #DFDFE6; position: absolute; top: -7px; right: -1px;  ">
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                            @elseif($products->min('unit_price') > 0)
                                                                data-range-value-low="{{ $products->min('unit_price') }}"
                                                            @else --}} data-range-value-low="0"
                                                            {{-- @endif --}} id="input-slider-range-value-low">0</span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            {{-- @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                            @elseif($products->max('unit_price') > 0)
                                                                data-range-value-high="{{ $products->max('unit_price') }}"
                                                            @else --}}
                                                            data-range-value-high="{{ get_product_max_unit_price() / 2 }}"
                                                            {{-- @endif --}} id="input-slider-range-value-high"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Hidden Items -->
                                        <input type="hidden" name="min_price" value="">
                                        <input type="hidden" name="max_price" value="">
                                    </div>
                                </div>


                                <!-- Attributes -->
                                @foreach ($attributes as $attribute)
                                    @if ($attribute->product_count > 0)
                                        <div class="modern-card preorder-time-hide mb-3">
                                            <div class="fs-16 fw-700 p-3">
                                                <a href="#"
                                                    class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                    data-toggle="collapse"
                                                    data-target="#collapse_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}"
                                                    style="white-space: normal;">
                                                    {{ $attribute->getTranslation('name') }}
                                                </a>
                                            </div>
                                            @php
                                                $show = '';
                                                foreach ($attribute->attribute_values as $attribute_value) {
                                                    if (in_array($attribute_value->value, $selected_attribute_values)) {
                                                        $show = 'show';
                                                    }
                                                }
                                            @endphp
                                            <div class="collapse {{ $show }}"
                                                id="collapse_{{ str_replace(' ', '_', preg_replace('/[^a-zA-Z]/', '', $attribute->name)) }}">
                                                <div class="px-3 aiz-checkbox-list">
                                                    @foreach ($attribute->attribute_values as $attribute_value)
                                                        @if ($attribute_value->product_count > 0)
                                                            <label class="aiz-checkbox mb-3 d-flex align-items-center ">
                                                                <input type="checkbox" name="selected_attribute_values[]"
                                                                    value="{{ $attribute_value->value }}"
                                                                    @if (in_array($attribute_value->value, $selected_attribute_values)) checked @endif
                                                                    onchange="filter(event)">
                                                                <span class="aiz-square-check border_black"></span>
                                                                <span
                                                                    class="fs-14 fw-400 text-dark hover-effect-list-item  @if (in_array($attribute_value->value, $selected_attribute_values)) fw-bold @endif">{{ $attribute_value->value }}
                                                                    {{ '(' . $attribute_value->product_count . ')' }}</span>
                                                            </label>
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-link p-0 m-0 mb-3 font-weight-bold see_more_toggle_btn">
                                                        See More <i class="las la-angle-down fs-12 fw-600 "></i></button>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="button"
                                                        class="btn btn-link p-0 m-0 mb-3 font-weight-bold less_toggle_btn">See
                                                        Less <i class="las la-angle-up fs-12 fw-600 "></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <!-- Color -->
                                @if (get_setting('color_filter_activation'))
                                    <div class="modern-card mb-3 preorder-time-hide">
                                        <div class="fs-16 fw-700 p-3">
                                            <a href="#"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                                data-toggle="collapse" data-target="#collapse_color">
                                                {{ translate('Filter by color') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($colors as $key => $color) {
                                                if (isset($selected_color) && $selected_color == $color->code) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_color">
                                            <div class="px-3 aiz-checkbox-list">
                                                @foreach ($colors as $key => $color)
                                                    @if ($color->product_count > 0)
                                                        <label class="aiz-checkbox mb-3 d-flex align-items-center ">
                                                            <input type="checkbox" name="colors[]"
                                                                value="{{ $color->code }}"
                                                                @if (isset($selected_color) && $selected_color == $color->code) checked @endif
                                                                onchange="filter(event)">
                                                            <span class="aiz-square-check border_black"></span>
                                                            <div class="d-flex">

                                                                <div
                                                                    style="width: 20px; height: 20px; background-color: {{ $color->code }};border-radius: 50%; margin-right: 10px;">
                                                                </div>
                                                                <span
                                                                    class="fs-14 text-dark hover-effect-list-item">{{ $color->name }}
                                                                    {{ '(' . $color->product_count . ')' }}
                                                                </span>
                                                            </div>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-link p-0 m-0 mb-3 font-weight-bold see_more_toggle_btn">
                                                    See More <i class="las la-angle-down fs-12 fw-600 "></i></button>
                                            </div>
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-link p-0 m-0 mb-3 font-weight-bold less_toggle_btn">See
                                                    Less <i class="las la-angle-up fs-12 fw-600 "></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Attributes for preorder product -->
                                <div class="modern-card mb-3 mt-3 preorder-time-show display-none">
                                    <div class="fs-16 fw-700 p-3">
                                        <a href="#"
                                            class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between"
                                            data-toggle="collapse" data-target="#collapse_availability_filter"
                                            style="white-space: normal;">
                                            {{ translate('Filter by Availability') }}
                                        </a>
                                    </div>
                                    @php
                                        $show = $is_available !== null ? 'show' : '';
                                    @endphp
                                    <div class="collapse {{ $show }}" id="collapse_availability_filter">
                                        <div class="p-3 aiz-checkbox-list">
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="1"
                                                    @if ($is_available == 1) checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"
                                                    style="--primary: var(--black-50);"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('Available Now') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value="0"
                                                    @if ($is_available === '0') checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('Upcoming') }}</span>
                                            </label>
                                            <label class="aiz-checkbox mb-3">
                                                <input type="radio" name="is_available" value=""
                                                    @if ($is_available === null) checked @endif
                                                    onchange="filter(event)">
                                                <span class="aiz-square-check border_black"></span>
                                                <span
                                                    class="fs-14 fw-400 text-dark hover-effect-list-item">{{ translate('All') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Contents -->
                    <div class="col-xl-9 axv-listing-products-col">
                        <div class="axv-listing-offer-card mb-3">
                            <div class="axv-listing-offer-content">
                                <p class="axv-listing-offer-kicker mb-2">Shop wit us!</p>
                                <h2 class="axv-listing-offer-title mb-3">Get 40% Off for<br>all iteams</h2>
                                <a href="{{ route('flash-deals') }}" class="axv-listing-offer-cta">Shop Now <span>&#8594;</span></a>
                            </div>
                            <div class="axv-listing-offer-image">
                                @if(isset($category_id) && $category->banner != null)
                                    <img src="{{ uploaded_asset($category->banner) }}" alt="{{ $category->getTranslation('name') }}">
                                @elseif(isset($brand_id) && get_single_brand($brand_id)->logo != null)
                                    <img src="{{ uploaded_asset(get_single_brand($brand_id)->logo) }}" alt="{{ get_single_brand($brand_id)->getTranslation('name') }}">
                                @elseif(isset($products) && $products->first() && $products->first()->thumbnail_img != null)
                                    <img src="{{ uploaded_asset($products->first()->thumbnail_img) }}" alt="Offer model">
                                @else
                                    <img src="{{ static_asset('assets/img/demo/wepik-photo-mode.png.jpeg') }}" alt="Offer model">
                                @endif
                            </div>
                        </div>

                        <style>
                            @media (min-width: 1200px) {
                                .axv-listing-scroll-layout {
                                    align-items: flex-start;
                                }
                                .axv-listing-sidebar-col {
                                    align-self: stretch;
                                }
                                .axv-listing-sidebar-col .aiz-filter-sidebar {
                                    position: static !important;
                                    z-index: 1 !important;
                                    height: 100%;
                                }
                                .axv-listing-sidebar-col .aiz-filter-sidebar .collapse-sidebar {
                                    position: sticky;
                                    top: 120px;
                                    max-height: calc(100vh - 130px);
                                    overflow-y: auto;
                                    overflow-x: hidden;
                                    z-index: 1;
                                }
                            }
                            .axv-listing-offer-card {
                                background: #efeff2;
                                border-radius: 26px;
                                padding: 22px 28px;
                                display: flex;
                                align-items: center;
                                justify-content: space-between;
                                min-height: 260px;
                                gap: 28px;
                            }
                            .axv-listing-offer-content {
                                max-width: 55%;
                            }
                            .axv-listing-offer-kicker {
                                font-size: 18px;
                                line-height: 1.25;
                                font-weight: 500;
                                color: #0c1631;
                            }
                            .axv-listing-offer-title {
                                font-size: 54px;
                                line-height: 1.15;
                                font-weight: 700;
                                color: #0c1631;
                            }
                            .axv-listing-offer-cta {
                                font-size: 18px;
                                line-height: 1.2;
                                font-weight: 700;
                                color: #0c1631;
                                text-decoration: none;
                                display: inline-flex;
                                align-items: center;
                                gap: 8px;
                                margin-top: 8px;
                            }
                            .axv-listing-offer-cta:hover {
                                color: #0c1631;
                                text-decoration: none;
                            }
                            .axv-listing-offer-image {
                                width: 45%;
                                display: flex;
                                justify-content: flex-end;
                                align-items: flex-end;
                                height: 250px;
                            }
                            .axv-listing-offer-image img {
                                height: 100%;
                                width: auto;
                                max-width: 100%;
                                object-fit: contain;
                                object-position: center bottom;
                                display: block;
                            }
                            @media (max-width: 1199px) {
                                .axv-listing-offer-kicker {
                                    font-size: 16px;
                                }
                                .axv-listing-offer-title {
                                    font-size: 34px;
                                }
                                .axv-listing-offer-cta {
                                    font-size: 16px;
                                }
                            }
                            @media (max-width: 767px) {
                                .axv-listing-offer-card {
                                    flex-direction: row;
                                    align-items: center;
                                    justify-content: space-between;
                                    padding: 18px 20px;
                                    border-radius: 18px;
                                    min-height: 0;
                                    gap: 14px;
                                }
                                .axv-listing-offer-content,
                                .axv-listing-offer-image {
                                    max-width: none;
                                }
                                .axv-listing-offer-content {
                                    width: 58%;
                                }
                                .axv-listing-offer-kicker {
                                    font-size: 14px;
                                }
                                .axv-listing-offer-title {
                                    font-size: 22px;
                                    margin-bottom: 10px !important;
                                }
                                .axv-listing-offer-cta {
                                    font-size: 14px;
                                    margin-top: 0;
                                }
                                .axv-listing-offer-image {
                                    width: 42%;
                                    justify-content: center;
                                    height: 170px;
                                }
                                .axv-listing-offer-image img {
                                    height: 100%;
                                    max-height: none;
                                }
                            }
                        </style>

                        <!-- Breadcrumb -->
                        <ul class="breadcrumb mb-0 bg-transparent py-0 px-0 mt-2 d-flex align-items-center">
                            <li class=" has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            @if (!isset($category_id) && !isset($brand_id))
                                <i class="las la-angle-right fs-12 fw-600"></i>
                                <li class=" fw-700  text-dark fs-12">
                                    "{{ translate('All Categories') }}"
                                </li>
                            @else
                                <i class="las la-angle-right fs-12 fw-600 show_cat1 d-none"></i>
                                <li class=" fw-700  text-dark fs-12 show_cat1 d-none">
                                    "{{ translate('All Categories') }}"
                                </li>

                                @if (!isset($brand_id))
                                    <i class="las la-angle-right fs-12 fw-600 hide_cat1"></i>
                                    <li class=" opacity-50 hov-opacity-100 fs-12 hide_cat1">
                                        <a class="text-reset"
                                            href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                    </li>
                                @endif
                            @endif
                            @if (isset($brand_id))
                                <i class="las la-angle-right fs-12 fw-600 hide_cat1 "></i>
                                <li class=" fw-700  text-dark opacity-50 hov-opacity-100 fs-12 hide_cat1">
                                    {{ translate('Brand') }}
                                </li>

                                <i class="las la-angle-right fs-12 fw-600 hide_cat1"></i>
                                <li class=" fw-700  text-dark  fs-12 hide_cat1">
                                    "{{ $brand_name }}"
                                </li>
                            @endif

                            @if (isset($category_id))
                                <i class="las la-angle-right fs-12 fw-600 d-flex hide_cat1"></i>
                                <li class="text-dark fw-600 fs-12 hide_cat1">
                                    "{{ $category_search->getTranslation('name') }}"
                                </li>
                            @endif
                        </ul>

                        <!-- Top Filters -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                            <!-- Left: Results count -->
                            <div class="d-flex align-items-center mb-2 mb-md-0" id="search_product_count">
                                <span class="fs-15 text-secondary mr-1" style="color: #6c757d;">{{ translate('Showing') }}</span>
                                <span class="fs-15 fw-bold text-dark mx-1" id="total_product_count">{{ $products->total() }}</span>
                                <span class="fs-15 text-secondary ml-1" style="color: #6c757d;">{{ translate('results') }}</span>
                                <input type="hidden" name="keyword" value="{{ $query ?? '' }}">
                            </div>
                            <div class="display-none fs-14 ml-2 text-muted" id="searching_product">{{ translate('searching..') }}</div>

                            <!-- Right: Sort & Filter -->
                            <div class="d-flex align-items-center" style="gap: 12px;">
                                <!-- Sort By -->
                                <style>
                                    .custom-sort-select .bootstrap-select > .dropdown-toggle {
                                        background: transparent !important;
                                        border: none !important;
                                        box-shadow: none !important;
                                        padding-left: 0 !important;
                                        font-weight: 600 !important;
                                        color: #111 !important;
                                        outline: none !important;
                                    }
                                    .custom-sort-select .bootstrap-select > .dropdown-menu {
                                        border-radius: 12px !important;
                                        border: 1px solid #e2e5ec !important;
                                        box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important;
                                        padding: 8px 0 !important;
                                        margin-top: 8px !important;
                                    }
                                    .custom-sort-select .bootstrap-select .dropdown-menu li a {
                                        font-size: 14px !important;
                                        padding: 8px 20px !important;
                                        color: #444 !important;
                                        font-weight: 500 !important;
                                    }
                                    .custom-sort-select .bootstrap-select .dropdown-menu li a:hover, 
                                    .custom-sort-select .bootstrap-select .dropdown-menu li.selected a,
                                    .custom-sort-select .bootstrap-select .dropdown-menu li.active a {
                                        background-color: #f8f9fa !important;
                                        color: #111 !important;
                                    }
                                </style>
                                <div class="custom-sort-select d-flex align-items-center position-relative bg-white border rounded-pill px-3" style="border-color: #e2e5ec !important; height: 38px; min-width: 140px;">
                                    <select id="select_option"
                                        class="form-control aiz-selectpicker"
                                        name="sort_by" onchange="filter(event)" data-minimum-results-for-search="Infinity">
                                        <option value="" disabled hidden selected>{{ translate('Sort by') }}</option>
                                        <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest') }}</option>
                                        <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest') }}</option>
                                        <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high') }}</option>
                                        <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low') }}</option>
                                    </select>
                                </div>

                                <!-- Filter Button -->
                                <button type="button" class="btn btn-dark rounded-pill d-flex align-items-center fw-600 px-3 d-xl-none" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" style="height: 38px; gap: 6px; font-size: 14px;">
                                    {{ translate('Filters') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-white"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="px-3">
                            <div class="row g-3 row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2"
                                id="products-row">
                                {{-- @foreach ($products as $key => $product)
                                    <div class="col border-right border-bottom has-transition hov-shadow-out z-1 ">
                                        @if (isset($product_type) && $product_type == 'preorder_product')
                                            @include('preorder.frontend.product_box3', [
                                                'product' => $product,
                                            ])
                                        @else
                                            @include('frontend.product_box_for_listing_page', [
                                                'product' => $product,
                                            ])
                                        @endif
                                    </div>
                                @endforeach --}}
                            </div>
                        </div>

                        <div class="aiz-pagination mt-4" id="pagination"></div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        let category_page_first_time = true;
        let brand_page_first_time = true;
        let session_data_first_time = true;
        let currentPage = 1;
        let lastPage = 1;
        let isLoadingProducts = false;

        function filter(e) {
            // alert("working or not")
            if (e) e.preventDefault();

            const target = e ? e.target : null;

            if (target && target.type === 'checkbox') {
                const parent = target.parentElement;
                if (parent) {
                    const children = parent.children;
                    if (children.length > 0) {
                        const lastSibling = children[children.length - 1];

                        if (target.checked) {
                            lastSibling.classList.add('fw-bold');
                        } else {
                            lastSibling.classList.remove('fw-bold');
                        }
                    }
                }
            }

            filter_data();
        }


        function rangefilter(arg) {
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter_data();
        }

        function filter_data(page = 1, append = false) {
            if (isLoadingProducts) return;
            isLoadingProducts = true;
            $("#search_product_count").hide();
            $("#searching_product").show();
            var formData = $('#search-form').serialize();
            formData += '&page=' + page;

            // preoerder route to search page time
            if (session_data_first_time) {
                const form_all_preorder_page = @json($form_all_preorder_page);
                // alert(form_all_preorder_page);
                if (form_all_preorder_page && form_all_preorder_page === 'preorder_product') {
                    formData = formData.replace(/(&|^)product_type=[^&]*/g, '');
                    formData += '&product_type=' + 'preorder_product';
                    $('input[name="product_type"][value="preorder_product"]').prop('checked', true);
                    // alert(formData)
                    session_data_first_time = false;
                }
            }

            // category filter page some logic here
            let category_id = <?php echo $category_id ?? 'null'; ?>;
            let brand_id = <?php echo $brand_id ?? 'null'; ?>;
            if (category_page_first_time && category_id !== null && category_id !== 0 && category_id !== undefined) {
                formData += '&categories[]=' + category_id;
                category_page_first_time = false;
            } else if (brand_page_first_time && brand_id !== null && brand_id !== 0 && brand_id !== undefined) {
                formData += "&brand_id=" + brand_id;
                brand_page_first_time = false;
            } else {
                $('.hide_cat1').each(function() {
                    this.style.setProperty('display', 'none', 'important');
                });
                $('.show_cat1').removeClass('d-none');
            }

            // alert(formData);

            // product types ways some action this page
            if (formData.includes('product_type=preorder_product')) {
                $('#product_type_badge_preorder').removeClass('preorder-border-dashed my-2 text-muted  fw-600');
                $('#product_type_badge_preorder').addClass('bg-soft-dark  my-2 text-white');
                $('#product_type_badge_general').removeClass('bg-soft-dark my-2  text-white');
                $('#product_type_badge_general').addClass('preorder-border-dashed  text-muted my-2 fw-600');

                $('#preorder_cagegories_box').slideDown(300);
                $('#general_cagegories_box').slideUp(300);

                $('.preorder-time-hide').fadeOut(400);
                $('.preorder-time-show').slideDown(400);
            } else {
                $('#product_type_badge_general').removeClass('preorder-border-dashed my-2  text-muted  fw-600');
                $('#product_type_badge_general').addClass('bg-soft-dark my-2  text-white');
                $('#product_type_badge_preorder').removeClass('bg-soft-dark  my-2 text-white');
                $('#product_type_badge_preorder').addClass('preorder-border-dashed my-2 text-muted  fw-600');

                $('#preorder_cagegories_box').slideUp(300);
                $('#general_cagegories_box').slideDown(300);

                $('.preorder-time-hide').fadeIn(400);
                $('.preorder-time-show').slideUp(400);
            }

            // alert(JSON.stringify(formData));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('suggestion.search2') }}",
                type: 'get',
                data: formData,
                success: function(response) {
                    // alert(JSON.stringify(response))
                    $("#search_product_count").show();
                    $("#searching_product").hide();
                    if (append) {
                        $('#products-row').append(response.product_html);
                    } else {
                        $('#products-row').html(response.product_html);
                    }
                    $('#pagination').empty().hide();
                    $('#total_product_count').text(response.total_product_count);
                    currentPage = response.current_page || page;
                    lastPage = response.last_page || page;

                    if (response.banner_url) {
                        $('.axv-listing-offer-image img').attr('src', response.banner_url);
                    }

                    if (!append) {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                },
                complete: function() {
                    isLoadingProducts = false;
                }
            });
        }

        // Handle page button click
        $(document).on('click', '.page-btn', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            filter_data(page);
        });
    </script>




    <script type="text/javascript">
        $(document).ready(function() {

            const path = window.location.pathname;
            if (path.includes('/search')) {
                filter_data();
            } else {
                filter_data();
            }

            function setActiveButtonByWidth() {
                var width = $(window).width();
                var cols = 4;

                if (width < 576) {
                    cols = 2;
                } else if (width >= 576 && width < 768) {
                    cols = 3;
                } else if (width >= 768 && width < 1200) {
                    cols = 4;
                } else {
                    cols = 4;
                }

                $('.btn-col-filter').removeClass('active-cols');
                $('.btn-col-filter[data-cols="' + cols + '"]').addClass('active-cols');
                $('.row.gutters-16').removeClass('row-cols-2 row-cols-3 row-cols-4 row-cols-6')
                    .addClass('row-cols-' + cols);
            }


            setActiveButtonByWidth();

            $(window).resize(function() {
                setActiveButtonByWidth();
            });

            $('.btn-col-filter').on('click', function() {

                $('.btn-col-filter').removeClass('active-cols');
                $(this).addClass('active-cols');

                var colValue = $(this).data('cols');

                var $row = $('#products-row');

                $row.removeClass(function(index, className) {
                    return (className.match(/(^|\s)row-cols-\S+/g) || []).join(' ');
                });

                $row.addClass('row-cols-xxl-' + colValue);
                $row.addClass('row-cols-xl-' + colValue);
                $row.addClass('row-cols-lg-' + colValue);
                $row.addClass('row-cols-md-' + colValue);
                $row.addClass('row-cols-2');

            });

            $(window).on('scroll', function() {
                if (isLoadingProducts) return;
                if (currentPage >= lastPage) return;

                const scrollTop = $(window).scrollTop();
                const windowHeight = $(window).height();
                const documentHeight = $(document).height();

                if (scrollTop + windowHeight >= documentHeight - 220) {
                    filter_data(currentPage + 1, true);
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            document.querySelectorAll('.see_more_toggle_btn').forEach((btn) => {
                const lessBtn = btn.closest('div').nextElementSibling.querySelector('.less_toggle_btn');
                const element_list = btn.parentElement.previousElementSibling;
                const children = Array.from(element_list.children);


                let visibleCount = 5;

                // first five element show
                children.forEach((child, index) => {
                    // console.log(child)
                    if (index < visibleCount) {
                        child.style.setProperty('display', 'block', 'important');
                    } else {
                        child.style.setProperty('display', 'none', 'important');
                    }
                });
                if (children.length <= 5) {
                    btn.style.display = 'none';
                }

                // click to add more element
                btn.addEventListener('click', () => {

                    visibleCount += 5;

                    children.forEach((child, index) => {
                        if (index < visibleCount) {
                            child.style.setProperty('display', 'block', 'important');
                        }
                    });

                    if (visibleCount >= children.length) {
                        btn.style.display = 'none';
                        lessBtn.style.display = 'inline-block';
                    }
                });


                lessBtn.addEventListener('click', () => {
                    visibleCount = 5;

                    children.forEach((child, index) => {
                        child.style.setProperty('display', index < visibleCount ? 'block' :
                            'none', 'important');
                    });

                    // Toggle buttons
                    lessBtn.style.display = 'none';
                    btn.style.display = 'inline-block';
                });

                lessBtn.style.display = 'none';


            });


        });
    </script>
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/hummingbird-treeview2.js') }}"></script>

    <script>
        $(document).ready(function() {

            // $("#treeview2").hummingbird();
            var $tree = $('#treeview2');

            var oldShow = $.fn.show;
            var oldHide = $.fn.hide;

            // Override show for smooth animation
            $.fn.show = function(speed, oldCallback) {
                if ($(this).closest($tree).length) {
                    return this.stop(true, true).slideDown(400, oldCallback);
                } else {
                    return oldShow.apply(this, arguments);
                }
            };

            // Override hide for smooth animation
            $.fn.hide = function(speed, oldCallback) {
                if ($(this).closest($tree).length) {
                    return this.stop(true, true).slideUp(400, oldCallback);
                } else {
                    return oldHide.apply(this, arguments);
                }
            };

            // Initialize Hummingbird treeview2
            $tree.hummingbird();

            var selected_ids = '{{ implode(',', $old_categories) }}';
            if (selected_ids != '') {
                const myArray = selected_ids.split(",");
                for (let i = 0; i < myArray.length; i++) {
                    const element = myArray[i];

                    $('#category_checkidgenerel_' + element).prop('checked', true);
                    $('#category_checkid_textgenerel_' + element).addClass('fw-bold');
                    $('#category_checkidgenerel_' + element).parents("ul").css("display", "block");
                }
            }
        });


        function showLabels() {
            document.querySelectorAll('.slider-value-text').forEach(label => {
                label.style.display = 'block';
            });
        }

        function hideLabels() {
            document.querySelectorAll('.slider-value-text').forEach(label => {
                label.style.display = 'none';
            });
        }


        document.querySelectorAll('.noUi-connect, .noUi-touch-area').forEach((element) => {
            // Desktop 
            element.addEventListener('mouseenter', showLabels);
            element.addEventListener('mouseleave', function() {
                setTimeout(() => {
                    hideLabels();
                }, 2000);
            });

            // Mobile 
            element.addEventListener('touchstart', showLabels);
            element.addEventListener('touchend', function() {
                setTimeout(() => {
                    hideLabels();
                }, 2000);
            });
        });
        document.getElementById('input-slider-range').addEventListener('click', function() {
            showLabels();

            setTimeout(function() {
                hideLabels();
            }, 2000);
        });
    </script>



    <script>
        window.onload = function() {
            setTimeout(function() {

                const mainUl = $('#category_filter div ul');

                if (mainUl.length === 0) {
                    return alert("Main UL not found!");
                }


                function processUl($ul) {
                    $ul.addClass('ul_is_empty');

                    $ul.children('li').each(function() {
                        const $li = $(this);


                        const $nestedUl = $li.children('ul');
                        if ($nestedUl.length > 0) {

                            processUl($nestedUl);



                            if ($nestedUl.children('li').length === 0) {
                                $nestedUl.prev('i.las.pt-3px.la-angle-right').remove();
                                $nestedUl.remove();
                            }
                        } else {
                            const countAttr = $li.attr('count');
                            if (countAttr === "0") {
                                $li.remove();
                            }
                        }
                    });
                }

                processUl(mainUl);

                $('.ul_is_empty').each(function() {
                    const $ul = $(this);

                    if ($ul.children('li').length === 0) {
                        $ul.prev('i.las.pt-3px.la-angle-right').remove();
                        $ul.remove();
                    }
                });

            }, 0000);

            setTimeout(function() {

                const mainUl = $('#category_filter_preorder div ul');

                if (mainUl.length === 0) {
                    return alert("Main UL not found!");
                }


                function processUl($ul) {
                    $ul.addClass('ul_is_empty');


                    $ul.children('li').each(function() {
                        const $li = $(this);


                        const $nestedUl = $li.children('ul');
                        if ($nestedUl.length > 0) {

                            processUl($nestedUl);



                            if ($nestedUl.children('li').length === 0) {
                                $nestedUl.prev('i.las.pt-3px.la-angle-down').remove();
                                $nestedUl.remove();
                            }
                        } else {
                            const countAttr = $li.attr('count');
                            if (countAttr === "0") {
                                $li.remove();
                            }
                        }
                    });
                }

                processUl(mainUl);

                $('.ul_is_empty').each(function() {
                    const $ul = $(this);

                    if ($ul.children('li').length === 0) {
                        $ul.prev('i.las.pt-3px.la-angle-right').remove();
                        $ul.remove();
                    }
                });

            }, 0000);

        };
    </script>

    </script>
@endsection
