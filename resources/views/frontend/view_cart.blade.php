@extends('frontend.layouts.app')

@section('content')
    <style>
        .axvero-cart-page {
            min-height: 100vh;
            background: #fff;
        }

        .axvero-cart-shell {
            min-height: 100vh;
            padding-bottom: 80px;
        }

        @media (max-width: 991.98px) {
            .axvero-cart-shell {
                max-width: 480px;
                margin-left: auto;
                margin-right: auto;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            }
        }

        @media (min-width: 992px) {
            .axvero-cart-shell {
                max-width: 100%;
                padding-bottom: 2rem;
            }

            .axvero-cart-stepper .step-divider {
                max-width: 80px !important;
            }
        }
    </style>

    <div class="axvero-cart-page">
        <div class="axvero-cart-shell mx-auto bg-white position-relative w-100">
            <!-- Mobile App Header -->
            <div class="d-lg-none d-flex align-items-center justify-content-between p-3"
                style="background-color: #ffffff; color: #000000; position: sticky; top: 0; z-index: 100;">
                <div class="d-flex align-items-center">
                    <a href="{{ url()->previous() }}" class="text-dark"><i class="las la-arrow-left fs-24"></i></a>
                </div>

                <div class="text-center flex-grow-1">
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    @if ($header_logo != null)
                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-30px h-md-40px">
                    @else
                        <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-30px h-md-40px">
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3 text-dark">
                    <a href="{{ route('search') }}" class="text-dark"><i class="las la-search fs-24"></i></a>
                    <a href="{{ route('wishlists.index') }}" class="text-dark"><i class="lar la-heart fs-24"></i></a>
                    <a href="{{ route('cart') }}" class="text-dark"><i class="las la-shopping-cart fs-24"></i></a>
                </div>
            </div>

            <!-- Page title -->
            <div class="text-center mt-3 mb-4 d-lg-none">
                <h2 class="fw-700 text-dark fs-22">Shopping cart</h2>
            </div>
            <div class="d-none d-lg-block container py-4">
                <h1 class="fw-700 text-dark fs-28 mb-0">Shopping cart</h1>
            </div>

            <!-- Stepper -->
            <div class="d-flex justify-content-center align-items-center px-4 mb-4 axvero-cart-stepper container-lg">
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-700 fs-12"
                        style="width: 24px; height: 24px; background-color: #000;">1</div>
                    <span class="ml-2 fw-700 text-dark fs-14">Cart</span>
                </div>

                <div class="flex-grow-1 mx-2 step-divider"
                    style="height: 2px; background-color: #000; max-width: 40px;"></div>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-700 fs-12"
                        style="width: 24px; height: 24px; background-color: #f5f5f5; color: #888;">2</div>
                    <span class="ml-2 fw-600 fs-14" style="color: #a0a0a0;">Address</span>
                </div>

                <div class="flex-grow-1 mx-2 step-divider"
                    style="height: 1px; background-color: #e0e0e0; max-width: 40px;"></div>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-700 fs-12"
                        style="width: 24px; height: 24px; background-color: #f5f5f5; color: #888;">3</div>
                    <span class="ml-2 fw-600 fs-14" style="color: #a0a0a0;">Payment</span>
                </div>
            </div>

            <!-- Cart Details -->
            <section class="my-3 px-3 px-lg-4" id="cart-details">
                @include('frontend.partials.cart.cart_details', ['carts' => $carts])
            </section>
        </div>
    </div>
@endsection

@section('script')
<script>
    const PRODUCT_LIMIT = {{ $productLimit }};
</script>
<script>
$(document).on('click', '.aiz-plus-minus button', function (e) {
    e.preventDefault();

    let button = $(this);
    let input = button.closest('.aiz-plus-minus').find('.input-number');

    let currentQty = parseInt(input.val());
    let weight = parseFloat(input.data('weight'));

    if (isNaN(currentQty)) currentQty = 1;

    let newQty = currentQty;

    // PLUS BUTTON
    if (button.data('type') === 'plus') {
        newQty = currentQty + 1;

        if ((newQty * weight) > PRODUCT_LIMIT) {
            AIZ.plugins.notify(
                'danger',
                'You cannot add more of this product as it exceeds the weight limit (' + PRODUCT_LIMIT + ' KG)'
            );
            return false;
        }

        let totalWeight = getTotalCartWeight() + weight;
        if (totalWeight > PRODUCT_LIMIT) {
            AIZ.plugins.notify(
                'danger',
                'Total cart weight cannot exceed ' + PRODUCT_LIMIT + ' KG'
            );
            return false;
        }
    }

    if (button.data('type') === 'minus') {
        if (currentQty > 1) {
            newQty = currentQty - 1;
        } else {
            return false;
        }
    }

    input.val(newQty);
    updateQuantity(input.attr('name').match(/\d+/)[0], input[0]);
});
</script>

<script>
function getTotalCartWeight() {
    let total = 0;

    $('.input-number').each(function () {
        let qty = parseInt($(this).val());
        let weight = parseFloat($(this).data('weight'));

        if (!isNaN(qty) && !isNaN(weight)) {
            total += qty * weight;
        }
    });

    return total;
}
</script>

<script>
$(document).on('change', '#bulkBuyerToggle', function () {

    let checkbox = $(this);
    let is_bulk_buyer = checkbox.is(':checked') ? 1 : 0;

    let message = is_bulk_buyer
        ? 'Are you sure you want to enable Bulk Buyer?\n30% Online payment will be required.'
        : 'Are you sure you want to disable Bulk Buyer?';

   
    if (!confirm(message)) {
        
        checkbox.prop('checked', !checkbox.is(':checked'));
        return;
    }

    
     $.ajax({
        url: "{{ route('cart.update.bulk-buyer') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            is_bulk_buyer: is_bulk_buyer
        },
        success: function (res) {
            AIZ.plugins.notify('success', res.message);

          
            setTimeout(function () {
                location.reload();
            }, 500);
        },
        error: function () {
            AIZ.plugins.notify('danger', 'Failed to update bulk buyer');

            checkbox.prop('checked', !checkbox.is(':checked'));
        }
    });

});
</script>

    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key);
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-details').html(data.cart_view);
                AIZ.extra.plusMinus();
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
            });
        }


        // Cart item selection
        $(document).on("change", ".check-all", function() {
            $('.check-one:checkbox').prop('checked', this.checked);
            updateCartStatus();
        });
        $(document).on("change", ".check-seller", function() {
            var value = this.value;
            $('.check-one-'+value+':checkbox').prop('checked', this.checked);
            updateCartStatus();
        });
        $(document).on("change", ".check-one[name='id[]']", function(e) {
            e.preventDefault();
            updateCartStatus();
        });
        function updateCartStatus() {
            $('.aiz-refresh').addClass('active');
            let product_id = [];
            $(".check-one[name='id[]']:checked").each(function() {
                product_id.push($(this).val());
            });

            $.post('{{ route('cart.updateCartStatus') }}', {
                _token: AIZ.data.csrf,
                product_id: product_id
            }, function(data) {
                $('#cart-details').html(data);
                 AIZ.extra.plusMinus();
                 AIZ.plugins.slickCarousel();
                 AIZ.plugins.zoom();
                $('.aiz-refresh').removeClass('active');
            });
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

    </script>
@endsection
