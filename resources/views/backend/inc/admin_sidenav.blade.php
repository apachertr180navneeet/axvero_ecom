<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if(get_setting('system_logo_black') != null)
                <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_black')) }}" class="brand-icon"
                    alt="{{ get_setting('site_name') }}">
                @else
                <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon"
                    alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>
        <div class="aiz-side-nav-wrap">
            <div class="px-3 mb-3 position-relative">
                <input class="form-control bg-transparent rounded-2 form-control-sm text-white fs-14" type="text"
                    name="" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
                <span class="absolute-top-right pr-3 mr-3" style="margin-top: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path id="search_FILL0_wght200_GRAD0_opsz20"
                            d="M176.921-769.231l6.255-6.255a5.99,5.99,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.312,5.312,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.043,6.043,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z"
                            transform="translate(-176.307 785.231)" fill="#4e5767" />
                    </svg>
                </span>
            </div>
            <ul class="aiz-side-nav-list" id="search-menu">
            </ul>
            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                {{-- Dashboard --}}
                @can('admin_dashboard')
                <li class="aiz-side-nav-item">
                    <a href="{{route('admin.dashboard')}}" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="_3d6902ec768df53cd9e274ca8a57e401"
                                    data-name="3d6902ec768df53cd9e274ca8a57e401"
                                    d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z"
                                    transform="translate(-2 -2)" fill="#575b6a" fill-rule="evenodd" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Dashboard')}}</span>
                    </a>
                </li>
                @endcan

                <!-- Product -->
                @canany(['add_new_product', 'show_all_products','show_in_house_products','show_seller_products','add_digital_product','edit_digital_product','product_bulk_import',
                        'product_bulk_export','view_product_categories','view_all_brands', 'brand_bulk_upload','view_product_attributes','view_colors','view_product_warranties', 'smart-bar', 'view_custom_label', 'view_product_reviews'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="13.714" viewBox="0 0 16 13.714">
                                    <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -4)">
                                        <path id="Path_40719" data-name="Path 40719"
                                            d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z"
                                            fill="#575b6a" />
                                        <path id="Path_40720" data-name="Path 40720"
                                            d="M12.571,15.143H16A.571.571,0,0,0,16,14H12.571a.571.571,0,0,0,0,1.143Z"
                                            transform="translate(-4.286 -4.286)" fill="#575b6a" />
                                    </g>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Products')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @can('add_new_product')
                            <li class="aiz-side-nav-item">
                                <a class="aiz-side-nav-link" href="{{route('products.create')}}">
                                    <span class="aiz-side-nav-text">{{translate('Add New product')}}</span>
                                </a>
                            </li>
                            @endcan
                            @can('show_all_products')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('products.all')}}" class="aiz-side-nav-link {{ areActiveRoutes(['digitalproducts.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('show_in_house_products')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('products.admin')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.admin', 'products.admin.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('In House Products') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('add_digital_product')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('digitalproducts.create') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['digitalproducts.create']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Digital Product') }}</span>
                                </a>
                            </li>
                            @endcan
                            @if(get_setting('vendor_system_activation') == 1)
                            @can('show_seller_products')
                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Seller Product')}}</span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.seller','all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.seller.edit']) }}">
                                            <span class="aiz-side-nav-text">{{translate('All Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.seller','physical') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text">{{translate('Physical Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.seller','digital') }}" class="aiz-side-nav-link">
                                            <span class="aiz-side-nav-text">{{translate('Digital Products')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan
                            @endif

                            @can('product_bulk_import')
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{ translate('Bulk Import') }}</span>
                                </a>
                            </li>
                            @endcan
                            @can('product_bulk_export')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('product_bulk_export.index')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Bulk Export')}}</span>
                                </a>
                            </li>
                            @endcan
                            @can('view_product_categories')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('categories.index')}}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Category')}}</span>
                                </a>
                            </li>
                            @endcan
                            @can('set_category_wise_discount')
                            <li class="aiz-side-nav-item">
                                <a href="{{route('categories_wise_product_discount')}}" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Category Based Discount')}}</span>
                                </a>
                            </li>
                            @endcan
                            @canany(['view_all_brands', 'brand_bulk_upload'])
                                <li class="aiz-side-nav-item">
                                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Brand')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_all_brands')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('brands.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit'])}}">
                                                <span class="aiz-side-nav-text">{{translate('All Brands')}}</span>
                                            </a>
                                        </li>
                                        @endcan
                                        @can('brand_bulk_upload')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('brand_bulk_upload.index') }}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text">{{translate('Brand Bulk Import')}}</span>
                                            </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcan
                            @can('view_custom_label')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('custom_label.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['custom_label.edit', 'custom_label.create'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Custom Label')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_product_attributes')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('attributes.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit','attributes.show'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Attribute')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_colors')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('colors')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['colors','colors.edit','colors.create'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Colors')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['view_size_charts', 'view_measurement_points'])
                            <li class="aiz-side-nav-item">
                                <a href="javascript:void(0);" class="aiz-side-nav-link">
                                    <span class="aiz-side-nav-text">{{translate('Size Guide')}}</span>
                                    <span class="aiz-side-nav-arrow"></span>
                                </a>
                                <ul class="aiz-side-nav-list level-3">
                                    @can('view_size_charts')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('size-charts.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['size-charts.index', 'size-charts.create', 'size-charts.edit'])}}">
                                                <span class="aiz-side-nav-text">{{translate('Size Chart')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('view_measurement_points')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('measurement-points.index') }}" class="aiz-side-nav-link">
                                                <span class="aiz-side-nav-text">{{translate('Measurement Points')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                            @endcan
                            @can('view_product_warranties')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('warranties.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['warranties.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Warranty')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('smart-bar')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link {{ areActiveRoutes(['smart.bar'])}}" href="{{route('smart.bar')}}">
                                        <span class="aiz-side-nav-text">{{translate('Smart Bar')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_product_reviews')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('reviews.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['detail-reviews', 'custom-review.edit']) }}">
                                        <span class="aiz-side-nav-text">{{translate('Product Reviews')}}</span>
                                    </a>
                                </li>
                            @endcan
                             <li class="aiz-side-nav-item">
                                    <a href="{{route('product_limit.create')}}" class="aiz-side-nav-link {{ areActiveRoutes(['detail-reviews', 'custom-review.edit']) }}">
                                        <span class="aiz-side-nav-text">{{translate('Product Limit')}}</span>
                                    </a>
                                </li>
                        </ul>
                    </li>
                @endcanany

                <!-- Note  -->
                @canany(['view_notes', 'add_note'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001" viewBox="0 0 16 16.001">
                                    <path id="Union_64" data-name="Union 64" d="M.333,16A.315.315,0,0,1,0,15.668V.335A.315.315,0,0,1,.333,0h9.31a.285.285,0,0,1,.123.014A.318.318,0,0,1,9.9.1l2.667,2.667.009.01a.293.293,0,0,1,.079.132.274.274,0,0,1,.012.112V5.835l1.267-1.267a.322.322,0,0,1,.466,0l1.5,1.5a.322.322,0,0,1,0,.466L12.667,9.768v5.9a.315.315,0,0,1-.333.333Zm.334-.666H12v-4.9L9.133,13.3a.3.3,0,0,1-.233.1H8.882L6.4,14.468a.2.2,0,0,1-.133.033.332.332,0,0,1-.3-.466l.589-1.368H2.667a.333.333,0,0,1,0-.667H6.843l.258-.6a.321.321,0,0,1,.176-.177L8.5,10H2.667a.333.333,0,0,1,0-.667h6.5L12,6.5V3.335H9.667A.315.315,0,0,1,9.333,3V.668H.667Zm6.233-1.8,1.4-.6-.8-.8-.1.239a.323.323,0,0,1-.074.172Zm2-.967,6.3-6.3-.283-.283-6.3,6.3ZM7.867,11.534l.284.284,6.3-6.3-.283-.283L12.624,6.777a.291.291,0,0,1-.115.115L9.558,9.844a.291.291,0,0,1-.115.115ZM10,2.668h1.533L10.767,1.9,10,1.135ZM2.667,7.335a.333.333,0,0,1,0-.667H10a.333.333,0,1,1,0,.667Zm0-2.668a.333.333,0,1,1,0-.666H10a.333.333,0,1,1,0,.666Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Notes')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <!--Submenu-->
                        <ul class="aiz-side-nav-list level-2">
                            @can('add_note')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link" href="{{route('note.create')}}">
                                        <span class="aiz-side-nav-text">{{translate('Add New Note')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_notes')
                                <li class="aiz-side-nav-item">
                                    <a class="aiz-side-nav-link" href="{{route('note.index')}}">
                                        <span class="aiz-side-nav-text">{{translate('Note List')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <!-- Sale -->
                @canany(['view_all_orders', 'view_inhouse_orders','view_seller_orders','view_pickup_point_orders'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15.997" height="16" viewBox="0 0 15.997 16">
                                <g id="Layer_2" data-name="Layer 2" transform="translate(-2 -1.994)">
                                    <path id="Path_40726" data-name="Path 40726"
                                        d="M4.857,12.571H3.714A1.714,1.714,0,0,0,2,14.285V20.57a1.714,1.714,0,0,0,1.714,1.714H4.857A1.714,1.714,0,0,0,6.571,20.57V14.285a1.714,1.714,0,0,0-1.714-1.714Zm.571,8a.571.571,0,0,1-.571.571H3.714a.571.571,0,0,1-.571-.571V14.285a.571.571,0,0,1,.571-.571H4.857a.571.571,0,0,1,.571.571Zm5.142-6.284H9.427A1.714,1.714,0,0,0,7.713,16V20.57a1.714,1.714,0,0,0,1.714,1.714H10.57a1.714,1.714,0,0,0,1.714-1.714V16A1.714,1.714,0,0,0,10.57,14.285Zm.571,6.284a.571.571,0,0,1-.571.571H9.427a.571.571,0,0,1-.571-.571V16a.571.571,0,0,1,.571-.571H10.57a.571.571,0,0,1,.571.571ZM16.283,12H15.14a1.714,1.714,0,0,0-1.714,1.714V20.57a1.714,1.714,0,0,0,1.714,1.714h1.143A1.714,1.714,0,0,0,18,20.57V13.714A1.714,1.714,0,0,0,16.283,12Zm.571,8.57a.571.571,0,0,1-.571.571H15.14a.571.571,0,0,1-.571-.571V13.714a.571.571,0,0,1,.571-.571h1.143a.571.571,0,0,1,.571.571Z"
                                        transform="translate(0 -4.289)" fill="#575b6a" />
                                    <path id="Path_40727" data-name="Path 40727"
                                        d="M17.947,2.548a.571.571,0,0,0-.366-.24l-1.588-.3a.571.571,0,1,0-.213,1.122l.093.018L11.233,5.932l-5.45-2.18a.572.572,0,1,0-.424,1.062L11.072,7.1a.571.571,0,0,0,.506-.041L16.68,4l-.067.354a.571.571,0,0,0,.457.668.579.579,0,0,0,.107.01.571.571,0,0,0,.56-.465l.3-1.588A.568.568,0,0,0,17.947,2.548Z"
                                        transform="translate(-1.286)" fill="#575b6a" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Sales')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <!--Submenu-->
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_all_orders')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('all_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show'])}}">
                                <span class="aiz-side-nav-text">{{translate('All Orders')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_inhouse_orders')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('inhouse_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show'])}}">
                                <span class="aiz-side-nav-text">{{translate('Inhouse orders')}}</span>
                            </a>
                        </li>
                        @endcan
                        @if (get_setting('vendor_system_activation') == 1)
                        @can('view_seller_orders')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller_orders.index', 'seller_orders.show'])}}">
                                <span class="aiz-side-nav-text">{{translate('Seller Orders')}}</span>
                            </a>
                        </li>
                        @endcan
                        @endif

                        @can('view_pickup_point_orders')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('pick_up_point.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_point.index','pick_up_point.order_show'])}}">
                                <span class="aiz-side-nav-text">{{translate('Pick-up Point Orders')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_all_unpaid_orders')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('unpaid_orders.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['unpaid_orders.index'])}}">
                                <span class="aiz-side-nav-text">{{translate('Unpaid Orders')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- Customers -->
                @canany(['view_all_customers','view_classified_products','view_classified_packages'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Path_40769" data-name="Path 40769"
                                    d="M8,10.667A2.667,2.667,0,1,1,10.667,8,2.667,2.667,0,0,1,8,10.667Zm0-4A1.333,1.333,0,1,0,9.333,8,1.333,1.333,0,0,0,8,6.667Zm4,8.667a4,4,0,1,0-8,0,.667.667,0,0,0,1.333,0,2.667,2.667,0,1,1,5.333,0,.667.667,0,0,0,1.333,0Zm0-10a2.667,2.667,0,1,1,2.667-2.667A2.667,2.667,0,0,1,12,5.333Zm0-4a1.333,1.333,0,1,0,1.333,1.333A1.333,1.333,0,0,0,12,1.333ZM16,10a4,4,0,0,0-4-4,.667.667,0,0,0,0,1.333A2.667,2.667,0,0,1,14.667,10,.667.667,0,1,0,16,10ZM4,5.333A2.667,2.667,0,1,1,6.667,2.667,2.667,2.667,0,0,1,4,5.333Zm0-4A1.333,1.333,0,1,0,5.333,2.667,1.333,1.333,0,0,0,4,1.333ZM1.333,10A2.667,2.667,0,0,1,4,7.333.667.667,0,0,0,4,6a4,4,0,0,0-4,4,.667.667,0,0,0,1.333,0Z"
                                    fill="#575b6a" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_all_customers')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customers.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['customers.create'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Customer list') }}</span>
                            </a>
                        </li>
                        @endcan
                        <li class="aiz-side-nav-item">
    <a href="{{ route('join-agent') }}" class="aiz-side-nav-link">
        <span class="aiz-side-nav-text">Members List</span>
    </a>
</li>
                        @can('manage_bulk_buyers')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('bulk_buyers.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['bulk_buyers.index', 'bulk_buyers.show', 'bulk_buyers.customers'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Bulk Buyers') }}</span>
                            </a>
                        </li>
                        @endcan
                        @if(get_setting('classified_product') == 1)
                        @can('view_classified_products')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('classified_products')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Classified Products')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_classified_packages')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('customer_packages.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Classified Packages') }}</span>
                            </a>
                        </li>
                        @endcan
                        @endif
                    </ul>
                </li>
                @endcanany

                {{-- Uploads Files --}}
                <li class="aiz-side-nav-item">
                    <a href="{{ route('uploaded-files.index') }}"
                        class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create'])}}">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="layer1" transform="translate(-0.53 -0.53)">
                                    <path id="path3159"
                                        d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673ZM8.12,3.557,5.34,6.37a.572.572,0,0,0,0,.809.564.564,0,0,0,.81,0l1.8-1.824V10.8a.571.571,0,0,0,1.143,0V5.347l1.8,1.829a.571.571,0,0,0,.81-.806L8.935,3.557a.511.511,0,0,0-.815,0Zm-4.156,8.97a.571.571,0,0,0,0,1.143h9.128a.571.571,0,0,0,0-1.143Z"
                                        fill="#575b6a" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                    </a>
                </li>

                <!-- Reports -->
                @canany(['earning_report',
                'in_house_product_sale_report','seller_products_sale_report','products_stock_report','product_wishlist_report','user_search_report','commission_history_report','wallet_transaction_report'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg id="stats_3916778" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 16 16">
                                <path id="Path_40739" data-name="Path 40739"
                                    d="M16,16H2a2,2,0,0,1-2-2V0H1.333V14A.667.667,0,0,0,2,14.667H16Z" fill="#575b6a" />
                                <rect id="Rectangle_21340" data-name="Rectangle 21340" width="1.333" height="6"
                                    transform="translate(9.333 7.333)" fill="#575b6a" />
                                <rect id="Rectangle_21341" data-name="Rectangle 21341" width="1.333" height="6"
                                    transform="translate(4 7.333)" fill="#575b6a" />
                                <rect id="Rectangle_21342" data-name="Rectangle 21342" width="1.333" height="9.333"
                                    transform="translate(12 4)" fill="#575b6a" />
                                <rect id="Rectangle_21343" data-name="Rectangle 21343" width="1.333" height="9.333"
                                    transform="translate(6.667 4)" fill="#575b6a" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('earning_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('earning_payout_report.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Earning Report') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('in_house_product_sale_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('in_house_sale_report.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('In House Product Sale') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('seller_products_sale_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('seller_sale_report.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Seller Products Sale') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('products_stock_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('stock_report.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Products Stock') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('product_wishlist_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('wish_report.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Products wishlist') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('user_search_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('user_search_report.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('User Searches') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('commission_history_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('commission-log.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('wallet_transaction_report')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('wallet-history.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Wallet Recharge History') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!--Blog System-->
                @canany(['view_blogs','view_blog_categories'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Path_40771" data-name="Path 40771"
                                    d="M9.688,16H3.75A3.754,3.754,0,0,1,0,12.25V3.75A3.754,3.754,0,0,1,3.75,0h8.5A3.754,3.754,0,0,1,16,3.75V9.734a.625.625,0,0,1-1.25,0V3.75a2.5,2.5,0,0,0-2.5-2.5H3.75a2.5,2.5,0,0,0-2.5,2.5v8.5a2.5,2.5,0,0,0,2.5,2.5H9.688a.625.625,0,0,1,0,1.25ZM12.875,3.938a.625.625,0,0,0-.625-.625H6.531a.625.625,0,0,0,0,1.25H12.25A.625.625,0,0,0,12.875,3.938Zm0,2.5a.625.625,0,0,0-.625-.625H3.75a.625.625,0,0,0,0,1.25h8.5A.625.625,0,0,0,12.875,6.438Zm-6.25,2.5A.625.625,0,0,0,6,8.313H3.75a.625.625,0,0,0,0,1.25H6A.625.625,0,0,0,6.625,8.938Zm-3.5-5.062a.781.781,0,1,0,.781-.781A.781.781,0,0,0,3.125,3.875ZM15.332,15.332a2.284,2.284,0,0,0,0-3.226L13.141,9.915a4.506,4.506,0,0,0-2.31-1.236L9.06,8.325a.625.625,0,0,0-.735.735l.354,1.771a4.506,4.506,0,0,0,1.236,2.31l2.191,2.191a2.281,2.281,0,0,0,3.226,0ZM10.586,9.9a3.259,3.259,0,0,1,1.671.894l2.191,2.191a1.031,1.031,0,1,1-1.458,1.458L10.8,12.257A3.26,3.26,0,0,1,9.9,10.586l-.17-.852Z"
                                    fill="#575b6a" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Blog System') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_blogs')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['blog.create', 'blog.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('All Posts') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_blog_categories')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('blog-category.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.create', 'blog-category.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- marketing -->
                @canany(['view_all_flash_deals',
                'view_all_dynamic_popups',
                'view_all_custom_alerts',
                'manage_email_templates',
                'send_newsletter',
                'notification_settings',
                'view_all_notification_types',
                'send_custom_notification',
                'view_custom_notification_history',
                'send_bulk_sms',
                'view_all_subscribers',
                'view_all_coupons'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="_8dbc7a38f7bdee3f0be2c44d010760a2" data-name="8dbc7a38f7bdee3f0be2c44d010760a2"
                                    transform="translate(0 -4.027)">
                                    <path id="Path_40740" data-name="Path 40740"
                                        d="M38.286,16.393a.555.555,0,0,1-.344-.119L34.032,13.2a.557.557,0,0,1-.213-.438v-5.1a.556.556,0,0,1,.212-.438l3.91-3.074a.557.557,0,0,1,.9.438V15.836a.556.556,0,0,1-.556.557Zm-3.354-3.9,2.8,2.2V5.73l-2.8,2.2Z"
                                        transform="translate(-25.364 0)" fill="#575b6a" />
                                    <path id="Path_40741" data-name="Path 40741"
                                        d="M9.011,22.556H3.093a3.1,3.1,0,0,1,0-6.192H9.011a.557.557,0,0,1,.557.557V22A.557.557,0,0,1,9.011,22.556ZM3.093,17.478a1.982,1.982,0,0,0,0,3.964H8.455V17.478Z"
                                        transform="translate(0 -9.25)" fill="#575b6a" />
                                    <path id="Path_40742" data-name="Path 40742"
                                        d="M10.2,31.9a1.895,1.895,0,0,1-1.847-1.5l-.974-5.455a.557.557,0,1,1,1.089-.229l.975,5.455a.777.777,0,1,0,1.521-.32l-.824-4.74a.557.557,0,1,1,1.089-.229l.824,4.74A1.894,1.894,0,0,1,10.2,31.9Zm8.487-7.6h-.862a.557.557,0,0,1,0-1.114h.862a1.105,1.105,0,0,0,1.1-1.105,1.106,1.106,0,0,0-1.1-1.105h-.862a.557.557,0,0,1,0-1.114h.862a2.22,2.22,0,0,1,1.566,3.79A2.2,2.2,0,0,1,18.683,24.3Z"
                                        transform="translate(-4.9 -11.875)" fill="#575b6a" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Marketing') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_all_flash_deals')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('flash_deals.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Flash deals') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_all_dynamic_popups')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('dynamic-popups.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['dynamic-popups.index', 'dynamic-popups.create', 'dynamic-popups.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Dynamic Pop-up') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_all_custom_alerts')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('custom-alerts.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['custom-alerts.index', 'custom-alerts.create', 'custom-alerts.edit','custom-sale-alert.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Custom Alert') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_custom_sale_alert')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('custom-sale-alerts.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['custom-sale-alerts.index'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Custom Sell Alert') }}</span>
                            </a>
                        </li>
                        @endcan
                        @can('manage_email_templates')
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Email Templates')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('email-templates.index', 'admin') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Admin Templates')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('email-templates.index', 'seller') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Seller Templates')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('email-templates.index', 'customer') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Customer Templates')}}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('email-templates.index', 'all') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Common Templates')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endcan
                        
                        @can('send_newsletter')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('newsletters.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Newsletters') }}</span>
                            </a>
                        </li>
                        @endcan
                        @canany(['notification_settings','view_all_notification_types','send_custom_notification', 'view_custom_notification_history'])
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Notification')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                @can('notification_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('notification.settings') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Settings')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_all_notification_types')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('notification-type.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['notification-type.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Notification Types')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('send_custom_notification')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom_notification') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Custom Notification')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_custom_notification_history')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom_notification.history') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Custom Notification History')}}</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        @if (auth()->user()->can('send_bulk_sms'))
                        <li class="aiz-side-nav-item">
                            <a href="{{route('sms.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Bulk SMS') }}</span>
                                @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001"
                                    viewBox="0 0 16 14.001" class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="#f51350" />
                                </svg>
                                @endif
                            </a>
                        </li>
                        @endif
                        @can('view_all_subscribers')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{ translate('Subscribers') }}</span>
                            </a>
                        </li>
                        @endcan
                        @if (get_setting('coupon_system') == 1 && auth()->user()->can('view_all_coupons') )
                        <li class="aiz-side-nav-item">
                            <a href="{{route('coupon.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Coupon') }}</span>
                            </a>
                        </li>
                        @endif

                        @can('custom_visitors_setup')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('custom_product_visitors')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['custom_product_visitors'])}}">
                                <span class="aiz-side-nav-text">{{ translate('Custom Visitors') }}</span>
                            </a>
                        </li>
                        @endcan

                       
                    </ul>
                </li>
                @endcanany

                <!-- Affiliate System -->
                @canany(['affiliate_registration_form_config', 'affiliate_configurations', 'view_affiliate_users', 'view_affiliate_withdraw_requests', 'view_affiliate_logs'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Union_44" data-name="Union 44" d="M12.75,16a3.254,3.254,0,0,1-3.23-2.915,2.74,2.74,0,0,1-.832-.835,3.254,3.254,0,1,1-.462-4.526,2.736,2.736,0,0,1,1.294.618,3.235,3.235,0,0,1,2.83,2.83,2.736,2.736,0,0,1,.618,1.294A3.252,3.252,0,0,1,12.75,16Zm-2.228-3.023a1.745,1.745,0,1,0,1.251-1.251A3.267,3.267,0,0,1,10.522,12.977ZM3.25,16A3.254,3.254,0,0,1,.02,13.085,2.74,2.74,0,0,1,.852,12.25,3.254,3.254,0,1,1,5.378,7.724a2.736,2.736,0,0,1-.618,1.294,3.235,3.235,0,0,1-2.83,2.83,2.736,2.736,0,0,1-1.294.618A3.252,3.252,0,0,1,3.25,16Zm2.228-3.023A3.267,3.267,0,0,1,6.728,11.73,1.745,1.745,0,1,0,5.478,12.977ZM8,8A3.254,3.254,0,0,1,4.77,5.085,2.74,2.74,0,0,1,5.6,4.25,3.254,3.254,0,1,1,10.128-.276a2.736,2.736,0,0,1-.618,1.294,3.235,3.235,0,0,1-2.83,2.83,2.736,2.736,0,0,1-1.294.618A3.252,3.252,0,0,1,8,8ZM10.228,4.977A3.267,3.267,0,0,1,11.478,3.73a1.745,1.745,0,1,0-1.25,1.247Z" fill="#575b6a"/>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('Affiliate System') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('affiliate_registration_form_config')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.index']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Affiliate Options') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('affiliate_configurations')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.configs') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.configs']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Affiliate Configurations') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_affiliate_users')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.users') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.users', 'affiliate.users.show_verification_request']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Affiliate Users') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_affiliate_users')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.refferal_users') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.refferal_users']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Referral Users') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_affiliate_withdraw_requests')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.withdraw_requests') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.withdraw_requests']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Withdraw Requests') }}</span>
                            </a>
                        </li>
                        @endcan

                        @can('view_affiliate_logs')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('affiliate.logs_admin') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.logs_admin']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Affiliate Logs') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- Support -->
                @canany(['view_all_support_tickets','view_all_product_conversations','view_all_product_queries'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_28286" data-name="Group 28286" transform="translate(0)">
                                    <path id="Path_40743" data-name="Path 40743"
                                        d="M16,9.125a3.122,3.122,0,0,0-1.255-2.5,6.9,6.9,0,0,0-1.94-4.6,6.725,6.725,0,0,0-9.61,0,6.9,6.9,0,0,0-1.94,4.6,3.124,3.124,0,0,0,1.87,5.627h1.25A.625.625,0,0,0,5,11.625v-5A.625.625,0,0,0,4.375,6H3.125a3.129,3.129,0,0,0-.569.052,5.487,5.487,0,0,1,10.887,0A3.129,3.129,0,0,0,12.875,6h-1.25A.625.625,0,0,0,11,6.625v5a.625.625,0,0,0,.625.625h.625v.625a1.877,1.877,0,0,1-1.875,1.875H8A.625.625,0,0,0,8,16h2.375A3.129,3.129,0,0,0,13.5,12.875v-.688A3.13,3.13,0,0,0,16,9.125ZM3.75,7.25V11H3.125a1.875,1.875,0,0,1,0-3.75ZM12.875,11H12.25V7.25h.625a1.875,1.875,0,1,1,0,3.75Z"
                                        fill="#575b6a" />
                                    <path id="Path_40744" data-name="Path 40744"
                                        d="M197.875,113.25a.626.626,0,0,1,.625.625.618.618,0,0,1-.137.391,4.365,4.365,0,0,0-1.113,2.746v.613a.625.625,0,0,0,1.25,0v-.613a3.186,3.186,0,0,1,.838-1.964A1.875,1.875,0,1,0,196,113.875a.625.625,0,0,0,1.25,0A.626.626,0,0,1,197.875,113.25Z"
                                        transform="translate(-189.875 -108.5)" fill="#575b6a" />
                                    <circle id="Ellipse_891" data-name="Ellipse 891" cx="0.625" cy="0.625" r="0.625"
                                        transform="translate(7.375 11)" fill="#575b6a" />
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Support')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_all_support_tickets')
                            @php
                                $support_ticket = DB::table('tickets')
                                                    ->where('viewed', 0)
                                                    ->select('id')
                                                    ->count();
                            @endphp
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('support_ticket.admin_index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Ticket')}}</span>
                                    @if($support_ticket > 0)<span class="badge badge-info">{{ $support_ticket
                                        }}</span>@endif
                                </a>
                            </li>
                        @endcan

                        @can('view_all_product_conversations')
                            @php
                                $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '0')->get();
                            @endphp
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('conversations.admin_index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Product Conversations')}}</span>
                                    @if (count($conversation) > 0)
                                        <span class="badge badge-info">{{ count($conversation) }}</span>
                                    @endif
                                </a>
                            </li>
                        @endcan

                        @if (get_setting('product_query_activation') == 1)
                            @can('view_all_product_queries')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_query.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['product_query.index','product_query.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Queries') }}</span>
                                    </a>
                                </li>
                            @endcan
                        @endif
                        @can('view_all_contacts')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('contacts') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['contacts']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Contacts') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['payment_methods_configurations','african_pg_configuration','african_pg_credentials_configuration','view_all_manual_payment_methods','view_all_offline_payment_orders',
                        'view_all_offline_wallet_recharges','view_all_offline_customer_package_payments','view_all_offline_seller_package_payments','asian_payment_gateway_configuration'])
                <li class="aiz-side-nav-item">
                    
                    <a href="javascript:void(0);" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Payment_1_" transform="translate(0 -16)">
                                    <path id="Path_40758" data-name="Path 40758"
                                        d="M316.375,220h-2.5a.668.668,0,0,1,0-1.333h3.75a.668.668,0,0,0,0-1.333H315.75v-.667a.626.626,0,1,0-1.25,0v.667h-.625a2,2,0,0,0,0,4h2.5a.668.668,0,0,1,0,1.333h-3.75a.668.668,0,0,0,0,1.333H314.5v.667a.626.626,0,1,0,1.25,0V224h.625a2,2,0,0,0,0-4Z"
                                        transform="translate(-302.25 -193.333)" fill="#575b6a" />
                                    <g id="Group_28296" data-name="Group 28296" transform="translate(0 16)">
                                        <path id="Path_40759" data-name="Path 40759"
                                            d="M13.5,16H2.5A2.59,2.59,0,0,0,0,18.667v6.667A2.59,2.59,0,0,0,2.5,28H7.875a.668.668,0,0,0,0-1.333H2.5a1.3,1.3,0,0,1-1.25-1.333v-4h13.5V22A.626.626,0,1,0,16,22V18.667A2.59,2.59,0,0,0,13.5,16ZM1.25,20V18.667A1.3,1.3,0,0,1,2.5,17.333h11a1.3,1.3,0,0,1,1.25,1.333V20Z"
                                            transform="translate(0 -16)" fill="#575b6a" />
                                        <path id="Path_40760" data-name="Path 40760"
                                            d="M80.625,256a.668.668,0,0,0,0,1.333H81.75a.668.668,0,0,0,0-1.333Z"
                                            transform="translate(-77.5 -248)" fill="#575b6a" />
                                        <path id="Path_40761" data-name="Path 40761"
                                            d="M196.625,256a.668.668,0,0,0,0,1.333h1.125a.668.668,0,0,0,0-1.333Z"
                                            transform="translate(-189.875 -248)" fill="#575b6a" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Payment Gateways')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('payment_methods_configurations')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('payment_method.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Payment Methods')}}</span>
                            </a>
                        </li>
                        @endcan

                        
                        <!-- Cybersource Addon -->
                        @if (auth()->user()->can('cybersource_pg_configuration'))
                        <li class="aiz-side-nav-item">
                            <a href="{{route('cybersource_configuration')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Cybersource Payment Gateway')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="#f51350" />
                                </svg>
                                @endif
                            </a>
                        </li>
                        @endif

                       

                       
                         <!-- Paytm Addon -->
                        @if (auth()->user()->can('asian_payment_gateway_configuration'))
                        <li class="aiz-side-nav-item">
                            <a href="{{route('paytm.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Asian Payment Gateway')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="#f51350" />
                                </svg>
                                @endif
                            </a>
                        </li>
                        @endif

                         <!-- African Payment Gateway -->
                        @canany(['african_pg_configuration','african_pg_credentials_configuration'])
                         <li class="aiz-side-nav-item">
                            <a href="{{route('african_credentials.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('African Payment Gateway')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="#f51350" />
                                </svg>
                                @endif
                            </a>
                        </li>
                        @endcanany


                        <!-- Offline Payment Addon-->
                        @canany(['view_all_manual_payment_methods','view_all_offline_payment_orders',
                        'view_all_offline_wallet_recharges','view_all_offline_customer_package_payments','view_all_offline_seller_package_payments'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Offline Payment System')}}</span>
                                @if (env("DEMO_MODE") == "On")
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.001" viewBox="0 0 16 14.001"
                                    class="mx-2">
                                    <path id="Union_49" data-name="Union 49"
                                        d="M-19322,3342.5v-5a2.007,2.007,0,0,0-2-2v1.5a3,3,0,0,1-3,3h-4v-10h4a3,3,0,0,1,3,3v1.5a3,3,0,0,1,3,3v5a.506.506,0,0,1-.5.5A.5.5,0,0,1-19322,3342.5Zm-11-2V3339h-3a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v-7.5a.5.5,0,0,1,.5-.5.5.5,0,0,1,.5.5v11a.5.5,0,0,1-.5.5A.506.506,0,0,1-19333,3340.5Zm-3-7.5a1,1,0,0,1-1-1,1,1,0,0,1,1-1h3v2Z"
                                        transform="translate(19337 -3329)" fill="#f51350" />
                                </svg>
                                @endif
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                @can('view_all_manual_payment_methods')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('manual_payment_methods.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Manual Payment Methods')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_all_offline_payment_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('offline_payment_orders.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Offline Payment Orders')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('view_all_offline_wallet_recharges')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('offline_wallet_recharge_request.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Offline Wallet Recharge')}}</span>
                                    </a>
                                </li>
                                @endcan

                                @if(get_setting('classified_product') == 1 &&
                                auth()->user()->can('view_all_offline_customer_package_payments'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('offline_customer_package_payment_request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Offline Customer Package
                                            Payments')}}</span>
                                    </a>
                                </li>
                                @endif
                                @if (auth()->user()->can('view_all_offline_seller_package_payments'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('offline_seller_package_payment_request.index') }}"
                                        class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Offline Seller Package Payments')}}</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endcanany


                    </ul>
                </li>
                @endcanany

                <!-- Website Setup -->
                @canany(['header_setup', 'footer_setup', 'view_all_website_pages', 'website_appearance', 'authentication_layout_settings', 'top_banner_setting', 'view_top_banner'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link {{ areActiveRoutes(['website.footer', 'website.header'])}}">
                            <div class="aiz-side-nav-icon">
                                <svg id="Group_28315" data-name="Group 28315" xmlns="http://www.w3.org/2000/svg" width="16"
                                    height="16" viewBox="0 0 16 16">
                                    <circle id="Ellipse_893" data-name="Ellipse 893" cx="0.625" cy="0.625" r="0.625"
                                        transform="translate(7.375 6.125)" fill="#575b6a" />
                                    <path id="Path_40777" data-name="Path 40777"
                                        d="M13.5,0H2.5A2.5,2.5,0,0,0,0,2.5V11a2.5,2.5,0,0,0,2.5,2.5H7.375v1.25H5.5A.625.625,0,0,0,5.5,16h5a.625.625,0,0,0,0-1.25H8.625V12.875A.625.625,0,0,0,8,12.25H2.5A1.251,1.251,0,0,1,1.25,11V2.5A1.251,1.251,0,0,1,2.5,1.25h11A1.251,1.251,0,0,1,14.75,2.5V11a1.251,1.251,0,0,1-1.25,1.25h-3a.625.625,0,0,0,0,1.25h3A2.5,2.5,0,0,0,16,11V2.5A2.5,2.5,0,0,0,13.5,0Z"
                                        fill="#575b6a" />
                                    <path id="Path_40778" data-name="Path 40778"
                                        d="M120.375,84.75a.625.625,0,0,0,.625-.625v-.688a3.107,3.107,0,0,0,1.1-.456l.487.487a.625.625,0,0,0,.884-.884l-.487-.487a3.108,3.108,0,0,0,.456-1.1h.688a.625.625,0,1,0,0-1.25h-.688a3.108,3.108,0,0,0-.456-1.1l.487-.487a.625.625,0,0,0-.884-.884l-.487.487a3.107,3.107,0,0,0-1.1-.456v-.688a.625.625,0,0,0-1.25,0v.688a3.108,3.108,0,0,0-1.1.456l-.487-.487a.625.625,0,0,0-.884.884l.487.487a3.108,3.108,0,0,0-.456,1.1h-.688a.625.625,0,0,0,0,1.25h.688a3.108,3.108,0,0,0,.456,1.1l-.487.487a.625.625,0,0,0,.884.884l.487-.487a3.107,3.107,0,0,0,1.1.456v.688A.625.625,0,0,0,120.375,84.75ZM118.5,80.375a1.875,1.875,0,1,1,1.875,1.875A1.877,1.877,0,0,1,118.5,80.375Z"
                                        transform="translate(-112.375 -73.625)" fill="#575b6a" />
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{translate('Website Setup')}}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('select_homepage')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-homepage') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Select Homepage')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('edit_website_page')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom-pages.edit', ['id' => 'home', 'lang' => env('DEFAULT_LANGUAGE'), 'page' => 'home']) }}"
                                        class="aiz-side-nav-link {{ (url()->current() == url('/admin/website/custom-pages/edit/home')) ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text">{{translate('Homepage Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_font_family')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-font-family') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Font Family')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('authentication_layout_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.authentication-layout-settings') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Authentication Layout & Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('select_header')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.select-header') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Select Header')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('header_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.header') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Header Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['view_top_banner'])
                                <li class="aiz-side-nav-item">
                                    <a href="#" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Top Bar')}}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <!--Submenu-->
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('view_top_banner')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link {{ areActiveRoutes(['top_banner.create', 'top_banner.edit'])}}" href="{{route('top_banner.index')}}">
                                                    <span class="aiz-side-nav-text">{{translate('Top Bar List')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('top_banner_setting')
                                            <li class="aiz-side-nav-item">
                                                <a class="aiz-side-nav-link" href="{{route('top_banner.setting')}}">
                                                    <span class="aiz-side-nav-text">{{translate('Top Bar Settings')}}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @can('footer_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Footer Settings')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_website_pages')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.pages') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create', 'custom-pages.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Pages')}}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('website_appearance')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Appearance')}}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                <!-- Setup & Configurations -->
                @canany(['general_settings','features_activation','language_setup','currency_setup','vat_&_tax_setup',
                'pickup_point_setup','smtp_settings','payment_methods_configurations','order_configuration','file_system_&_cache_configuration',
                'social_media_logins','facebook_chat','facebook_comment','analytics_tools_configuration','google_recaptcha_configuration','google_map_setting',
                'google_firebase_setting','shipping_configuration','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers','select_shipping_methods'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <path id="Path_40779" data-name="Path 40779"
                                    d="M7.688,16h.625a1.877,1.877,0,0,0,1.875-1.875V13.81a.209.209,0,0,1,.133-.191l.011,0a.209.209,0,0,1,.23.041l.223.223a1.875,1.875,0,0,0,2.652,0l.442-.442a1.875,1.875,0,0,0,0-2.652l-.223-.223a.209.209,0,0,1-.041-.23l0-.012a.209.209,0,0,1,.191-.133h.315A1.877,1.877,0,0,0,16,8.313V7.688a1.877,1.877,0,0,0-1.875-1.875H13.81a.209.209,0,0,1-.191-.133l0-.011a.209.209,0,0,1,.041-.23l.223-.223a1.875,1.875,0,0,0,0-2.652l-.442-.442a1.875,1.875,0,0,0-2.652,0l-.223.223a.21.21,0,0,1-.23.041l-.012,0a.209.209,0,0,1-.133-.191V1.875A1.877,1.877,0,0,0,8.312,0H7.687A1.877,1.877,0,0,0,5.812,1.875V2.19a.209.209,0,0,1-.133.191l-.012,0a.209.209,0,0,1-.23-.041l-.223-.223a1.875,1.875,0,0,0-2.652,0l-.442.442a1.875,1.875,0,0,0,0,2.652l.223.223a.209.209,0,0,1,.041.23l0,.011a.209.209,0,0,1-.191.133H1.875A1.877,1.877,0,0,0,0,7.687v.625a1.874,1.874,0,0,0,1.407,1.816.625.625,0,1,0,.312-1.211.624.624,0,0,1-.468-.605V7.688a.626.626,0,0,1,.625-.625H2.19a1.455,1.455,0,0,0,1.347-.906l0-.011a1.455,1.455,0,0,0-.312-1.591l-.223-.223a.625.625,0,0,1,0-.884l.442-.442a.625.625,0,0,1,.884,0l.223.223a1.456,1.456,0,0,0,1.593.311l.009,0A1.455,1.455,0,0,0,7.063,2.19V1.875a.626.626,0,0,1,.625-.625h.625a.626.626,0,0,1,.625.625V2.19a1.455,1.455,0,0,0,.906,1.347l.009,0a1.455,1.455,0,0,0,1.593-.311l.223-.223a.625.625,0,0,1,.884,0l.442.442a.625.625,0,0,1,0,.884l-.223.223a1.455,1.455,0,0,0-.311,1.593l0,.009a1.455,1.455,0,0,0,1.347.906h.315a.626.626,0,0,1,.625.625v.625a.626.626,0,0,1-.625.625H13.81a1.455,1.455,0,0,0-1.347.906l0,.009a1.455,1.455,0,0,0,.311,1.593l.223.223a.625.625,0,0,1,0,.884l-.442.442a.625.625,0,0,1-.884,0l-.223-.223a1.456,1.456,0,0,0-1.593-.311l-.009,0a1.455,1.455,0,0,0-.906,1.347v.315a.626.626,0,0,1-.625.625H7.688a.622.622,0,0,1-.6-.437.625.625,0,1,0-1.193.375A1.867,1.867,0,0,0,7.688,16ZM.536,15.433a1.829,1.829,0,0,1,0-2.586h0L4.589,8.811a3.234,3.234,0,0,1-.308-1.259,2.97,2.97,0,0,1,.9-2.141A4.228,4.228,0,0,1,8.13,4.255h.007a3.322,3.322,0,0,1,1.086.188A.625.625,0,0,1,9.... (line truncated to 2000 chars)
                                    fill="#575b6a" />
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Setup & Configurations')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">

                        @can('business_settings')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('business_settings.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Business Settings')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('features_activation')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('activation.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Features activation')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('language_setup')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('languages.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['languages.index', 'languages.create', 'languages.store', 'languages.show', 'languages.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Languages')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('currency_setup')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('currency.index')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Currency')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('vat_&_tax_setup')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('tax.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['tax.index', 'tax.create', 'tax.store', 'tax.show', 'tax.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Vat & TAX')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('pickup_point_setup')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('pick_up_points.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Pickup point')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('smtp_settings')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('SMTP Settings')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('order_configuration')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('order_configuration.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Order Configuration')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('file_system_&_cache_configuration')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('File System & Cache
                                    Configuration')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('social_media_logins')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Social media Logins')}}</span>
                            </a>
                        </li>
                        @endcan
                        @canany(['facebook_chat','facebook_comment'])
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Facebook')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">

                                @can('whatsapp_chat')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('whatsapp_chat.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('WhatsApp Chat')}}</span>
                                    </a>
                                </li>
                                @endcan

                                @can('facebook_comment')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Facebook Comment')}}</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        @canany(['analytics_tools_configuration','google_recaptcha_configuration','google_map_setting','google_firebase_setting'])
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Google')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">
                                @can('analytics_tools_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google_analytics.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Analytics Tools')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('google_recaptcha_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Google reCAPTCHA')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('google_map_setting')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google-map.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Google Map')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('google_firebase_setting')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('google-firebase.index') }}" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{translate('Google Firebase')}}</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        @canany(['shipping_configuration','select_shipping_methods','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers', 'shipping_box_size_index', 'pickup_address_index'])
                        <li class="aiz-side-nav-item">
                            <a href="javascript:void(0);" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Shipping')}}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-3">


                                @can('select_shipping_methods')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('shipping_configuration.shipping_method')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.shipping_method'])}}">
                                            <span class="aiz-side-nav-text">{{translate('Select Shipping Method')}}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('shipping_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('shipping_configuration.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Configuration')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('shipping_country_setting')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('countries.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['countries.index','countries.edit','countries.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Countries')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage_shipping_states')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('states.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['states.index','states.edit','states.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping States')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage_shipping_cities')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('cities.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['cities.index','cities.edit','cities.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Cities')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage_shipping_areas')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('areas.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['areas.index','areas.edit','areas.update'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Areas')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage_zones')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('zones.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['zones.index','zones.create','zones.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Zones')}}</span>
                                    </a>
                                </li>
                                @endcan
                                @can('manage_carriers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{route('carriers.index')}}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['carriers.index','carriers.create','carriers.edit'])}}">
                                        <span class="aiz-side-nav-text">{{translate('Shipping Carrier')}}</span>
                                    </a>
                                </li>
                                @endcan
                                    @can('pickup_address_index')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('pickup_address.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['pickup_address.index','pickup_address.create','pickup_address.edit'])}}">
                                                <span class="aiz-side-nav-text">{{translate('Pickup Addresses')}}</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('shipping_box_size_index')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{route('shipping_box_size.index')}}"
                                            class="aiz-side-nav-link {{ areActiveRoutes(['shipping_box_size.index','shipping_box_size.create','shipping_box_size.edit'])}}">
                                            <span class="aiz-side-nav-text">{{translate('Shipping Box Size Configuration')}}</span>
                                        </a>
                                    </li>
                                    @endcan
                            </ul>
                        </li>
                        @endif
                    </ul>
                </li>
                @endcanany

                <!-- Staffs -->
                @canany(['view_all_staffs','view_staff_roles'])
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_28314" data-name="Group 28314" transform="translate(-19299 2175)">
                                    <path id="Path_40774" data-name="Path 40774"
                                        d="M87.867,3.07H84.133V1.72A.716.716,0,0,0,83.422,1H80.578a.716.716,0,0,0-.711.72V3.07H76.133A2.149,2.149,0,0,0,74,5.229V14.84A2.149,2.149,0,0,0,76.133,17H87.867A2.149,2.149,0,0,0,90,14.84V5.229A2.149,2.149,0,0,0,87.867,3.07Zm-6.578-.63h1.422V3.79a.711.711,0,1,1-1.422,0Zm7.289,12.4a.716.716,0,0,1-.711.72H76.133a.716.716,0,0,1-.711-.72V5.229a.716.716,0,0,1,.711-.72h3.856a2.124,2.124,0,0,0,4.022,0h3.856a.716.716,0,0,1,.711.72Z"
                                        transform="translate(19225 -2176)" fill="#575b6a" />
                                    <g id="Group_28312" data-name="Group 28312"
                                        transform="translate(19305.07 -2169.197)">
                                        <path id="Path_40775" data-name="Path 40775"
                                            d="M199.864,197.932a1.932,1.932,0,1,0-1.932,1.932A1.934,1.934,0,0,0,199.864,197.932Zm-1.932.644a.644.644,0,1,1,.644-.644A.645.645,0,0,1,197.932,198.576Z"
                                            transform="translate(-196 -196)" fill="#575b6a" />
                                    </g>
                                    <g id="Group_28313" data-name="Group 28313" transform="translate(19303.779 -2165)">
                                        <path id="Path_40776" data-name="Path 40776"
                                            d="M160.508,316h-2.576A1.934,1.934,0,0,0,156,317.932v1.288a.644.644,0,1,0,1.288,0v-1.288a.645.645,0,0,1,.644-.644h2.576a.645.645,0,0,1,.644.644v1.288a.644.644,0,1,0,1.288,0v-1.288A1.934,1.934,0,0,0,160.508,316Z"
                                            transform="translate(-156 -316)" fill="#575b6a" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('Staffs')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        @can('view_all_staffs')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('staffs.index') }}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('All staffs')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('view_staff_roles')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('roles.index')}}"
                                class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit'])}}">
                                <span class="aiz-side-nav-text">{{translate('Staff permissions')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <!-- System Update & Server Status -->
                @can('server_status')
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                <g id="Group_28317" data-name="Group 28317" transform="translate(-19315.001 1976)">
                                    <g id="layer1" transform="translate(19314.471 -1976.53)">
                                        <path id="path3159"
                                            d="M3.386.53A2.862,2.862,0,0,0,.53,3.386V13.67a2.865,2.865,0,0,0,2.856,2.86H13.67a2.869,2.869,0,0,0,2.86-2.86V3.386A2.865,2.865,0,0,0,13.67.53Zm0,1.143H13.67a1.7,1.7,0,0,1,1.718,1.713V13.67a1.7,1.7,0,0,1-1.718,1.718H3.386A1.7,1.7,0,0,1,1.673,13.67V3.386A1.7,1.7,0,0,1,3.386,1.673Z"
                                            fill="#575b6a" />
                                    </g>
                                    <g id="Group_28316" data-name="Group 28316"
                                        transform="translate(19317.551 -1973.449)">
                                        <g id="LWPOLYLINE" transform="translate(0 3.708)">
                                            <path id="Path_25666" data-name="Path 25666"
                                                d="M194.061,143.129a.436.436,0,0,0,0,.873h1.527a.436.436,0,0,0,0-.873Z"
                                                transform="translate(-193.625 -143.129)" fill="#575b6a" />
                                        </g>
                                        <g id="LWPOLYLINE-2" data-name="LWPOLYLINE" transform="translate(3.663)">
                                            <path id="Path_25667" data-name="Path 25667"
                                                d="M199.926,137.186a.436.436,0,0,1,.872,0v1.527a.436.436,0,0,1-.872,0Z"
                                                transform="translate(-199.926 -136.75)" fill="#575b6a" />
                                        </g>
                                        <g id="LWPOLYLINE-3" data-name="LWPOLYLINE" transform="translate(5.239 1.075)">
                                            <path id="Path_25668" data-name="Path 25668"
                                                d="M204.463,139.345a.436.436,0,1,0-.617-.617l-1.079,1.079a.436.436,0,1,0,.617.617Z"
                                                transform="translate(-202.638 -138.6)" fill="#575b6a" />
                                        </g>
                                        <g id="LWPOLYLINE-4" data-name="LWPOLYLINE" transform="translate(1.097 1.075)">
                                            <path id="Path_25669" data-name="Path 25669"
                                                d="M195.64,139.345a.436.436,0,1,1,.617-.617l1.079,1.079a.436.436,0,1,1-.617.617Z"
                                                transform="translate(-195.512 -138.6)" fill="#575b6a" />
                                        </g>
                                        <g id="LWPOLYLINE-5" data-name="LWPOLYLINE" transform="translate(1.097 5.261)">
                                            <path id="Path_25670" data-name="Path 25670"
                                                d="M195.64,147.008a.436.436,0,0,0,.617.617l1.079-1.079a.436.436,0,1,0-.617-.617Z"
                                                transform="translate(-195.512 -145.8)" fill="#575b6a" />
                                        </g>
                                        <path id="Path_25671" data-name="Path 25671"
                                            d="M206.87,148.144,205,146.269l.864-.471a.436.436,0,0,0-.044-.786l-5.682-2.322a.436.436,0,0,0-.569.568l2.322,5.682a.436.436,0,0,0,.786.044l.471-.864,1.875,1.875a.437.437,0,0,0,.617,0l1.233-1.233A.437.437,0,0,0,206.87,148.144Zm-1.544.913-1.977-1.977a.436.436,0,0,0-.691.1l-.311.57-1.58-3.868,3.868,1.58-.57.311a.436.436,0,0,0-.174.591.467.467,0,0,0,.074.1l1.977,1.977Z"
                                            transform="translate(-196.099 -139.223)" fill="#575b6a" />
                                    </g>
                                </g>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{translate('System')}}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">

                        @can('server_status')
                        <li class="aiz-side-nav-item">
                            <a href="{{route('system_server')}}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Server status')}}</span>
                            </a>
                        </li>
                        @endcan
                       
                        @can('sitemap_generator')
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('sitemap_generator') }}" class="aiz-side-nav-link">
                                <span class="aiz-side-nav-text">{{translate('Sitemap Generator')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan

            </ul><!-- .aiz-side-nav -->
        </div><!-- .aiz-side-nav-wrap -->
    </div><!-- .aiz-sidebar -->
    <div class="aiz-sidebar-overlay"></div>
</div><!-- .aiz-sidebar -->
