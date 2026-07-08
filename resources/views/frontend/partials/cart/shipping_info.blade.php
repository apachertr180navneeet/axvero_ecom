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
        <ul class="list-group list-group-flush mb-2 px-lg-0 px-2">
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

                $addr_title = 'Address ' . ($key + 1);
                if (stripos($addr->address, 'flat') !== false || stripos($addr->address, 'home') !== false || stripos($addr->address, 'apt') !== false) {
                    $addr_title = 'Home';
                } elseif (stripos($addr->address, 'office') !== false || stripos($addr->address, 'tower') !== false) {
                    $addr_title = 'Office';
                }
            @endphp

            <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                <div class="bg-white p-3 position-relative"
                    style="border: 1px solid #f0f0f0; border-radius: 12px; {{ $is_disabled ? 'opacity: 0.6;' : '' }}">
                    <label class="d-block mb-0" style="cursor: pointer;">
                        <div class="d-flex align-items-start pr-5">
                            <input type="radio" name="single_address_id" value="{{ $addr->id }}"
                                {{ $addr->id == $address_id && !$is_disabled ? 'checked' : '' }}
                                {{ $is_disabled ? 'disabled' : '' }} class="mr-3 mt-1"
                                style="width: 18px; height: 18px; accent-color: #000; flex-shrink: 0;">
                            <div class="flex-grow-1" style="min-width: 0;">
                                <h6 class="fs-15 fw-700 text-dark mb-2">{{ $addr_title }}</h6>
                                <div class="fs-13 text-muted mb-2" style="line-height: 1.5;">
                                    {{ $addr->address }}<br>
                                    {{ $addr->area ? $addr->area->name . ',' : '' }} {{ $addr->city->name }},
                                    {{ $addr->state && $addr->state->status == 1 ? $addr->state->name . ',' : '' }}
                                    {{ optional($addr->country)->name }}<br>
                                    {{ $addr->postal_code ? 'P.O. Box ' . $addr->postal_code : '' }}
                                </div>
                                <div class="fs-13 text-dark fw-600 mb-0">Phone: {{ $addr->phone }}</div>
                            </div>
                        </div>
                    </label>

                    <div class="position-absolute d-flex align-items-center"
                        style="top: 15px; right: 15px; gap: 12px; z-index: 2;">
                        <button type="button" class="btn btn-link p-0 text-muted shadow-none"
                            onclick="event.preventDefault(); edit_address('{{ $addr->id }}')">
                            <i class="las la-edit fs-18"></i>
                        </button>
                        <button type="button" class="btn btn-link p-0 text-muted shadow-none">
                            <i class="las la-trash-alt fs-18"></i>
                        </button>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>

        <div class="d-flex align-items-center px-2 mt-1" style="cursor: pointer;" onclick="add_new_address()">
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
