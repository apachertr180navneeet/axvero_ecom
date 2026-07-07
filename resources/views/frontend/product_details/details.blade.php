<div class="text-left px-2">
    <!-- Brand Logo & Name -->
    @if ($detailedProduct->brand != null)
        <div class="d-flex flex-wrap align-items-center mb-2">
            <span class="text-secondary fs-12 mr-2">{{ translate('Brand') }}:</span>
            <a href="{{ route('products.brand', $detailedProduct->brand->slug) }}" class="text-primary fs-12 fw-600">{{ $detailedProduct->brand->name }}</a>
        </div>
    @endif

    <!-- Product Name -->
    <h1 class="mb-2 fs-22 fw-800 text-dark" style="font-family: 'Playfair Display', serif; line-height: 1.3;">
        {{ $detailedProduct->getTranslation('name') }}
    </h1>

    <div class="d-flex align-items-center mb-3">
        @if ($detailedProduct->auction_product != 1)
            @php
                $total = $detailedProduct->reviews->where('status', 1)->count();
            @endphp
            <div class="d-flex align-items-center bg-light px-2 py-1 rounded-pill">
                <i class="las la-star text-warning fs-16 mr-1"></i>
                <span class="fs-13 fw-700 text-dark">{{ number_format($detailedProduct->rating, 1) }}</span>
                <span class="ml-1 text-muted fs-12">({{ $total }} Reviews)</span>
            </div>
        @endif
        @if ($detailedProduct->est_shipping_days)
            <div class="ml-3 fs-12 text-muted">
                <i class="las la-shipping-fast mr-1"></i>{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}
            </div>
        @endif
    </div>

    <!-- Price -->
    <div class="d-flex align-items-center gap-2 mb-4">
        <strong class="fs-24 fw-900 text-dark">{{ home_discounted_base_price($detailedProduct) }}</strong>
        @if (home_base_price($detailedProduct) != home_discounted_base_price($detailedProduct))
            <del class="text-muted fs-16 ml-2">{{ home_base_price($detailedProduct) }}</del>
        @endif
    </div>

    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form" class="product-details-page">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                <!-- Choice Options (Size, etc) -->
                @if ($detailedProduct->choice_options != null)
                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-dark fs-15 fw-700">{{ get_single_attribute_name($choice->attribute_id) }}</span>
                                <a href="#" class="text-muted fs-12 text-decoration-underline">Size Guide</a>
                            </div>
                            <div class="aiz-radio-inline d-flex flex-wrap gap-2">
                                @foreach ($choice->values as $key => $value)
                                    <label class="aiz-megabox mb-0">
                                        <input type="radio" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $value }}" @if ($key == 0) checked @endif onchange="getVariantPrice()">
                                        <span class="aiz-megabox-elem rounded-circle d-flex align-items-center justify-content-center fw-600 shadow-sm" style="width: 45px; height: 45px; font-size: 14px; transition: 0.2s;">
                                            {{ $value }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Color Options -->
                @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                    <div class="mb-4">
                        <div class="text-dark fs-15 fw-700 mb-2">{{ translate('Color') }}</div>
                        <div class="aiz-radio-inline d-flex flex-wrap gap-2">
                            @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                <label class="aiz-megabox mb-0" data-toggle="tooltip" data-title="{{ get_single_color_name($color) }}">
                                    <input type="radio" name="color" value="{{ get_single_color_name($color) }}" @if ($key == 0) checked @endif onchange="getVariantPrice()">
                                    <span class="aiz-megabox-elem rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; padding: 3px;">
                                        <span class="d-inline-block rounded-circle w-100 h-100" style="background: {{ $color }};"></span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Hidden Price Update div for AJAX script -->
                <div class="d-none" id="chosen_price_div">
                    <strong id="chosen_price"></strong>
                </div>

                <!-- Quantity -->
                <div class="mb-4 d-flex align-items-center justify-content-between bg-light p-2 rounded-pill" style="max-width: 150px;">
                    <button class="btn btn-icon btn-sm rounded-circle shadow-sm bg-white" type="button" data-type="minus" data-field="quantity" disabled="">
                        <i class="las la-minus text-dark fs-16"></i>
                    </button>
                    <input type="number" name="quantity" class="border-0 text-center flex-grow-1 bg-transparent fs-16 fw-700 text-dark input-number p-0" style="width: 40px; outline: none;" placeholder="1" value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                    <button class="btn btn-icon btn-sm rounded-circle shadow-sm bg-white" type="button" data-type="plus" data-field="quantity">
                        <i class="las la-plus text-dark fs-16"></i>
                    </button>
                </div>
                
                @php
                    $qty = 0;
                    foreach ($detailedProduct->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp
                <div class="fs-12 text-success fw-600 mb-4">
                    <i class="las la-check-circle mr-1"></i>
                    @if ($detailedProduct->stock_visibility_state == 'quantity')
                        <span id="available-quantity">{{ $qty }}</span> {{ translate('available in stock') }}
                    @else
                        {{ translate('In Stock') }}
                    @endif
                </div>

                <!-- Desktop add to cart -->
                <div class="d-none d-xl-flex flex-wrap gap-2 mb-4">
                    <button type="button" class="btn text-white fw-700 px-4 py-2 rounded-pill add-to-cart"
                        style="background-color: #502288;" onclick="addToCart()">
                        <i class="las la-shopping-cart fs-18"></i> {{ translate('Add to cart') }}
                    </button>
                    <button type="button" class="btn btn-outline-primary fw-700 px-4 py-2 rounded-pill buy-now"
                        onclick="buyNow()">
                        {{ translate('Buy Now') }}
                    </button>
                </div>

                <!-- Hidden triggers for mobile sticky bar -->
                <div class="d-none">
                    <button type="button" class="add-to-cart" onclick="addToCart()">Add to cart</button>
                    <button type="button" class="buy-now" onclick="buyNow()">Buy Now</button>
                </div>
            @endif
        </form>
    @endif
    
    <style>
        .aiz-megabox input:checked + .aiz-megabox-elem {
            border: 2px solid #502288 !important;
            background-color: #f8f9fa;
            color: #502288 !important;
        }
        .aiz-megabox-elem {
            border: 1px solid #e2e5ec;
            color: #6c757d;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</div>
