@php
    $reviewTotal = $detailedProduct->reviews->where('status', 1)->count();

    // Prefer clean short meta text; fall back to readable cleaned HTML description sentence
    $shortDescription = trim(strip_tags($detailedProduct->meta_description ?? ''));
    if ($shortDescription === '') {
        $rawDesc = $detailedProduct->getTranslation('description') ?? '';
        $rawDesc = html_entity_decode($rawDesc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $rawDesc = preg_replace('/<\s*br\s*\/?\s*>/i', ' ', $rawDesc);
        $rawDesc = preg_replace('/<\/(p|div|li|h[1-6]|tr)>/i', ' ', $rawDesc);
        $rawDesc = strip_tags($rawDesc);
        $rawDesc = preg_replace('/([a-z])([A-Z])/', '$1 $2', $rawDesc); // ProductHighlights -> Product Highlights
        $rawDesc = preg_replace('/\s+/', ' ', $rawDesc);
        $shortDescription = trim($rawDesc);
    }
    if ($shortDescription === '') {
        $shortDescription =
            'Its simple and elegant shape makes it perfect for those of you who want minimalist clothes';
    }
    $shortDescription = \Illuminate\Support\Str::limit($shortDescription, 95, '');
@endphp

<div class="text-left px-2 axv-product-details">
    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form" class="product-details-page">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            {{-- MOBILE: title + rating + qty + short description --}}
            <div class="d-xl-none axv-mobile-header mb-3">
                <h1 class="mb-2 fs-18 fw-800 text-dark axv-title">
                    {{ \Illuminate\Support\Str::limit($detailedProduct->getTranslation('name'), 32) }}
                </h1>

                <div class="d-flex align-items-center mb-2">
                    <i class="las la-star axv-star mr-1"></i>
                    <span class="axv-rating-text">
                        {{ number_format($detailedProduct->rating, 1) }}
                        ({{ number_format($reviewTotal) }} {{ translate('reviews') }})
                    </span>
                </div>

                @if ($detailedProduct->digital == 0)
                    <div class="d-flex align-items-center axv-mobile-qty aiz-plus-minus mb-3">
                        <button class="btn axv-qty-btn" type="button" data-type="minus" data-field="quantity"
                            disabled="">
                            <i class="las la-minus"></i>
                        </button>
                        <input type="number" name="quantity"
                            class="axv-qty-input border-0 text-center bg-transparent fw-700 text-dark input-number p-0"
                            placeholder="1" value="{{ $detailedProduct->min_qty }}"
                            min="{{ $detailedProduct->min_qty }}" max="10" lang="en">
                        <button class="btn axv-qty-btn" type="button" data-type="plus" data-field="quantity">
                            <i class="las la-plus"></i>
                        </button>
                    </div>
                @endif

                <p class="axv-mobile-desc mb-0">
                    {{ $shortDescription }}
                    <a href="javascript:void(0)" class="fw-700 text-dark text-decoration-none axv-read-more">
                        {{ translate('Read More...') }}
                    </a>
                </p>
            </div>

            {{-- DESKTOP header --}}
            <div class="d-none d-xl-block">
                <h1 class="mb-2 fs-24 fw-700 text-dark axv-title">
                    {{ $detailedProduct->getTranslation('name') }}
                </h1>

                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center axv-rating-pill mr-3">
                        <i class="las la-star text-warning fs-16 mr-1"></i>
                        <span class="fs-13 fw-700 text-dark">{{ number_format($detailedProduct->rating, 1) }}</span>
                        <span class="ml-1 text-muted fs-12">({{ $reviewTotal }} Reviews)</span>
                    </div>
                    @if ($detailedProduct->est_shipping_days)
                        <div class="fs-12 text-muted">
                            <i class="las la-shipping-fast mr-1"></i>{{ $detailedProduct->est_shipping_days }}
                            {{ translate('Days') }}
                        </div>
                    @endif
                </div>

                <div class="axv-price-card mb-4">
                    <div class="d-flex align-items-center flex-wrap">
                        <strong
                            class="fs-28 fw-800 text-dark">{{ home_discounted_base_price($detailedProduct) }}</strong>
                        @if (home_base_price($detailedProduct) != home_discounted_base_price($detailedProduct))
                            <del class="text-muted fs-16 ml-2">{{ home_base_price($detailedProduct) }}</del>
                        @endif
                    </div>
                </div>
            </div>

            @if ($detailedProduct->digital == 0)

                <div class="row mb-3">
                    <!-- Choice Options (Size, etc) -->
                    @if ($detailedProduct->choice_options != null)
                        @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                            <div class="col-6 mb-3">
                                <div class="text-dark fs-14 fw-700 mb-2">
                                    {{ get_single_attribute_name($choice->attribute_id) }}</div>
                                <div class="aiz-radio-inline d-flex flex-wrap" style="gap: 10px;">
                                    @foreach ($choice->values as $key => $value)
                                        <label class="aiz-megabox mb-0">
                                            <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                                value="{{ $value }}"
                                                @if ($key == 0) checked @endif
                                                onchange="getVariantPrice()">
                                            <span
                                                class="aiz-megabox-elem rounded-circle d-flex align-items-center justify-content-center fw-600 shadow-sm"
                                                style="width: 35px; height: 35px; font-size: 13px; transition: 0.2s;">
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
                        <div class="col-6 mb-3">
                            <div class="text-dark fs-14 fw-700 mb-2">{{ translate('Color') }}</div>
                            <div class="aiz-radio-inline d-flex flex-wrap gap-2">
                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                    <label class="aiz-megabox mb-0" data-toggle="tooltip"
                                        data-title="{{ get_single_color_name($color) }}">
                                        <input type="radio" name="color"
                                            value="{{ get_single_color_name($color) }}"
                                            @if ($key == 0) checked @endif
                                            onchange="getVariantPrice()">
                                        <span
                                            class="aiz-megabox-elem rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 35px; height: 35px; padding: 3px;">
                                            <span class="d-inline-block rounded-circle w-100 h-100"
                                                style="background: {{ $color }};"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Color options intentionally hidden --}}


                <div class="d-none" id="chosen_price_div">
                    <strong id="chosen_price"></strong>
                </div>


                {{-- Mobile: price + Add To Cart below size (updates with selected size) --}}
                <div class="d-xl-none axv-mobile-buy-row mb-4">
                    <div class="axv-mobile-buy-left">
                        <div class="axv-mobile-qty-total d-none mb-1" id="axv_mobile_qty_total"></div>
                        <div class="d-flex align-items-center flex-wrap">
                            <span class="axv-mobile-buy-price"
                                id="axv_mobile_price">{{ home_discounted_base_price($detailedProduct) }}</span>
                            <span class="axv-mobile-buy-off @if (discount_in_percentage($detailedProduct) <= 0) d-none @endif"
                                id="axv_mobile_off">
                                {{ discount_in_percentage($detailedProduct) }}% off
                            </span>
                        </div>
                        <div class="axv-mobile-buy-mrp @if (home_base_price($detailedProduct) == home_discounted_base_price($detailedProduct)) d-none @endif"
                            id="axv_mobile_mrp_wrap">
                            MRP <del id="axv_mobile_mrp">{{ home_base_price($detailedProduct) }}</del> Incl. of taxes
                        </div>
                    </div>
                    <button type="button" class="btn axv-mobile-buy-btn add-to-cart" onclick="addToCart()">
                        {{ translate('Add To Cart') }}
                    </button>
                </div>

                {{-- Desktop qty (uses sibling buttons only; shares same quantity field via data-field) --}}
                <div class="d-none d-xl-flex mb-4 align-items-center">
                    <span class="fs-14 fw-700 text-dark mr-3">{{ translate('Qty') }}</span>
                    <div
                        class="d-flex align-items-center justify-content-between bg-light p-2 rounded-pill axv-qty aiz-plus-minus">
                        <button class="btn btn-icon btn-sm rounded-circle shadow-sm bg-white" type="button"
                            data-type="minus" data-field="quantity">
                            <i class="las la-minus text-dark fs-16"></i>
                        </button>
                        <span
                            class="axv-desktop-qty-display fs-16 fw-700 text-dark px-2">{{ $detailedProduct->min_qty }}</span>
                        <button class="btn btn-icon btn-sm rounded-circle shadow-sm bg-white" type="button"
                            data-type="plus" data-field="quantity">
                            <i class="las la-plus text-dark fs-16"></i>
                        </button>
                    </div>
                </div>


                @php
                    $qty = 0;
                    foreach ($detailedProduct->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp

                {{-- Stock indicator (desktop only) --}}
                <div class="d-none d-xl-block fs-12 text-success fw-600 mb-4">
                    <i class="las la-check-circle mr-1"></i>
                    @if ($detailedProduct->stock_visibility_state == 'quantity')
                        <span id="available-quantity">{{ $qty }}</span>
                        {{ translate('available in stock') }}
                    @else
                        {{ translate('In Stock') }}
                    @endif
                </div>

                {{-- Desktop: Add To Cart + Buy Now buttons --}}
                <div class="d-none d-xl-flex flex-wrap mb-4">
                    <button type="button" class="btn text-white fw-700 px-5 py-2 add-to-cart mr-3 axv-add-btn"
                        onclick="addToCart()">
                        {{ translate('Add To Cart') }}
                    </button>
                    <button type="button" class="btn fw-700 px-5 py-2 buy-now axv-buy-btn" onclick="buyNow()">
                        {{ translate('Buy Now') }}
                    </button>
                </div>

                {{-- Hidden triggers required by global JS --}}
                <div class="d-none">
                    <button type="button" class="add-to-cart" onclick="addToCart()">Add to cart</button>
                    <button type="button" class="buy-now" onclick="buyNow()">Buy Now</button>
                </div>
            @endif
        </form>
    @else
        <h1 class="mb-2 fs-24 fw-700 text-dark axv-title">
            {{ $detailedProduct->getTranslation('name') }}
        </h1>
    @endif

    <style>
        .axv-product-details .axv-title {
            line-height: 1.3;
            letter-spacing: 0.1px;
        }

        .axv-rating-pill {
            background: #f6f7fb;
            border: 1px solid #e9e9ef;
            border-radius: 999px;
            padding: 4px 10px;
        }

        .axv-price-card {
            border: 1px solid #ececf3;
            border-radius: 12px;
            padding: 14px 16px;
            background: #fff;
        }

        .axv-chip-wrap {
            gap: 10px;
        }

        .aiz-megabox input:checked+.aiz-megabox-elem {

            border: 2px solid #000 !important;
            background-color: #fff;
            color: #000 !important;

            border: 2px solid #222 !important;
            background-color: #222;
            color: #fff !important;

        }

        .aiz-megabox-elem {
            border: 1px solid #d7d7d7;
            color: #6c757d;
            width: 44px;
            height: 44px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fff;
        }

        .axv-qty {
            width: 170px;
        }

        .axv-add-btn {
            background-color: #000000 !important;
            border: none !important;
            border-radius: 4px !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
            letter-spacing: 0.2px;
            min-height: 48px;
        }

        .axv-add-btn:hover,
        .axv-add-btn:focus {
            background-color: #111111 !important;
            color: #ffffff !important;
        }

        .axv-buy-btn {
            border: 1px solid #ff6a00 !important;
            color: #ff6a00 !important;
            background: #fff !important;
            border-radius: 4px !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            letter-spacing: 0.2px;
            min-height: 48px;
        }

        .axv-buy-btn:hover,
        .axv-buy-btn:focus {
            color: #fff !important;
            background: #ff6a00 !important;
            border-color: #ff6a00 !important;
        }

        .axv-mobile-qty {
            gap: 6px;
        }

        .axv-qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 50% !important;
            border: 1.5px solid #c8c8c8 !important;
            background: #fff !important;
            padding: 0 !important;
            line-height: 1;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            color: #222;
            box-shadow: none !important;
        }

        .axv-qty-btn i {
            font-size: 14px;
            line-height: 1;
        }

        .axv-qty-input {
            width: 22px;
            font-size: 15px;
            outline: none;
            -moz-appearance: textfield;
        }

        .axv-star {
            color: #f5b301;
            font-size: 15px;
        }

        .axv-rating-text {
            color: #8ea0b5;
            font-size: 13px;
            font-weight: 500;
        }

        .axv-mobile-desc {
            color: #8a8a8a;
            font-size: 13px;
            line-height: 1.55;
            margin-bottom: 0;
        }

        .axv-mobile-buy-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 0;
            border-top: 1px solid #ececec;
            border-bottom: 1px solid #ececec;
        }

        .axv-mobile-buy-left {
            min-width: 0;
            flex: 1 1 auto;
        }

        .axv-mobile-buy-price {
            font-size: 20px;
            font-weight: 800;
            color: #111;
            line-height: 1.1;
        }

        .axv-mobile-qty-total {
            font-size: 13px;
            color: #666;
            line-height: 1.2;
        }

        .axv-mobile-buy-off {
            font-size: 13px;
            font-weight: 700;
            color: #1aae4c;
            margin-left: 8px;
        }

        .axv-mobile-buy-mrp {
            font-size: 12px;
            color: #666;
            margin-top: 4px;
            line-height: 1.2;
        }

        .axv-mobile-buy-mrp del {
            color: #666;
            text-decoration: line-through;
        }

        .axv-mobile-buy-btn {
            background: #000 !important;
            color: #fff !important;
            border: none !important;
            border-radius: 4px !important;
            min-height: 44px;
            padding: 10px 18px;
            font-size: 14px;
            font-weight: 700;
            white-space: nowrap;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.18);
            flex-shrink: 0;
        }

        .axv-read-more {
            white-space: nowrap;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media (min-width: 1200px) {
            .aiz-megabox input:checked+.aiz-megabox-elem {
                border: 2px solid #502288 !important;
                background-color: #f5f0ff;
                color: #502288 !important;
            }
        }
    </style>


    <script>
        (function() {
            const qtyInput = document.querySelector('#option-choice-form input[name="quantity"]');
            const qtyDisplay = document.querySelector('.axv-desktop-qty-display');
            if (qtyInput && qtyDisplay) {
                qtyInput.addEventListener('change', function() {
                    qtyDisplay.textContent = this.value;
                });
            }

            document.querySelectorAll('.axv-read-more').forEach(function(el) {
                el.addEventListener('click', function() {
                    const trigger = document.querySelector('[data-target="#descCollapse"]');
                    if (trigger) {
                        trigger.click();
                        document.getElementById('descCollapse')?.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        })();
    </script>

</div>
