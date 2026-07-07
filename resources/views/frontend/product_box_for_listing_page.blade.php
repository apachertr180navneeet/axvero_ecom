@php
    $product_url = route('product', $product->slug);
    $productBrand = $product->brand ? $product->brand->getTranslation('name') : 'Fancy Tops';
@endphp

<div class="border-0 shadow-none overflow-hidden d-flex flex-column"
    style="border-radius: 14px; background:#e9e9ed; padding:8px; min-height: 286px; height: 100%; margin: 0;">
    <div class="position-relative" style="height: 192px;">
        <a href="{{ $product_url }}" class="d-flex h-100 w-100 align-items-center justify-content-center overflow-hidden"
            style="border-radius: 12px;">
            <img style="height:100%; width:auto; max-width:100%; object-fit: contain; object-position: center bottom; border-radius: 12px;"
                src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        </a>

        @if (discount_in_percentage($product) > 0)
            <div class="position-absolute" style="top: 8px; left: 8px; z-index: 3;">
                <span
                    style="display:inline-flex;align-items:center;justify-content:center;background:#ff2a2a;color:#ffffff;border-radius:999px;height:18px;min-width:40px;padding:0 8px;font-size:12px;font-weight:700;line-height:1;">
                    -{{ discount_in_percentage($product) }}%
                </span>
            </div>
        @endif

        <button type="button"
            class="btn border-0 rounded-circle d-flex align-items-center justify-content-center position-absolute"
            onclick="addToWishList({{ $product->id }})"
            style="top:10px; right:10px; width:34px; height:34px; background:#f1f1f3; box-shadow:none;">
            <i class="lar la-heart fs-16 text-muted"></i>
        </button>
    </div>

    <div class="px-1 pt-2 pb-1 d-flex flex-column flex-grow-1" style="min-height: 102px;">
        <h3 class="mb-1 mt-2 text-dark fw-700"
            style="font-size: clamp(13px, 1.0vw, 16px); line-height: 1.2; display:-webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow:hidden;">
            <a href="{{ $product_url }}"
                class="text-dark text-decoration-none">{{ $product->getTranslation('name') }}</a>
        </h3>

        <div class="mb-2 text-muted" style="font-size: clamp(11px, 0.8vw, 13px); line-height:1.2;">{{ $productBrand }}
        </div>

        <div class="d-flex align-items-end justify-content-between mt-auto pt-2">
            <div class="d-flex align-items-center">
                <span class="text-dark fw-800"
                    style="font-size: clamp(15px, 0.95vw, 18px); line-height: 1.1;">{{ home_discounted_base_price($product) }}</span>
            </div>
        </div>
    </div>
</div>
