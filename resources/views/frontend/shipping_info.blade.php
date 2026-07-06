@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid p-0 mx-auto bg-white position-relative" style="max-width: 480px; min-height: 100vh; box-shadow: 0 0 20px rgba(0,0,0,0.05); padding-bottom: 80px !important;">
        <!-- Mobile App Header -->
        <div class="d-flex align-items-center justify-content-between p-3" style="background-color: #502288; color: white; position: sticky; top: 0; z-index: 100;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-white"><i class="las la-arrow-left fs-24"></i></a>
                <h5 class="mb-0 fw-600 fs-16">Shipping Info</h5>
            </div>
        </div>

        <section class="p-3">
            <form class="form-default" id="shipping_info_form" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                @csrf
                
                @if(Auth::check())
                    <h6 class="fw-700 text-dark mb-3">Select Address</h6>
                    @foreach (Auth::user()->addresses as $key => $address)
                        <label class="aiz-megabox d-block bg-white mb-3 shadow-sm" style="border-radius: 12px; border: 1px solid #eee;">
                            <input type="radio" name="address_id" value="{{ $address->id }}" @if ($address->set_default) checked @endif required>
                            <span class="d-flex p-3 aiz-megabox-elem border-0 align-items-start" style="border-radius: 12px;">
                                <!-- Checkbox -->
                                <span class="aiz-rounded-check flex-shrink-0 mt-1 mr-3"></span>
                                <!-- Address Details -->
                                <span class="flex-grow-1 text-left">
                                    <div class="fw-600 text-dark mb-1">{{ translate('Address') }}</div>
                                    <div class="fs-13 text-muted mb-2 line-height-1-4">
                                        {{ $address->address }}, {{ optional($address->city)->name }}, <br>
                                        {{ optional($address->state)->name }}, {{ optional($address->country)->name }} - {{ $address->postal_code }}
                                    </div>
                                    <div class="fs-13 text-dark fw-600"><i class="las la-phone"></i> {{ $address->phone }}</div>
                                </span>
                                <!-- Edit Button -->
                                <a href="javascript:void(0)" onclick="edit_address('{{$address->id}}')" class="btn btn-icon btn-sm bg-light rounded-circle shadow-sm position-absolute" style="top: 10px; right: 10px;">
                                    <i class="las la-edit text-dark"></i>
                                </a>
                            </span>
                        </label>
                    @endforeach

                    <input type="hidden" name="checkout_type" value="logged">
                    
                    <div class="text-center mt-4">
                        <a href="javascript:void(0)" onclick="add_new_address()" class="btn rounded-pill px-4 fw-600 shadow-sm" style="border: 2px dashed #502288; color: #502288;">
                            <i class="las la-plus"></i> Add New Address
                        </a>
                    </div>
                @else
                    @include('frontend.partials.cart.guest_shipping_info')
                @endif
                
                <!-- Sticky Bottom Bar -->
                <div class="position-fixed d-flex justify-content-between align-items-center p-3 bg-white" style="bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 480px; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); z-index: 99; border-top-left-radius: 20px; border-top-right-radius: 20px;">
                    <a href="{{ route('cart') }}" class="text-muted fw-600 fs-14">
                        <i class="las la-arrow-left"></i> Cart
                    </a>
                    <button @if(Auth::check()) type="submit" @else type="button" onclick="submitShippingInfoForm(this)" @endif class="btn text-white fw-700 px-4 py-2 rounded-pill shadow-sm" style="background-color: #502288; font-size: 15px;">
                        Continue <i class="las la-arrow-right fs-18"></i>
                    </button>
                </div>
            </form>
        </section>
        
        <style>
            .aiz-megabox input:checked + .aiz-megabox-elem {
                border-color: #502288 !important;
            }
            .aiz-megabox input:checked + .aiz-megabox-elem .aiz-rounded-check:after {
                background: #502288 !important;
            }
        </style>
    </div>
@endsection

@section('modal')
    <!-- Address Modal -->
    @if(Auth::check())
        @include('frontend.partials.address.address_modal')
    @endif
@endsection

@section('script')
    @include('frontend.partials.address.address_js')
@endsection
