@extends('frontend.layouts.app')

@section('content')
    <div class="container-fluid p-0 mx-auto bg-white position-relative" style="max-width: 480px; min-height: 100vh; box-shadow: 0 0 20px rgba(0,0,0,0.05); padding-bottom: 80px !important;">
        <!-- Mobile App Header -->
        <div class="d-flex align-items-center justify-content-between p-3" style="background-color: #502288; color: white; position: sticky; top: 0; z-index: 100;">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 fw-600 fs-16">Order Confirmed</h5>
            </div>
            <div>
                <a href="{{ route('home') }}" class="text-white"><i class="las la-home fs-24"></i></a>
            </div>
        </div>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first()
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                              <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                              <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                              </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  translate('A copy or your order summary has been sent to') }} <strong>{{ json_decode($first_order->shipping_address)->email }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 text-soft-dark pb-2 border-bottom">{{ translate('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14 text-soft-dark">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Order date')}}:</td>
                                        <td class="border-top-0 py-2">{{ date('d-m-Y H:i A', $first_order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Name')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Email')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->email }}</td>
                                    </tr>
                                    <tr>
                                        @php
                                            $shipping = json_decode($first_order->shipping_address);
                                            $billing = json_decode($first_order->billing_address);
                                        @endphp
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Shipping address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $shipping->address }}, 
                                            {{ $shipping?->city ? $shipping->city . ', ' : '' }}
                                            {{ $shipping?->state ? $shipping->state . ', ' : '' }}
                                            {{ $shipping->country }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Billing address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $billing->address }}, 
                                            {{ $billing?->city ? $billing->city . ', ' : '' }}
                                            {{ $billing?->state ? $billing->state . ', ' : '' }}
                                            {{ $billing->country }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Order status')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Total order amount')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ single_price($combined_order->grand_total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Shipping')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ translate('Payment method')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">{{ translate('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                    <h5 class="h5 fs-14">{{ translate('Delivery Type:')}} 
                                        <span class="fw-700">
                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                {{  translate('Home Delivery') }}
                                            @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                {{  translate('Carrier') }}
                                            @elseif ($order->shipping_type == 'pickup_point')
                                                @if ($order->pickup_point != null)
                                                    {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickip Point') }})
                                                @endif
                                            @endif
                                        </span>
                                    </h5>
                                    @if(get_seller_gstin($order) != null)
                                    <h5 class="h5 fs-14">{{ translate('GSTIN:')}} 
                                        <span class="fw-700">
                                            {{get_seller_gstin($order)}}
                                        </span>
                                    </h5>
                                    @endif
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                    <!-- Product Details -->
                                    <div>
                                        <table class="table table-responsive-md text-soft-dark fs-14">
                                            <thead>
                                                <tr>
                                                    <th class="opacity-60 border-top-0 pl-0">#</th>
                                                    <th class="opacity-60 border-top-0" width="30%">{{ translate('Product')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Qty')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Gross Amount')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Discount/ Coupon')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('Taxable Value')}}</th>
                                                    @if(same_state_shipping($order))
                                                    <th class="opacity-60 border-top-0">{{ translate('CGST')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ translate('SGST')}}</th>
                                                    @else
                                                    <th class="opacity-60 border-top-0">{{ translate('IGST')}}</th>
                                                    @endif

                                                    <th class="text-right opacity-60 border-top-0 pr-0">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                    @php
                                                                        if($orderDetail->combo_id != null) {
                                                                            $combo = \App\ComboProduct::findOrFail($orderDetail->combo_id);

                                                                            echo '('.$combo->combo_title.')';
                                                                        }
                                                                    @endphp
                                                                </a>
                                                                <p class="fs-12">{{ $orderDetail->variation }}</p>
                                                            @else
                                                                <strong>{{  translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->price) }}
                                                        </td>

                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->coupon_discount) }}
                                                        </td>

                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->price - $orderDetail->coupon_discount) }}
                                                        </td>

                                                        @php 
                                                            $gst_amount = get_gst_by_price_and_rate($orderDetail->price - $orderDetail->coupon_discount , $orderDetail->gst_rate);
                                                            $shipping_gst = get_gst_by_price_and_rate($orderDetail->shipping_cost, $orderDetail->gst_rate);
                                                            @endphp
                                                        @if(same_state_shipping($order))
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount/2) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount/2) }}
                                                        </td>
                                                        @else
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($gst_amount) }}
                                                        </td>
                                                        @endif
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price - $orderDetail->coupon_discount + $gst_amount) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0"></td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{translate('Shipping')}}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            1
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->shipping_cost) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price(0) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($orderDetail->shipping_cost) }}
                                                        </td>
                                                        @if(same_state_shipping($order))
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst/2) }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst/2) }}
                                                        </td>
                                                        @else
                                                        <td class="border-top-0 border-bottom">
                                                            {{ single_price($shipping_gst) }}
                                                        </td>
                                                        @endif
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->shipping_cost + (($orderDetail->shipping_cost* $orderDetail->gst_rate)/100)) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                     <tr>
                                                        <th class="border-top-0 py-2">{{ translate('Subtotal')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('shipping_cost') - $order->orderDetails->sum('coupon_discount')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ translate('GST Amount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('gst_amount')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Total -->
                                                    <tr>
                                                        <th class="py-2"><span class="fw-600">{{ translate('Total')}}</span></th>
                                                        <td class="text-right pr-0">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    
    <!-- Sticky Bottom Bar -->
    <div class="position-fixed d-flex justify-content-center align-items-center p-3 bg-white" style="bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-width: 480px; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); z-index: 99; border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <a href="{{ route('home') }}" class="btn text-white fw-700 px-4 py-2 rounded-pill shadow-sm w-100 text-center" style="background-color: #502288; font-size: 15px;">
            Continue Shopping
        </a>
    </div>
</div>
@endsection

@section('script')
    @if (get_setting('facebook_pixel') == 1)
    <!-- Facebook Pixel purchase Event -->
    <script>
        $(document).ready(function(){
            var currend_code = '{{ get_system_currency()->code }}';
            var amount = 'single_price($combined_order->grand_total) }}';
            fbq('track', 'Purchase',
                {
                    value: amount,
                    currency: currend_code,
                    content_type: 'product'
                }
            );
        });
    </script>
    <!-- Facebook Pixel purchase Event -->
    @endif
@endsection
        
