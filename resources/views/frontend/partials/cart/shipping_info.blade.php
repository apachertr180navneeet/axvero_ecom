@php
    $address = $address ?? null;
@endphp
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $address = $address ?? null;
@endphp
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (Auth::check())
    <div class="mt-2">
        @foreach (Auth::user()->addresses as $key => $addr)
            @php
                $city = optional($addr->city);
                $area_id = $addr->area_id;
                $has_area_id = !is_null($area_id);
                $city_status = $city->status;
                $active_area_exists = $city->areas()->where('status', 1)->exists(); 
                $area_status = $has_area_id ? optional($addr->area)->status : 1;
                $is_disabled =
                    $city_status === 0 ||
                    ($has_area_id && $area_status === 0) ||
                    ($active_area_exists && !$has_area_id) ||
                    ($addr->state_id == null && get_setting('has_state') == 1);
                    
                $addr_title = "Address " . ($key + 1);
                // Simple heuristic to name Home/Office based on string matching (if no title field exists in DB)
                if (stripos($addr->address, 'flat') !== false || stripos($addr->address, 'home') !== false || stripos($addr->address, 'apt') !== false) {
                    $addr_title = "Home";
                } elseif (stripos($addr->address, 'office') !== false || stripos($addr->address, 'tower') !== false) {
                    $addr_title = "Office";
                }
            @endphp
            
            <div class="card border-0 mb-3" style="border: 1px solid #e0e0e0 !important; border-radius: 8px; {{ $is_disabled ? 'opacity: 0.6;' : '' }}">
                <label class="d-flex flex-column mb-0 p-3" style="cursor: pointer;">
                    <div class="d-flex align-items-center mb-2">
                        <input type="radio" name="single_address_id" value="{{ $addr->id }}" {{ $addr->id == $address_id && !$is_disabled ? 'checked' : '' }} {{ $is_disabled ? 'disabled' : '' }} class="mr-2" style="width: 18px; height: 18px; accent-color: #000;">
                        <span class="fw-700 text-dark fs-15">{{ $addr_title }}</span>
                    </div>
                    
                    <div class="pl-4 ml-1 text-muted fs-13 mb-3" style="line-height: 1.5;">
                        {{ $addr->address }}<br>
                        {{ $addr->area ? $addr->area->name . ',' : '' }} {{ $addr->city->name }}, {{ $addr->state && $addr->state->status == 1 ? $addr->state->name . ',' : '' }} {{ optional($addr->country)->name }}<br>
                        {{ $addr->postal_code ? 'P.O. Box ' . $addr->postal_code : '' }}
                    </div>
                    
                    <div class="pl-4 ml-1 mb-3 text-dark fs-13 fw-600">
                        Phone: {{ $addr->phone }}
                    </div>
                    
                    <div class="pl-4 ml-1 d-flex gap-2 w-100">
                        <button type="button" class="btn text-dark fw-600 rounded-pill px-0 flex-grow-1 mr-2" style="background-color: #f0f0f0; font-size: 13px;" onclick="edit_address('{{ $addr->id }}')">
                            Edit
                        </button>
                        <button type="button" class="btn text-dark fw-600 rounded-pill px-0 flex-grow-1" style="background-color: #f0f0f0; font-size: 13px;">
                            Delete
                        </button>
                    </div>
                </label>
            </div>
        @endforeach
        
        <div class="d-flex align-items-center mt-4" style="cursor: pointer;" onclick="add_new_address()">
            <i class="las la-plus fw-800 text-dark fs-18 mr-2"></i>
            <span class="fw-700 text-dark fs-15">Add New Address</span>
        </div>
        
        <input type="hidden" name="checkout_type" value="logged">
    </div>

    <!--Modal Start -->
    <div class="modal fade" id="choose-address-modal" tabindex="-1" aria-labelledby="chooseAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseAddressModalLabel">Choose Address</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Single Start -->
                    <div>
                        @foreach (Auth::user()->addresses as $key => $address)
                        @php
                            $city = optional($address->city);
                            $area_id = $address->area_id;

                            $has_area_id = !is_null($area_id);
                            $city_status = $city->status;
                            $active_area_exists = $city->areas()->where('status', 1)->exists(); // new line
                            $area_status = $has_area_id ? optional($address->area)->status : 1;
                            $is_disabled =
                                $city_status === 0 ||
                                ($has_area_id && $area_status === 0) ||
                                ($active_area_exists && !$has_area_id) ||
                                ($address->state_id == null && get_setting('has_state') == 1);
                        @endphp
                        <div class="border {{ $is_disabled ? 'border-danger' : '' }} mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="aiz-megabox d-block bg-white mb-0">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" {{ $address->id == $address_id && !$is_disabled ? 'checked' : '' }}
                                             {{ $is_disabled ? 'disabled' : '' }} required>
                                        <span class="d-flex p-3 aiz-megabox-elem border-0">
                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                            <span class="pl-3 text-left w-xl-300px address-text">
                                                {{ $address->address }}, {{ $address->area ? $address->area->name . ',' : '' }} {{ $address->postal_code }}-{{ $address->city->name }},{{ $address->state && $address->state->status == 1 ? $address->state->name . ',' : '' }} {{ optional($address->country)->name }}
                                              <br>  {{ $address->phone }}
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <!-- Always show Change button -->
                                <div class="col-md-4 p-3 text-right">
                                    <a class="btn btn-sm btn-secondary-base text-white mr-4 rounded-pill px-4"
                                        onclick="edit_address('{{ $address->id }}')">
                                        {{ translate('Change') }}
                                    </a>
                                </div>
                                @if($is_disabled)
                                <div class="col-md-12">
                                    <div class="text-center text-danger">
                                        <span>{{ translate('We no longer deliver in this area.') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <input type="hidden" name="checkout_type" value="logged">

                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            
                            <!-- Add New Address Button -->
                            <div class="py-1">
                                <div class="border c-pointer text-center py-2 px-3 bg-soft-blue has-transition d-flex justify-content-center rounded-pill"
                                    onclick="add_new_address()">
                                    <i class="las la-plus fs-20 fw-bold text-blue"></i>
                                    <div class="alpha-7 fs-14 text-blue fw-700 ml-2">{{ translate('Add New Address') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single End -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded-0" onclick="changeShippingAddress()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="choose-billing-address-modal" tabindex="-1" aria-labelledby="chooseAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chooseAddressModalLabel">{{ translate('Choose Billing Address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Single Start -->
                    <div>
                        @foreach (Auth::user()->addresses as $key => $address)
                        @php
                            $city = optional($address->city);
                            $area_id = $address->area_id;

                            $has_area_id = !is_null($area_id);
                            $city_status = $city->status;
                            $active_area_exists = $city->areas()->where('status', 1)->exists(); // new line
                            $area_status = $has_area_id ? optional($address->area)->status : 1;
                            $is_disabled =
                                $city_status === 0 ||
                                ($has_area_id && $area_status === 0) ||
                                ($active_area_exists && !$has_area_id) ||
                                ($address->state_id == null && get_setting('has_state') == 1);
                        @endphp
                        <div class="border {{ $is_disabled ? 'border-danger' : '' }} mb-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="aiz-megabox d-block bg-white mb-0">
                                        <input type="radio" name="billing_address_id" data-type="billing" value="{{ $address->id }}" {{ $address->set_billing == 1 ? 'checked' : '' }}
                                                {{ $is_disabled ? 'disabled' : '' }} required>
                                        <span class="d-flex p-3 aiz-megabox-elem border-0">
                                            <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                                            <span class="pl-3 text-left w-xl-300px address-text">
                                                {{ $address->address }}, {{ $address->area ? $address->area->name . ',' : '' }} {{ $address->postal_code }}-{{ $address->city->name }},{{ $address->state && $address->state->status == 1 ? $address->state->name . ',' : '' }} {{ optional($address->country)->name }}
                                                <br>  {{ $address->phone }}
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <!-- Always show Change button -->
                                <div class="col-md-4 p-3 text-right">
                                    <a class="btn btn-sm btn-secondary-base text-white mr-4 rounded-pill px-4"
                                        onclick="edit_billing_address('{{ $address->id }}')">
                                        {{ translate('Change') }}
                                    </a>
                                </div>
                                @if($is_disabled)
                                <div class="col-md-12">
                                    <div class="text-center text-danger">
                                        <span>{{ translate('Address is not Valid') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <input type="hidden" name="checkout_type" value="logged">

                        <div class="d-flex flex-wrap align-items-center justify-content-between">
                            
                            <!-- Add New Address Button -->
                            <div class="py-1">
                                <div class="border c-pointer text-center py-2 px-3 bg-soft-blue has-transition d-flex justify-content-center rounded-pill"
                                    onclick="add_new_address()">
                                    <i class="las la-plus fs-20 fw-bold text-blue"></i>
                                    <div class="alpha-7 fs-14 text-blue fw-700 ml-2">{{ translate('Add New Address') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Single End -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary rounded-0 close" data-dismiss="modal" aria-label="Close">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    
    
    <!--Modal End -->
@else
    <!-- Guest Shipping a address -->
    @include('frontend.partials.cart.guest_shipping_info')
@endif
