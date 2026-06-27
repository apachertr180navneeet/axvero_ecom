@extends('frontend.layouts.app')

@section('content')
    <!-- Cart Details -->
    <section class="my-4" id="cart-details">
        @include('frontend.partials.cart.cart_details', ['carts' => $carts])
    </section>

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
