@extends('frontend.layouts.app')

@section('content')

    <div class="container-fluid p-0 mx-auto bg-light position-relative" style="max-width: 480px; min-height: 100vh; box-shadow: 0 0 20px rgba(0,0,0,0.05); padding-bottom: 80px !important;">
        <!-- Mobile App Header -->
        <div class="d-flex align-items-center justify-content-between p-3" style="background-color: #502288; color: white; position: sticky; top: 0; z-index: 100;">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-white" style="color: #ffffff !important;"><i class="las la-arrow-left fs-24" style="color: #ffffff !important;"></i></a>
                <h5 class="mb-0 fw-600 fs-16 text-white" style="color: #ffffff !important;">Checkout</h5>
            </div>
        </div>

        <div class="text-center mt-3 mb-4">
            <h2 class="fw-700 text-dark fs-22">Shopping cart</h2>
        </div>

        <!-- Stepper -->
        <div class="d-flex justify-content-center align-items-center px-4 mb-4" id="checkout-stepper">
            <!-- Step 1: Cart -->
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-700 fs-12" style="width: 24px; height: 24px; background-color: #000;">1</div>
                <span class="ml-2 fw-700 text-dark fs-14">Cart</span>
            </div>
            
            <!-- Divider -->
            <div class="flex-grow-1 mx-2" style="height: 2px; background-color: #000; max-width: 40px;"></div>
            
            <!-- Step 2: Address -->
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-700 fs-12 step-number" id="step2-number" style="width: 24px; height: 24px; background-color: #000; color: #fff;">2</div>
                <span class="ml-2 fw-700 text-dark fs-14 step-text" id="step2-text">Address</span>
            </div>
            
            <!-- Divider -->
            <div class="flex-grow-1 mx-2 step-divider" id="step2-divider" style="height: 1px; background-color: #e0e0e0; max-width: 40px;"></div>
            
            <!-- Step 3: Payment -->
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-700 fs-12 step-number" id="step3-number" style="width: 24px; height: 24px; background-color: #f5f5f5; color: #888;">3</div>
                <span class="ml-2 fw-600 fs-14 step-text" id="step3-text" style="color: #a0a0a0;">Payment</span>
            </div>
        </div>

        <section class="p-3">
            <form class="form-default" data-toggle="validator" action="{{ route('payment.checkout') }}" role="form" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="is_online_pay" id="is_online_pay" value="{{ $carts->isNotEmpty() ? $carts->first()->is_online_pay : 0 }}">
                
                <!-- STEP 1: Address -->
                <div id="step-address">
                    <h6 class="fs-15 fw-700 text-dark mb-3">Select Delivery Address</h6>
                    <div id="shipping_info">
                        @include('frontend.partials.cart.shipping_info', ['address_id' => $address_id])
                    </div>
                    
                    <div class="mt-4">
                        <button type="button" class="btn btn-block text-white fw-700 py-3 shadow-sm" style="background-color: #000; font-size: 16px; border-radius: 4px;" onclick="goToPaymentStep()">
                            Continue
                        </button>
                    </div>
                </div>

                <!-- STEP 2: Payment (Includes delivery info implicitly if needed, but UI primarily shows payment) -->
                <div id="step-payment" class="d-none">
                    <div class="d-none" id="delivery_info">
                        @include('frontend.partials.cart.delivery_info', ['carts' => $carts, 'carrier_list' => $carrier_list, 'shipping_info' => $shipping_info])
                    </div>
                    
                    <div id="payment_info">
                        @include('frontend.partials.cart.payment_info', ['carts' => $carts, 'total' => $total])
                    </div>
                </div>

            </form>
            
            <div class="d-none" id="cart_summary">
                @include('frontend.partials.cart.cart_summary', ['proceed' => 0, 'carts' => $carts])
            </div>
        </section>

    </div>

    <script>
        function goToPaymentStep() {
            // Hide Address Step
            document.getElementById('step-address').classList.add('d-none');
            // Show Payment Step
            document.getElementById('step-payment').classList.remove('d-none');
            
            // Update Stepper UI for Payment Step
            document.getElementById('step2-divider').style.backgroundColor = '#000';
            document.getElementById('step2-divider').style.height = '2px';
            
            let step3Num = document.getElementById('step3-number');
            step3Num.style.backgroundColor = '#000';
            step3Num.style.color = '#fff';
            
            let step3Text = document.getElementById('step3-text');
            step3Text.style.color = '#000';
            step3Text.classList.remove('fw-600');
            step3Text.classList.add('fw-700');
        }
        
        function goToAddressStep() {
            // Hide Payment Step
            document.getElementById('step-payment').classList.add('d-none');
            // Show Address Step
            document.getElementById('step-address').classList.remove('d-none');
            
            // Update Stepper UI back to Address
            document.getElementById('step2-divider').style.backgroundColor = '#e0e0e0';
            document.getElementById('step2-divider').style.height = '1px';
            
            let step3Num = document.getElementById('step3-number');
            step3Num.style.backgroundColor = '#f5f5f5';
            step3Num.style.color = '#888';
            
            let step3Text = document.getElementById('step3-text');
            step3Text.style.color = '#a0a0a0';
            step3Text.classList.add('fw-600');
            step3Text.classList.remove('fw-700');
        }
    </script>
@endsection

@section('modal')
    <!-- Address Modal -->
    @if(Auth::check())
        @include('frontend.partials.address.address_modal')
         @include('frontend.partials.address.billing_address_modal')
    @endif
@endsection
<style>
    .switch {
    position: relative;
    display: inline-block;
    width: 46px;
    height: 24px;
}

.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #ccc;
    transition: .4s;
}

.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: #28a745;
}

input:checked + .slider:before {
    transform: translateX(22px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}
 
</style>
@section('script')

<script>
        function stepCompletionPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var payment = false;
            var agree = false;
            var allOk = false;
            var length = $('input[name="payment_option"]:checked').length;
            if(length > 0){
                if ($('input[name="payment_option"]:checked').hasClass('offline_payment_option')) {
                    if ($('#trx_id').val() != '' && $('#trx_id').val() != undefined && $('#trx_id').val() != null) {
                        payment = true;
                    }
                } else {
                    payment = true;
                }

                if ($('#agree_checkbox').is(":checked")){
                    agree = true;
                }

                if (payment && agree) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }
 $(document).ready(function() {
    // Initial setup
    var currentOnlinePay = parseInt($('#is_online_pay').val()) === 1;
    $('#online_pay_toggle').prop('checked', currentOnlinePay);
    toggleCodBlock(currentOnlinePay);

    // Prevent multiple AJAX requests
    let isProcessing = false;

    $('#online_pay_toggle').on('change', function () {
        if (isProcessing) return; // already processing

        let toggle = $(this);
        let enableOnlinePay = toggle.is(':checked');
        let confirmText = enableOnlinePay
            ? 'Do you want to enable Online Payment?'
            : 'Do you want to disable Online Payment?';

        Swal.fire({
            title: 'Confirmation',
            text: confirmText,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (!result.isConfirmed) {
                toggle.prop('checked', !enableOnlinePay);
                return;
            }

            // set processing flag
            isProcessing = true;

            // Update hidden field
            $('#is_online_pay').val(enableOnlinePay ? 1 : 0);

            // Toggle COD block
            toggleCodBlock(enableOnlinePay);

            $.ajax({
                url: "{{ route('checkout.toggle-online-pay') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    is_online_pay: enableOnlinePay ? 1 : 0
                },
                success: function (res) {
                    if (res.success && res.html) {
                        $('#cart_summary').html(res.html);
                    } else if(!res.success) {
                        Swal.fire('Error', res.message || 'Something went wrong', 'error');
                        revertToggle();
                    }
                },
                error: function () {
                    Swal.fire('Error', 'Server error. Please try again.', 'error');
                    revertToggle();
                },
                complete: function () {
                    isProcessing = false;
                }
            });

            function revertToggle() {
                toggle.prop('checked', !enableOnlinePay);
                $('#is_online_pay').val(enableOnlinePay ? 0 : 1);
                toggleCodBlock(!enableOnlinePay);
            }
        });
    });

    function toggleCodBlock(onlinePayEnabled) {
        if (onlinePayEnabled) {
            $('#cod_block').hide();
            $('#cod_option').prop('checked', false);
        } else {
            $('#cod_block').show();
        }
    }
});

</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const IS_BULK_BUYER = {{ auth()->check() && auth()->user()->is_bulk_buyer == "1" ? 'true' : 'false' }};
    const BULK_ONLINE_PERCENT = 30;
    const BULK_ONLINE_AMOUNT = {{ $bulk_online_pay_amount ?? 0 }};
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const codRadio = document.getElementById('cod_option');
  const submitBtn = document.getElementById('submitOrderBtn');

    if (codRadio) {
        codRadio.addEventListener('change', function (event) {

            if (!IS_BULK_BUYER) return;

            event.preventDefault();

            Swal.fire({
                title: 'Bulk Buyer Notice',
                html: `
                    <p>
                        You must pay <b>${BULK_ONLINE_PERCENT}%</b> advance
                        via <b>PayU</b>.
                    </p>
                    <p>
                        Remaining amount will be collected via
                        <b>Cash on Delivery</b>.
                    </p>
                `,
                icon: 'info',
                confirmButtonText: 'Proceed to Pay',
                allowOutsideClick: false
            }).then((result) => {

                if (result.isConfirmed) {

                    fetch("{{ route('set.bulk.cod') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ cod_bulk: true })
                    }).then(() => {

                        // ✅ allow checkout
                        submitBtn.disabled = false;

                        // ✅ force PayU as payment option
                        document.querySelector('input[value="payu"]')?.click();

                        // ✅ submit checkout
                        submitOrder(submitBtn);
                    });
                } else {
                    codRadio.checked = false;
                }
            });
        });
    }

});

</script>

<script>
    document.querySelectorAll('input[name="payment_option"]').forEach(el => {
    el.addEventListener('change', function () {

        if (this.value !== 'cash_on_delivery') {
            fetch("{{ route('set.bulk.cod') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cod_bulk: false })
            });
        }
    });
});

</script>
    <script type="text/javascript">
       var carrierCount=0;
        $(document).ready(function() {
            $(".online_payment").click(function() {
                $('#manual_payment_description').parent().addClass('d-none');
            });
            toggleManualPaymentData($('input[name=payment_option]:checked').data('id'));
        });

        var minimum_order_amount_check = {{ get_setting('minimum_order_amount_check') == 1 ? 1 : 0 }};
        var minimum_order_amount =
            {{ get_setting('minimum_order_amount_check') == 1 ? get_setting('minimum_order_amount') : 0 }};

        function use_wallet() {
            $('input[name=payment_option]').val('wallet');
            if ($('#agree_checkbox').is(":checked")) {
                ;
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    var allIsOk = false;
                    var isOkShipping = stepCompletionShippingInfo();
                    var isOkDelivery = stepCompletionDeliveryInfo();
                    var isOkPayment = stepCompletionWalletPaymentInfo();
                    if(isOkShipping && isOkDelivery && isOkPayment) {
                        allIsOk = true;
                    }else{
                        AIZ.plugins.notify('danger', '{{ translate("Please fill in all mandatory fields!") }}');
                        $('#checkout-form [required]').each(function (i, el) {
                            if ($(el).val() == '' || $(el).val() == undefined) {
                                var is_trx_id = $('.d-none #trx_id').length;
                                if(($(el).attr('name') != 'trx_id') || is_trx_id == 0){
                                    $(el).focus();
                                    $(el).scrollIntoView({behavior: "smooth", block: "center"});
                                    return false;
                                }
                            }
                        });
                    }

                    if (allIsOk) {
                        $('#checkout-form')[0].submit();
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate("You need to agree with our policies") }}');
            }
        }

        function submitOrder(el) {
            $(el).prop('disabled', true);
            if ($('#agree_checkbox').is(":checked")) {
                if (minimum_order_amount_check && $('#sub_total').val() < minimum_order_amount) {
                    AIZ.plugins.notify('danger',
                        '{{ translate('You order amount is less then the minimum order amount') }}');
                } else {
                    var offline_payment_active = '1';
                    if (offline_payment_active == '1' && $('.offline_payment_option').is(":checked") && $('#trx_id')
                        .val() == '') {
                        AIZ.plugins.notify('danger', '{{ translate('You need to put Transaction id') }}');
                        $(el).prop('disabled', false);
                    } else {
                        var allIsOk = false;
                        var isOkShipping = stepCompletionShippingInfo();
                        var isOkDelivery = stepCompletionDeliveryInfo();
                        var isOkPayment = stepCompletionPaymentInfo();
                        if(isOkShipping && isOkDelivery && isOkPayment) {
                            allIsOk = true;
                        }else{
                            AIZ.plugins.notify('danger', '{{ translate("Please fill in all mandatory fields!") }}');
                            $('#checkout-form [required]').each(function (i, el) {
                                if ($(el).val() == '' || $(el).val() == undefined) {
                                    var is_trx_id = $('.d-none #trx_id').length;
                                    if(($(el).attr('name') != 'trx_id') || is_trx_id == 0){
                                        $(el).focus();
                                        $(el).scrollIntoView({behavior: "smooth", block: "center"});
                                        return false;
                                    }
                                }
                            });
                        }

                        if (allIsOk) {
                            $('#checkout-form').submit();
                        }
                    }
                }
            } else {
                AIZ.plugins.notify('danger', '{{ translate('You need to agree with our policies') }}');
                $(el).prop('disabled', false);
            }
        }

        function toggleManualPaymentData(id) {
            if (typeof id != 'undefined') {
                $('#manual_payment_description').parent().removeClass('d-none');
                $('#manual_payment_description').html($('#manual_payment_info_' + id).html());
            }
        }
        // coupon apply
        $(document).on("click", "#coupon-apply", function() {
            @if (Auth::check())
                @if(Auth::user()->user_type != 'customer')
                    AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to apply coupon code.') }}");
                    return false;
                @endif

                var data = new FormData($('#apply-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.apply_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                        $("#cart_summary").html(data.html);
                    }
                });
            @else
                $('#login_modal').modal('show');
            @endif
        });

        // coupon remove
        $(document).on("click", "#coupon-remove", function() {
            @if (Auth::check() && Auth::user()->user_type == 'customer')
                var data = new FormData($('#remove-coupon-form')[0]);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: "POST",
                    url: "{{ route('checkout.remove_coupon_code') }}",
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data, textStatus, jqXHR) {
                        $("#cart_summary").html(data);
                    }
                });
            @endif
        });

        function updateDeliveryAddress(id, city_id = 0, area_id=0) {
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryAddress') }}', {
                _token: AIZ.data.csrf,
                address_id: id,
                city_id: city_id,
                area_id: area_id
            }, function(data) {
                $('#delivery_info').html(data.delivery_info);
                $('#cart_summary').html(data.cart_summary);
                $('.aiz-refresh').removeClass('active');
                carrierCount = data.carrier_count;
                checkCarrerShippingInfo();
            });
           
            AIZ.plugins.bootstrapSelect("refresh");
        }

        function updateBillingAddress(id) {
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateBillingAddress') }}', {
                _token: AIZ.data.csrf,
                billing_address_id: id
            }, function(data) {
                $('.aiz-refresh').removeClass('active');
            });
        }

        function stepCompletionShippingInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            @if (Auth::check())
                var length = $('input[name="address_id"]:checked').length;
                if (length > 0) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @else
                var count = 0;
                var length = $('#shipping_info [required]').length;
                $('#shipping_info [required]').each(function (i, el) {
                    if ($(el).val() != '' && $(el).val() != undefined && $(el).val() != null) {
                        count += 1;
                    }
                });
                if (count == length) {
                    headColor = '#15a405';
                    btnDisable = false;
                    allOk = true;
                }
            @endif

            $('#headingShippingInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('#shipping_info [required]').each(function (i, el) {
            $(el).change(function(){
                if ($(el).attr('name') == 'address_id') {
                    updateDeliveryAddress($(el).val());
                    setDefaultshippingAddress();
                    setBillingAddress();
                }
                @if (get_setting('shipping_type') == 'area_wise_shipping')
                    if ($(el).attr('name') == 'city_id') {
                        let country_id = $('select[name="country_id"]').length? $('select[name="country_id"]').val() : $('input[name="country_id"]').val();
                        let city_id = $(this).val();
                        updateDeliveryAddress(country_id, city_id);
                    }
                @endif
                if ($(el).attr('name') == 'billing_address_id') {
                    setBillingAddress(el);
                }
                
                
                stepCompletionShippingInfo();
            });
        });

        $('select[name="area_id"].guest-checkout').change(function () {
            let country_id = $('select[name="country_id"]').length
                ? $('select[name="country_id"]').val()
                : $('input[name="country_id"]').val();
            let city_id = $('select[name="city_id"]').val();
            let area_id = $(this).val();

            if (area_id) {
                updateDeliveryAddress(country_id, city_id, area_id);
            } else {
                updateDeliveryAddress(country_id, city_id);
            }

            stepCompletionShippingInfo();
        });

        function stepCompletionDeliveryInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            var content = $('#delivery_info [required]');
            if (content.length > 0) {
                var content_checked = $('#delivery_info [required]:checked');
                if (content_checked.length > 0) {
                    content_checked.each(function (i, el) {
                        allOk = false;
                        if($(el).val() == 'carrier'){
                            var owner = $(el).attr('data-owner');
                            if ($('input[name=carrier_id_'+owner+']:checked').length > 0) {
                                allOk = true;
                            }
                        }else if($(el).val() == 'pickup_point'){
                            var owner = $(el).attr('data-owner');
                            if ($('select[name="pickup_point_id_'+owner+'"]').val() != '') {
                                allOk = true;
                            }
                        }else{
                            allOk = true;
                        }

                        if(allOk == false) {
                            return false;
                        }
                    });

                    if (allOk) {
                        headColor = '#15a405';
                        btnDisable = false;
                    }
                }
            }else{
                allOk = true
                headColor = '#15a405';
                btnDisable = false;
            }

            $('#headingDeliveryInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        function updateDeliveryInfo(shipping_type, type_id, user_id, country_id = 0, city_id = 0) {
            @if (get_setting('shipping_type') == 'area_wise_shipping' || get_setting('shipping_type') == 'carrier_wise_shipping')
                country_id = $('select[name="country_id"]').val() != null ? $('select[name="country_id"]').val() : 0;
                city_id = $('select[name="city_id"]').val() != null ? $('select[name="city_id"]').val() : 0;
            @endif
            $('.aiz-refresh').addClass('active');
            $.post('{{ route('checkout.updateDeliveryInfo') }}', {
                _token: AIZ.data.csrf,
                shipping_type: shipping_type,
                type_id: type_id,
                user_id: user_id,
                country_id: country_id,
                city_id: city_id
            }, function(data) {
                $('#cart_summary').html(data);
                checkCarrerShippingInfo();
                stepCompletionDeliveryInfo();
                $('.aiz-refresh').removeClass('active');
            });
            AIZ.plugins.bootstrapSelect("refresh");
        }

        function show_pickup_point(el, user_id) {
        	var type = $(el).val();
        	var target = $(el).data('target');
            var type_id = null;

        	if(type == 'home_delivery' || type == 'carrier'){
                if(!$(target).hasClass('d-none')){
                    $(target).addClass('d-none');
                }
                $('.carrier_id_'+user_id).removeClass('d-none');
        	}else{
        		$(target).removeClass('d-none');
        		$('.carrier_id_'+user_id).addClass('d-none');
        	}

            if(type == 'carrier'){
                type_id = $('input[name=carrier_id_'+user_id+']:checked').val();
            }else if(type == 'pickup_point'){
                type_id = $('select[name=pickup_point_id_'+user_id+']').val();
            }
            updateDeliveryInfo(type, type_id, user_id);
        }

        function stepCompletionWalletPaymentInfo() {
            var headColor = '#9d9da6';
            var btnDisable = true;
            var allOk = false;
            if ($('#agree_checkbox').is(":checked")){
                headColor = '#15a405';
                btnDisable = false;
                allOk = true;
            }

            $('#headingPaymentInfo svg *').css('fill', headColor);
            $("#submitOrderBtn").prop('disabled', btnDisable);
            return allOk;
        }

        $('input[name="payment_option"]').change(function(){
            stepCompletionPaymentInfo();
        });

        function checkCarrerShippingInfo(){
           const shippingType = @json(get_setting('shipping_type'));
            const isDisabled = carrierCount === 0;
            let carrierSelected = false;
            let pickupSelected = false;
            $('.shipping-type-radio').each(function () {
                if ($(this).is(':checked') && $(this).val() === 'carrier') {
                    carrierSelected = true;
                }
            });
            $('.shipping-type-radio').each(function () {
                if ($(this).is(':checked') && $(this).val() === 'pickup_point') {
                    pickupSelected = true;
                }
            });
                if(shippingType == 'carrier_wise_shipping' && carrierSelected){
                    if (carrierCount === 0) {
                        if( (carrierSelected && pickupSelected) || (carrierSelected && !pickupSelected) ){
                            $('#submitOrderBtn').prop('disabled', true);
                            $('#agree_checkbox').prop('checked', false).prop('disabled', true);
                            $('.online_payment, .offline_payment_option').prop('checked', false).prop('disabled', true);
                        }
                    } else {
                        $('#agree_checkbox').prop('disabled', false);
                        $('.online_payment, .offline_payment_option').prop('disabled', false);
                    }
                }else{
                    $('#agree_checkbox').prop('disabled', false);
                    $('.online_payment, .offline_payment_option').prop('disabled', false);
                }
        }

        $(document).ready(function(){
            carrierCount = parseInt(document.getElementById('carrierCount')?.value || 0);
            checkCarrerShippingInfo();
            stepCompletionShippingInfo();
            stepCompletionDeliveryInfo();
            stepCompletionPaymentInfo();
            
        });

        function changeShippingAddress(){
            $('#choose-address-modal').modal('hide');
        }

        function setDefaultshippingAddress() {
            let checkedAddress = $('input[name="address_id"]:checked');

            if (checkedAddress.length) {

                let selectedText = checkedAddress.closest('label').find('.address-text').html();
                $('#choose-default').html(selectedText);
                $('#default-address-change-btn').attr('onclick', "edit_address('" + checkedAddress.val() + "')");
                $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                let $box = $('#default-address-box');
                if ($box.length) {
                    $box.removeClass('border-danger');
                    checkedAddress.prop('checked', true);
                    checkedAddress.prop('disabled', false);
                    $box.find('#hide-no-longer-div').remove();
                    
                }
            }
        }

        function setBillingAddress(el) {
            let type = $(el).data('type');
            let checkedAddress = $(el);
           if(type === 'billing'){
                let checkedAddress = $('input[name="billing_address_id"]:checked');
                if (checkedAddress.length) {

                    let selectedText = checkedAddress.closest('label').find('.address-text').html();
                    $('#choose-default-billing').html(selectedText);
                    $('#default-address-change-btn').attr('onclick', "edit_billing_address('" + checkedAddress.val() + "')");
                    $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                    let $box = $('#default-billing-address-box');
                    if ($box.length) {
                        $box.removeClass('border-danger');
                        checkedAddress.prop('checked', true);
                        checkedAddress.prop('disabled', false);
                        $box.find('#hide-no-valid-div').remove();
                        
                    }
                }
            } else{
                let checkedAddress = $('input[name="address_id"]:checked');
                if (checkedAddress.length) {
                    let selectedText = checkedAddress.closest('label').find('.address-text').html();
                    $('#choose-default-billing').html(selectedText);
                    $('input[name="billing_address_id"]').first().val(checkedAddress.val());
                }
            }
            updateBillingAddress(checkedAddress.val());
        }


    </script>

    @include('frontend.partials.address.address_js')

    @if(get_active_countries()->count() == 1)
    <script>
        $(document).ready(function() {
            @if(get_setting('has_state') == 1)
                get_states(@json(get_active_countries()[0]->id));
                @if(get_setting('billing_address_required') == 1)
                  get_billing_states(@json(get_active_countries()[0]->id));
                @endif
            @else
                get_city_by_country(@json(get_active_countries()[0]->id));
                @if(get_setting('billing_address_required') == 1)
                  get_billing_city_by_country(@json(get_active_countries()[0]->id));
                @endif
            @endif
        });
         @if(get_setting('shipping_type') == 'carrier_wise_shipping' && !Auth::check() )
            updateDeliveryAddress({{ get_active_countries()[0]->id }});
         @endif
         
         document.querySelectorAll('input[name="payment_option"]').forEach(function(el) {
            el.addEventListener('change', function() {

                setTimeout(function() {
        
                    if (!$('#agree_checkbox').is(':checked')) {
                        AIZ.plugins.notify('danger', 'You need to agree with our policies');
                        return;
                    }
        
                    if (
                        stepCompletionShippingInfo() &&
                        stepCompletionDeliveryInfo() &&
                        stepCompletionPaymentInfo()
                    ) {
                        submitOrder(document.createElement('button'));
                    }
        
                }, 150);

                 });
         });


        $(document).on('click', 'input[name="payment_option"]', function (e) {
        
            if (!$('#agree_checkbox').is(':checked')) {
        
                e.preventDefault(); 
                e.stopImmediatePropagation();
        
                AIZ.plugins.notify('danger', '{{ translate("You need to agree with our policies") }}');
        
                return false;
            }
        
        });
        
function stepCompletionPaymentInfo() {
    if ($('#agree_checkbox').is(':checked')) {
        $('#submitOrderBtn').prop('disabled', false).css('opacity', '1');
        return true;
    } else {
        $('#submitOrderBtn').prop('disabled', true).css('opacity', '0.6');
        return false;
    }
}
    </script>
    @endif

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif

@endsection
