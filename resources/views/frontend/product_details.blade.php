@extends('frontend.layouts.app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }},{{ $detailedProduct->meta_keywords }}@stop

@section('meta')
    @php
        $availability = "out of stock";
        $qty = 0;
        if($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        }
        else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if($qty > 0){
            $availability = "in stock";
        }
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="https://kactto.com/uploads/all/p2jgTo0PYictPm70zRh4rs3dq8odmeo46Xu02a36.png">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="https://kactto.com/uploads/all/p2jgTo0PYictPm70zRh4rs3dq8odmeo46Xu02a36.png">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="https://kactto.com/uploads/all/p2jgTo0PYictPm70zRh4rs3dq8odmeo46Xu02a36.png" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency"
        content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@section('content')
    <style>
        .axv-product-details-wrap {
            background: #fff;
            min-height: 100vh;
            padding-bottom: 80px;
        }
        @media (max-width: 1199.98px) {
            .axv-product-details-wrap {
                max-width: 480px;
                margin: 0 auto;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }
        }
        @media (min-width: 1200px) {
            .axv-product-details-wrap {
                max-width: 100%;
                padding-bottom: 24px;
            }
        }
    </style>

    <div class="axv-product-details-wrap position-relative">
        <!-- Mobile App Header -->
        <div class="d-xl-none d-flex align-items-center justify-content-between p-3"
            style="background-color: #502288; color: white; position: sticky; top: 0; z-index: 100;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-white"><i class="las la-arrow-left fs-24"></i></a>
                <h5 class="mb-0 fw-600 fs-16">Details</h5>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('cart') }}" class="text-white position-relative">
                    <i class="las la-shopping-cart fs-24"></i>
                    @if (Session::has('cart') && count(Session::get('cart')) > 0)
                        <span class="badge badge-primary badge-circle position-absolute"
                            style="top: -5px; right: -5px; font-size: 10px; padding: 3px 5px; background: #e74c3c;">{{ count(Session::get('cart')) }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div class="container-fluid px-xl-4 px-2">
            <div class="axv-listing-offer-card mb-3 mt-xl-3 mt-2">
                <div class="axv-listing-offer-content">
                    <p class="axv-listing-offer-kicker mb-2">Shop wit us!</p>
                    <h2 class="axv-listing-offer-title mb-3">Get 40% Off for<br>all iteams</h2>
                    <a href="{{ route('flash-deals') }}" class="axv-listing-offer-cta">Shop Now <span>&#8594;</span></a>
                </div>
                <div class="axv-listing-offer-image">
                    <img src="{{ static_asset('assets/img/demo/wepik-photo-mode.png') }}" alt="Offer model">
                </div>
            </div>

            <style>
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
        </div>

        <section class="mb-4">
            <div class="container-fluid px-xl-4 px-2">
                <div class="row mt-xl-4 mt-2">
                    <!-- Product Image Gallery -->
                    <div class="col-xl-5 col-lg-6 mb-4">
                        @include('frontend.product_details.image_gallery')
                    </div>

                    <!-- Product Details -->
                    <div class="col-xl-7 col-lg-6">
                        @include('frontend.product_details.details')
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-4">
            <div class="container-fluid px-xl-4 px-2">
            @if ($detailedProduct->auction_product)
                <!-- Reviews & Ratings -->
                @include('frontend.product_details.review_section')
                
                <!-- Description, Video, Downloads -->
                @include('frontend.product_details.description')
                
                <!-- Product Query -->
                @include('frontend.product_details.product_queries')
            @else
                <div class="row gutters-16">
                    <!-- Left side -->
                  
                    <!-- Right side -->
                    <div class="col-lg-12">
                        
                        <!-- Reviews & Ratings -->
                        @include('frontend.product_details.review_section')

                        <!-- Description, Video, Downloads -->
                        @include('frontend.product_details.description')
                        
                        <!-- Frequently Bought products -->

                        <!-- Product Query -->
                        @include('frontend.product_details.product_queries')
                        
                      

                    </div>
                </div>
                
                <div class="row">
   

  <div class="col-12 mb-4">
        @include('frontend.product_details.top_selling_products')
    </div>
    
     <div class="col-12 mb-4">
        @include('frontend.product_details.frequently_bought_products')
    </div>
    
         <div class="col-12 mb-4">

    <div class="border-top" id="section_last_viewed_products" style="background-color: #fcfcfc;">
    @php
    $lastViewedProducts = getLastViewedProducts();
    @endphp
    @if (count($lastViewedProducts) > 0)
        <section class="my-2 my-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fw-700 mb-2 mb-sm-0">
                        <span class="">{{ translate('Last Viewed Products') }}</span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_last_viewed_products')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                        <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_last_viewed_products')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                    </div>
                </div>
                <!-- Product Section -->
                <div class="px-sm-3">
                    <div class="aiz-carousel slick-left sm-gutters-16 arrow-none" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true' data-infinite='false'>
                        @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                            <div class="carousel-box px-3 position-relative has-transition hov-animate-outline border-right border-top border-bottom @if($key == 0) border-left @endif">
                                @include('frontend.'.get_setting('homepage_select').'.partials.last_view_product_box_1',['product' => $lastViewedProduct->product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
</div>
    
        </div>


  
</div>
            @endif
            </div>
        </section>

        @include('frontend.smart_bar')

        <!-- Sticky Bottom Bar for Add to Cart (mobile only) -->
        <div class="d-xl-none position-fixed d-flex justify-content-between align-items-center p-3 bg-white"
            style="bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 480px; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); z-index: 99;">
            <div>
                <div class="fs-12 text-muted fw-600">Total Price</div>
                <div class="fs-20 fw-900 text-dark">{{ home_discounted_base_price($detailedProduct) }}</div>
            </div>
            <div>
                <button type="button" class="btn text-white fw-700 px-4 py-2 d-flex align-items-center gap-2 rounded-pill"
                    style="background-color: #502288; font-size: 15px;" onclick="addToCart()">
                    <i class="las la-shopping-cart fs-20"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3 rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if($detailedProduct->auction_product == 1)
        @php 
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
            $gst_rate = gst_applicable_product_rate($detailedProduct->id);
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ').$min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{translate('Place Bid Price')}}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                    @if($gst_rate != null)
                                        <small class="text-danger">{{ translate('An') }} {{ $gst_rate }}% {{ translate('GST will be applied if you win the bid and proceed with the purchase') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-sm btn-primary transition-3d-hover mr-1">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Size chart show Modal -->
    @include('modals.size_chart_show_modal')

    <!-- Product Warranty Modal -->
    <div class="modal fade" id="warranty-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Warranty Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->warranty_note_id != null)
                        <p>{{ $detailedProduct->warrantyNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Refund Modal -->
    <div class="modal fade" id="refund-note-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Refund Note') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body c-scrollbar-light">
                    @if($detailedProduct->refund_note_id != null)
                        <p>{{ $detailedProduct->refundNote->getTranslation('description') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

<script>
document.addEventListener("DOMContentLoaded", function () {

    let productId = "{{ $detailedProduct->id }}";

    let recentProducts = JSON.parse(localStorage.getItem("recent_products")) || [];

    // Remove if already exists
    recentProducts = recentProducts.filter(id => id != productId);

    // Add to beginning
    recentProducts.unshift(productId);

    // Keep max 10 items
    if (recentProducts.length > 10) {
        recentProducts.pop();
    }

    localStorage.setItem("recent_products", JSON.stringify(recentProducts));

});
</script>

    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
          	@elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers & Sellers can Bid.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id,order_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id,
                        order_id: order_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate("Sorry, You need to buy this product to give review.") }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate("Sorry, Only customers can give review.") }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function showSizeChartDetail(id, name){
            $('#size-chart-show-modal .modal-title').html('');
            $('#size-chart-show-modal .modal-body').html('');
            if (id == 0) {
                AIZ.plugins.notify('warning', '{{ translate("Sorry, There is no size guide found for this product.") }}');
                return false;
            }
            $.ajax({
                type: "GET",
                url: "{{ route('size-charts-show', '') }}/"+id,
                data: {},
                success: function(data) {
                    $('#size-chart-show-modal .modal-title').html(name);
                    $('#size-chart-show-modal .modal-body').html(data);
                    $('#size-chart-show-modal').modal('show');
                }
            });
        }

        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function updateViewerCount() {
            const countElement = document.querySelector('#live-product-viewing-visitors .count');
            const min = parseInt(`{{ get_setting('min_custom_product_visitors') }}`);
            const max = parseInt(`{{ get_setting('max_custom_product_visitors') }}`);
            const randomNumber = getRandomNumber(min, max);
            countElement.textContent = randomNumber;
            const randomTime = getRandomNumber(5000, 10000);
            setTimeout(updateViewerCount, randomTime);
        }
        
    </script>
    @if(get_setting('show_custom_product_visitors')==1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateViewerCount();
        });
    </script>
    @endif

@endsection
