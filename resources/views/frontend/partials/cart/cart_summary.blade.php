<div class="z-3 sticky-top-lg">
<div class="card border-0 shadow-lg rounded-4 overflow-hidden">


           @php
            $subtotal_for_min_order_amount = 0;
            $subtotal = 0;
            $tax = 0;
            $gst = 0;
            $product_shipping_cost = 0;
            $shipping = 0;
            $coupon_code = null;
            $coupon_discount = 0;
            $total_point = 0;
            $has_bulk_product = false;
        
            $agent_discount_total = 0;
            $isAgentMember = false;
            if (Auth::check()) {
                $isAgentMember = \App\Models\AgentJoin::where('user_id', auth()->id())
                                    ->where('payment_status', 'success')
                                    ->exists();
            }
        @endphp
        
           @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                if ($cartItem->quantity > 1) {
                    $has_bulk_product = true;
                }
                $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                if (addon_is_activated('gst_system')) {
                    $gst += cart_product_gst($cartItem, $product, false);
                }
                $product_shipping_cost = $cartItem['shipping_cost'];
                $shipping += $product_shipping_cost;
                if ((get_setting('coupon_system') == 1) && ($cartItem->coupon_applied == 1)) {
                    $coupon_code = $cartItem->coupon_code;
                    $coupon_discount = $carts->sum('discount');
                }
                if (addon_is_activated('club_point')) {
                    $total_point += $product->earn_point * $cartItem['quantity'];
                }
        
                // Agent discount
                if ($isAgentMember && $product->agent_discount > 0) {
                    $product_price = cart_product_price($cartItem, $product, false, false);
                    $agent_discount_total += ($product_price * $product->agent_discount / 100) * $cartItem['quantity'];
                }
            @endphp
        @endforeach

      <div class="card-header bg-white pt-4 pb-3 border-0">
           <h3 class="fs-18 fw-bold mb-1 text-dark">{{ translate('Order Summary') }}</h3>
            <div class="text-right">
                <!-- Minimum Order Amount -->
                @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                    <span class="badge badge-inline badge-warning fs-12 rounded-0 px-2">
                        {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body pt-2">

            <div class="row gutters-5">
                <!-- Total Products -->
                <div class="@if (addon_is_activated('club_point')) col-6 @else col-12 @endif">
                   <div class="d-flex align-items-center justify-content-between bg-primary bg-gradient rounded-3 px-3 py-2 shadow-sm">
                        <span class="fs-13 text-white">{{ translate('Total Products') }}</span>
                        <span class="fs-13 fw-700 text-white">{{ sprintf("%02d", count($carts)) }}</span>
                    </div>
                </div>
                @if (addon_is_activated('club_point'))
                    <!-- Total Clubpoint -->
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-between bg-secondary bg-gradient rounded-3 px-3 py-2 shadow-sm">
                            <span class="fs-13 text-white">{{ translate('Total Clubpoint') }}</span>
                            <span class="fs-13 fw-700 text-white">{{ sprintf("%02d", $total_point) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" id="sub_total" value="{{ $subtotal }}">

            <table class="table table-borderless my-4">
                <tfoot>
                    <!-- Subtotal -->
                    <tr class="cart-subtotal">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Subtotal') }} ({{ sprintf("%02d", count($carts)) }} {{ translate('Products') }})</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($subtotal) }}</td>
                    </tr>
                    
                    <!-- Tax -->
                     @if(!addon_is_activated('gst_system'))
                    <tr class="cart-tax">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Tax') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($tax) }}</td>
                    </tr>
                    @endif
                    @if ($proceed != 1)
                    <!-- Total Shipping -->
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Total Shipping') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($shipping) }}</td>
                    </tr>
                    @endif
                    <!-- Redeem point -->
                    @if (Session::has('club_point'))
                        <tr class="cart-club-point">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Redeem point') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price(Session::get('club_point')) }}</td>
                        </tr>
                    @endif
                    <!-- Coupon Discount -->
                    @if ($coupon_discount > 0)
                        <tr class="cart-coupon-discount">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Coupon Discount') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($coupon_discount) }}</td>
                        </tr>
                    @endif
                    
                    @if ($isAgentMember && $agent_discount_total > 0)
                    <tr class="cart-agent-discount">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-success border-top-0">
                            {{ translate('Membership Discount') }}
                        </th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-success border-top-0">
                            - {{ single_price($agent_discount_total) }}
                        </td>
                    </tr>
                @endif
                    @if(addon_is_activated('gst_system'))
                    <!-- Gst -->
                    <tr class="cart-gst">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('GST') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($gst) }}</td>
                    </tr>
                    @endif

                  @php
                        $onlinePayDiscountTotal = $carts->sum('online_pay_discount');
                    
                        $total = $subtotal + $tax + $shipping + $gst;
                    
                        if (Session::has('club_point')) {
                            $total -= Session::get('club_point');
                        }
                    
                        if ($coupon_discount > 0) {
                            $total -= $coupon_discount;
                        }
                    
                          if ($isAgentMember && $agent_discount_total > 0) {
             $total -= $agent_discount_total;
                }

 
                    
                        $total -= $onlinePayDiscountTotal;
                    @endphp

                  
                @if($carts->isNotEmpty() && $carts->first()->is_online_pay == 1 && $onlinePayDiscountTotal > 0)
                    <tr>
                        <th class="pl-0 fs-14 fw-400 text-success">
                            {{ translate('Online Pay Discount') }}
                        </th>
                        <td class="text-right pr-0 fs-14 fw-700 text-success">
                            - {{ single_price($onlinePayDiscountTotal) }}
                        </td>
                    </tr>
                    @endif


                    <!-- Total -->
               <tr class="cart-total border-top pt-3">
                        <th class="pl-0 fs-14 text-dark fw-700 border-top-0 pt-3 text-uppercase">{{ translate('Total') }}</th>
                        <td class="text-end fw-bold fs-5 text-primary pt-3">{{ single_price($total) }}</td>
                    </tr>
                  @if(isset($is_bulk_buyer) && $is_bulk_buyer)
                    <tr>
                        <th class="pl-0 fs-14 fw-400 text-dark">
                            Online Payment (40% via PayU)
                        </th>
                      <td class="text-end fw-bold text-success">
                            {{ single_price($bulk_online_pay_amount) }}
                        </td>
                    </tr>
                    
                    <tr>
                        <th class="pl-0 fs-14 fw-400 text-dark">
                            Cash on Delivery (60%)
                        </th>
                        <td class="text-right pr-0 fs-14 fw-700 text-warning">
                            {{ single_price($bulk_cod_pay_amount) }}
                        </td>
                    </tr>
                    @endif


                </tfoot>
            </table>

            <!-- Coupon System -->
            @if (get_setting('coupon_system') == 1)
                @if ($coupon_discount > 0 && $coupon_code)
                    <div class="mt-3">
                        <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <div class="input-group">
                                <div class="form-control">{{ $coupon_code }}</div>
                                <div class="input-group-append">
                                    <button type="button" id="coupon-remove"
                                        class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-3">
                        <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-start" name="code"
                                    onkeydown="return event.key != 'Enter';"
                                    placeholder="{{ translate('Have coupon code? Apply here') }}" required>
                                <div class="input-group-append">
                                    <button type="button" id="coupon-apply"
                                        class="btn btn-primary rounded-end px-4">{{ translate('Apply') }}</button>
                                </div>
                            </div>
                            @if (!auth()->check())
                                <small>{{ translate('You must Login as customer to apply coupon') }}</small>
                            @endif

                        </form>
                    </div>
                @endif
            @endif
              
            @if ($proceed == 1)
            <!-- Continue to Shipping -->
            <div class="mt-4">
                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 fw-bold py-3 rounded-3 shadow-sm">
                    {{ translate('Proceed to Checkout')}} ({{ sprintf("%02d", count($carts)) }})
                </a>
            </div>
            @endif

        </div>
    </div>
</div>
