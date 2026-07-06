<div class="card border-0 shadow-sm rounded-lg overflow-hidden h-100 position-relative bg-white" style="border-radius: 12px !important;">
    <!-- Image Section -->
    <div class="position-relative overflow-hidden" style="height: 200px;">
        @php
            $product_url = route('product', $product->slug);
        @endphp
        
        <a href="{{ $product_url }}" class="d-block h-100 w-100">
            <img class="img-fluid w-100 h-100 object-fit-cover"
                src="{{ uploaded_asset($product->thumbnail_img) }}"
                alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>
        
        <!-- Badge -->
        @if (discount_in_percentage($product) > 0)
        <div class="position-absolute" style="top: 10px; left: 10px;">
            <span class="badge rounded-pill px-2 py-1 fs-10 fw-bold" style="background: #e74c3c; color: white;">
                -{{ discount_in_percentage($product) }}%
            </span>
        </div>
        @endif

        <!-- Wishlist -->
        <div class="position-absolute" style="top: 10px; right: 10px;">
            <button class="btn btn-light rounded-circle shadow-sm p-0 d-flex align-items-center justify-content-center" 
                    onclick="addToWishList({{ $product->id }})" style="width: 28px; height: 28px;">
                <i class="lar la-heart fs-16 text-muted"></i>
            </button>
        </div>
    </div>

    <!-- Product Details -->
    <div class="card-body p-2 d-flex flex-column">
        <h5 class="fs-13 fw-700 text-truncate mb-1 text-dark">
            <a href="{{ $product_url }}" class="text-dark text-decoration-none">
                {{ $product->getTranslation('name') }}
            </a>
        </h5>
        
        <div class="fs-11 text-muted mb-2 text-truncate">{{ $product->category?->getTranslation('name') ?? 'Fancy Tops' }}</div>
        
        <div class="d-flex align-items-center gap-2 mb-2">
            @if (home_base_price($product) != home_discounted_base_price($product))
                <del class="text-muted fs-11">{{ home_base_price($product) }}</del>
            @endif
            <span class="text-dark fw-900 fs-14">{{ home_discounted_base_price($product) }}</span>
        </div>
        
        @php
            $colors = is_string($product->colors) ? json_decode($product->colors, true) : $product->colors;
        @endphp
        
        @if(is_array($colors) && count($colors) > 0)
        <div class="d-flex gap-1 mt-auto">
            @foreach(array_slice($colors, 0, 3) as $color)
                <div class="rounded-circle" style="width: 10px; height: 10px; background-color: {{ $color }}; border: 1px solid #eee;"></div>
            @endforeach
            @if(count($colors) > 3)
                <div class="fs-10 text-muted">+{{ count($colors) - 3 }}</div>
            @endif
        </div>
        @endif
    </div>
</div>

<style>
    .category-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>