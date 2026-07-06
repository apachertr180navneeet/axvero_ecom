<div class="cart-items-wrapper pb-5 mb-5" style="background-color: #ffffff;">
    @php
        $cart_count = count($carts);
        $active_carts = $cart_count > 0 ? $carts->toQuery()->active()->get() : [];
        $subtotal = 0;
        $shipping = 0;
        $tax = 0;
        $coupon_discount = 0;

        foreach ($carts as $key => $cartItem) {
            $product = get_single_product($cartItem['product_id']);
            $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
            $item_price = cart_product_price($cartItem, $product, false);
            $subtotal += $item_price * $cartItem->quantity;
            $shipping += $cartItem['shipping_cost'];
            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];

            if ((get_setting('coupon_system') == 1) && ($cartItem->coupon_applied == 1)) {
                $coupon_discount = $carts->sum('discount');
            }
        }
        
        $grand_total = $subtotal + $shipping + $tax - $coupon_discount;
    @endphp

    @if($cart_count > 0)
        <!-- Hidden Inputs for AJAX update -->
        <input type="checkbox" class="check-all d-none" checked>

        <ul class="list-group list-group-flush mb-4 px-2">
            @foreach ($carts as $key => $cartItem)
                @php
                    $product = get_single_product($cartItem['product_id']);
                    $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
                    $item_price = cart_product_price($cartItem, $product, false);
                    $original_price = $product->unit_price; 
                @endphp
                <li class="list-group-item px-0 border-0 mb-3 bg-transparent">
                    <input type="checkbox" class="check-one d-none" name="id[]" value="{{$cartItem->product_id}}" checked>

                    <div class="d-flex bg-white p-3 position-relative" style="border: 1px solid #f0f0f0; border-radius: 12px;">
                        
                        <!-- Remove Icon (Trash) Top Right -->
                        <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItem->id }})" class="position-absolute" style="top: 15px; right: 15px; color: #555; z-index: 2;">
                            <i class="las la-trash-alt fs-18"></i>
                        </a>

                        <!-- Product Image -->
                        <div class="mr-3" style="width: 85px; height: 110px; border-radius: 8px; overflow: hidden; background: #f8f9fa; flex-shrink: 0;">
                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" class="w-100 h-100 object-fit-cover" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>

                        <!-- Product Details -->
                        <div class="flex-grow-1 d-flex flex-column justify-content-between py-1 pr-3" style="min-width: 0;">
                            <div>
                                <h6 class="fs-15 fw-700 text-dark text-truncate mb-1" style="max-width: 90%;">{{ $product->getTranslation('name') }}</h6>
                                
                                <!-- Brand or Subtitle -->
                                <div class="fs-12 text-muted mb-2">
                                    {{ $product->brand ? $product->brand->getTranslation('name') : 'Couture BA 43516' }}
                                </div>
                                
                                <!-- Pricing -->
                                <div class="d-flex align-items-center mb-1">
                                    <span class="fs-15 fw-700 text-dark mr-2">Rs {{ number_format($item_price, 2) }}</span>
                                    @if ($original_price > $item_price)
                                        <span class="fs-13 text-muted text-decoration-line-through">Rs {{ number_format($original_price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <!-- Delivery Info -->
                                <div class="fs-11 text-muted mb-3 d-flex align-items-center">
                                    <i class="las la-sync-alt mr-1"></i> 18-25 days delivery
                                </div>
                            </div>
                            
                            <!-- Size and Qty Pills -->
                            <div class="d-flex align-items-center gap-2">
                                <!-- Size Dropdown Pill -->
                                @if ($cartItem->variation != '')
                                <div class="border rounded-pill px-2 py-1 d-flex align-items-center justify-content-between" style="font-size: 11px; color: #555; min-width: 60px;">
                                    Size: {{ $cartItem->variation }} <i class="las la-caret-down ml-1"></i>
                                </div>
                                @endif
                                
                                <!-- Qty Dropdown Pill (simplified UI to match mockup, internally uses +/-) -->
                                <div class="border rounded-pill px-2 py-1 d-flex align-items-center justify-content-between aiz-plus-minus" style="font-size: 11px; color: #555; min-width: 70px;">
                                    <span class="mr-1">Qty:</span>
                                    <input type="number" name="quantity[{{ $cartItem->id }}]" class="border-0 text-center bg-transparent input-number p-0 m-0" value="{{ sprintf('%02d', $cartItem->quantity) }}" data-weight="{{ $product->weight }}" min="{{ $product->min_qty }}" max="{{ $product_stock ? $product_stock->qty : 10 }}" onchange="updateQuantity({{ $cartItem->id }}, this)" style="width: 20px; outline: none; -webkit-appearance: none; font-size: 11px; color: #555;">
                                    <i class="las la-caret-down ml-1"></i>
                                    <!-- Hidden real buttons for +/- logic if needed by JS -->
                                    <button type="button" class="d-none" data-type="minus" data-field="quantity[{{ $cartItem->id }}]"></button>
                                    <button type="button" class="d-none" data-type="plus" data-field="quantity[{{ $cartItem->id }}]"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        
        <!-- Coupons Section -->
        <div class="px-3 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fs-15 fw-700 text-dark mb-0">Select Coupons</h6>
                <a href="#" class="fs-12 text-muted text-decoration-none">All Coupons <i class="las la-angle-right"></i></a>
            </div>
            
            <div class="d-flex align-items-center border rounded-lg px-3 py-2" style="border-color: #eee !important;">
                <i class="las la-percentage bg-secondary text-white rounded p-1 mr-2 fs-14"></i>
                <input type="text" class="border-0 flex-grow-1 outline-none shadow-none fs-13 text-dark bg-transparent" placeholder="Apply Coupons" style="outline: none;">
                <button class="btn btn-link text-dark p-0 m-0 shadow-none"><i class="las la-arrow-right fs-18"></i></button>
            </div>
        </div>

        <!-- Price Details Section -->
        <div class="px-3 mb-4">
            <h6 class="fs-15 fw-700 text-dark mb-3">Price Details</h6>
            
            <div class="d-flex justify-content-between mb-2">
                <span class="fs-13 text-muted">Subtotal</span>
                <span class="fs-13 text-muted">{{ $subtotal }}</span>
            </div>
            
            <div class="d-flex justify-content-between mb-2">
                <span class="fs-13 text-muted">Discounts</span>
                <span class="fs-13 text-muted">-RS {{ number_format($coupon_discount, 2) }}</span>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
                <span class="fs-13 text-muted">Deliver Charges</span>
                <span class="fs-13 text-muted">Rs {{ $shipping }}</span>
            </div>
            
            <hr class="my-3 border-dashed" style="border-top: 1px dashed #eee;">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="fs-14 fw-700 text-dark">Grand Total</span>
                <span class="fs-15 fw-800 text-dark">Rs {{ number_format($grand_total, 2) }}</span>
            </div>
        </div>

        <!-- Checkout Button Sticky Area -->
        <div class="px-3 pb-3">
            @if(Auth::check())
                <a href="{{ route('checkout') }}" class="btn btn-block text-white fw-700 py-3 shadow-sm" style="background-color: #000; font-size: 16px; border-radius: 4px;">
                    Checkout
                </a>
            @else
                <button class="btn btn-block text-white fw-700 py-3 shadow-sm" style="background-color: #000; font-size: 16px; border-radius: 4px;" onclick="showLoginModal()">
                    Checkout
                </button>
            @endif
        </div>

    @else
        <!-- Empty Cart -->
        <div class="d-flex flex-column align-items-center justify-content-center mt-5 pt-5">
            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                <i class="las la-shopping-cart" style="font-size: 50px; color: #ccc;"></i>
            </div>
            <h4 class="fw-700 text-dark mb-2">Your Cart is Empty</h4>
            <p class="text-muted text-center mb-4">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-4 fw-600">Start Shopping</a>
        </div>
    @endif
    
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</div>
