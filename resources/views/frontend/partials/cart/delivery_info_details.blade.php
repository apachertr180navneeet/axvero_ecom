<style>
    /* Product Card Styling */
.checkout-product-card {
    background: #fff;
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: all 0.2s ease;
}

.checkout-product-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* Delivery Box Styling */
.delivery-box {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
}

/* Radio Premium Style */
.delivery-option {
    border: 1px solid #eee;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.delivery-option:hover {
    border-color: #ff6a00;
    box-shadow: 0 3px 12px rgba(0,0,0,0.06);
}

/* Fix Mobile Breaking */
@media (max-width: 768px) {
    .delivery-option span {
        font-size: 14px;
    }

    .delivery-box {
        padding: 15px;
    }
}
</style>

<div class="row gutters-16 shdow">
    @php
    $physical = false;
    $col_val = 'col-12';
    foreach ($products as $key => $cartItem){
    $product = get_single_product($cartItem);
    if ($product->digital == 0) {
    $physical = true;
    $col_val = 'col-md-6';
    }
    }
    @endphp
    <!-- Product List -->
    <div class="{{ $col_val }}">
        <ul class="list-group list-group-flush mb-3">
            @foreach ($products as $key => $cartItem)
            @php
            $product = get_single_product($cartItem);
            @endphp
         <li class="list-group-item border-0 px-0 py-2">

    <a href="{{ route('product', $product->slug) }}"
       class="text-decoration-none text-dark">

        <div class="checkout-product-card d-flex align-items-center">

            <div class="mr-3">
                <img src="{{ get_image($product->thumbnail) }}"
                    class="img-fit rounded"
                    style="width:70px; height:70px;"
                    alt="{{  $product->getTranslation('name')  }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </div>

            <div class="flex-grow-1">
                <div class="fw-600 text-dark mb-1">
                    {{ $product->getTranslation('name') }}
                </div>

                @if ($product_variation[$key] != '')
                <div class="text-muted small">
                    {{ translate('Variation') }}: {{ $product_variation[$key] }}
                </div>
                @endif
            </div>

        </div>

    </a>

</li>
            @endforeach
        </ul>
    </div>

    @if ($physical)
    <!-- Choose Delivery Type -->
   <div class="col-md-6">
            <div class="delivery-box">
        <h6 class="fs-14 fw-700 mt-3">{{ translate('Choose Delivery Type') }}</h6>
        <div class="row gutters-16">
            <!-- Home Delivery -->
            @if (get_setting('shipping_type') != 'carrier_wise_shipping')
            <div class="col-12">
               <label class="aiz-megabox d-block mb-3 delivery-option">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="home_delivery"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        checked required>
                    <span class="d-flex align-items-center rounded-0 px-3 py-3 w-100">
                     <span class="aiz-rounded-check flex-shrink-0"></span>
                <span class="fw-600 ms-2 text-wrap">
                    {{ translate('Home Delivery') }}
                </span>
            
            </span>
             </label>
            </div>
            <!-- Carrier -->
            @else
            <div class="col-6">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="carrier"
                        class="shipping-type-radio"
                        data-owner="{{ $owner_id }}"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        checked required>
                    <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Carrier') }}</span>
                    </span>
                </label>
            </div>
            @endif
            <!-- Local Pickup -->
            @if ($pickup_point_list)
            <div class="col-6">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="pickup_point"
                        class="shipping-type-radio"
                        data-owner="{{ $owner_id }}"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        required>
                    <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                    </span>
                </label>
            </div>
            @endif
        </div>

        <!-- Pickup Point List -->
        @if ($pickup_point_list)
        <div class="mt-3 pickup_point_id_{{ $owner_id }} d-none">
            <select
                class="form-control aiz-selectpicker rounded-0"
                name="pickup_point_id_{{ $owner_id }}"
                data-live-search="true"
                onchange="updateDeliveryInfo('pickup_point', this.value, {{ $owner_id }})">
                <option value="">{{ translate('Select your nearest pickup point')}}</option>
                @foreach ($pickup_point_list as $pick_up_point)
                <option
                    value="{{ $pick_up_point->id }}"
                    data-content="<span class='d-block'>
                                                <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                            </span>">
                </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Carrier Wise Shipping -->
        @if (get_setting('shipping_type') == 'carrier_wise_shipping')

        <div class="row pt-3 carrier_id_{{ $owner_id }}">
            @if($carrier_list->isEmpty())

            <div class="col-md-12">
                <div class="alert alert-danger col-md-12 mb-2">
                    <strong>{{ translate('Shipping is not available to your selected address.') }}</strong><br>
                    {{ translate('Please choose a different address.') }}
                </div>
                <span class="shipping-unavailable-flag" style="display: none;"></span>
            </div>


            @else
            @foreach($carrier_list as $carrier_key => $carrier)
            <div class="col-md-12 mb-2">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="carrier_id_{{ $owner_id }}"
                        value="{{ $carrier->id }}"
                        @if($carrier_key==0) checked @endif
                        onchange="updateDeliveryInfo('carrier', {{ $carrier->id }}, {{ $owner_id }})">
                    <span class="d-flex flex-wrap p-3 aiz-megabox-elem rounded-0">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">
                            <img src="{{ uploaded_asset($carrier->logo)}}" alt="Image" class="w-50px img-fit">
                        </span>
                        <span class="flex-grow-1 pl-3 fw-700">{{ $carrier->name }}</span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in').' '.$carrier->transit_time }}</span>
                        <span class="flex-grow-1 pl-4 pl-sm-3 fw-600 mt-2 mt-sm-0 text-sm-right">{{ single_price(carrier_base_price($carts, $carrier->id, $owner_id, $shipping_info)) }}</span>
                    </span>
                </label>
            </div>
            @endforeach
            @endif
        </div>

        @endif
    </div>
    </div>
    @endif
</div>