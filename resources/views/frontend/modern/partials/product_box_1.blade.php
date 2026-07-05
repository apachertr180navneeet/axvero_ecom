@php
    $cart_added = [];
    $carts = get_user_cart();
    if (count($carts) > 0) {
        $cart_added = $carts->pluck('product_id')->toArray();
    }
@endphp
<div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100 position-relative category-tile bg-white" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
    <!-- Image Section -->
    <div class="position-relative img-fit overflow-hidden" style="height: 250px;">
        @php
            $product_url = route('product', $product->slug);
            if ($product->auction_product == 1) {
                $product_url = route('auction-product', $product->slug);
            }
        @endphp
        
        <a href="{{ $product_url }}" class="d-block h-100 w-100">
            <img class="img-fluid w-100 h-100 object-fit-cover product-main-image"
                src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>
        
        <!-- Badges -->
        <div class="position-absolute" style="top: 10px; left: 10px; display: flex; flex-direction: column; gap: 5px;">
            @if (discount_in_percentage($product) > 0)
                <span class="badge badge-danger rounded-pill px-2 py-1 shadow-sm fs-12 fw-bold" style="background: #e74c3c; color: white;">
                    -{{ discount_in_percentage($product) }}%
                </span>
            @endif
            @if ($product->wholesale_product)
                <span class="badge badge-dark rounded-pill px-2 py-1 shadow-sm fs-12 fw-bold">
                    {{ translate('Wholesale') }}
                </span>
            @endif
        </div>

        <!-- Action Icons (Hover) -->
        <div class="position-absolute d-flex flex-column gap-2" style="top: 10px; right: 10px;">
            @if ($product->auction_product == 0)
                <button class="btn btn-light rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center hov-bg-primary hov-text-white" 
                        onclick="addToWishList({{ $product->id }})" data-toggle="tooltip" title="{{ translate('Add to wishlist') }}" style="width: 35px; height: 35px; transition: all 0.2s;">
                    <i class="las la-heart fs-18"></i>
                </button>
                <button class="btn btn-light rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center hov-bg-primary hov-text-white mt-2" 
                        onclick="addToCompare({{ $product->id }})" data-toggle="tooltip" title="{{ translate('Add to compare') }}" style="width: 35px; height: 35px; transition: all 0.2s;">
                    <i class="las la-sync fs-18"></i>
                </button>
            @endif
        </div>
        
        <!-- Add to Cart (Bottom of Image) -->
        @if ($product->auction_product == 0)
            <div class="position-absolute w-100" style="bottom: 0; left: 0;">
                <button class="btn btn-dark w-100 rounded-0 fw-bold py-2 @if (in_array($product->id, $cart_added)) active @endif"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartSingleProduct({{ $product->id }})" @else onclick="showLoginModal()" @endif style="opacity: 0.9;">
                    <i class="las la-shopping-cart mr-1"></i> {{ translate('Add to Cart') }}
                </button>
            </div>
        @endif
    </div>

    <!-- Product Details -->
    <div class="card-body text-center d-flex flex-column justify-content-between p-3" style="background-color: #fff;">
        <h5 class="card-title fs-15 fw-600 text-truncate-2 mb-2" style="line-height: 1.4; height: 42px;">
            <a href="{{ $product_url }}" class="text-dark hov-text-primary" title="{{ $product->getTranslation('name') }}">
                {{ $product->getTranslation('name') }}
            </a>
        </h5>
        
        <div class="d-flex justify-content-center align-items-center gap-2 mt-auto">
            @if ($product->auction_product == 0)
                @if (home_base_price($product) != home_discounted_base_price($product))
                    <del class="text-muted fs-13 mr-2">{{ home_base_price($product) }}</del>
                @endif
                <span class="text-primary fw-bold fs-16">{{ home_discounted_base_price($product) }}</span>
            @endif
        </div>
    </div>
</div>

<style>
    .category-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>
