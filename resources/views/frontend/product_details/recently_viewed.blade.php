@if($recentProducts->count() > 1)

<div class="bg-white mb-4 recent-wrapper">

    <!-- Header -->
    <div class="recent-header">
        Recently Viewed Products
    </div>

    <div class="p-3">

        <div class="aiz-carousel gutters-15"
             data-items="4"
             data-xl-items="4"
             data-lg-items="3"
             data-md-items="2"
             data-sm-items="2"
             data-xs-items="1"
             data-arrows="true"
             data-infinite="false">

            @foreach($recentProducts as $recent)

                @if($recent->id != $detailedProduct->id)

                <div class="carousel-box">

                    <div class="recent-card">

                        <!-- Image -->
                        <div class="recent-img-wrapper">
                            <a href="{{ route('product', $recent->slug) }}">
                                <img src="{{ uploaded_asset($recent->thumbnail_img) }}"
                                     alt="{{ $recent->getTranslation('name') }}">
                            </a>
                        </div>

                        <!-- Content -->
                        <div class="recent-content">

                            <a href="{{ route('product', $recent->slug) }}"
                               class="recent-title">
                                {{ $recent->getTranslation('name') }}
                            </a>

                            <div class="recent-price">
                                <span class="new-price">
                                    {{ home_discounted_base_price($recent) }}
                                </span>

                                @if(home_price($recent) != home_discounted_price($recent))
                                    <del class="old-price">
                                        {{ home_price($recent) }}
                                    </del>
                                @endif
                            </div>

                        </div>

                    </div>

                </div>

                @endif

            @endforeach

        </div>

    </div>

</div>

@endif



<style>
    
    /* ===========================
   RECENTLY VIEWED SECTION
=========================== */

.recent-wrapper {
    border: 1px solid #eee;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
}

.recent-header {
    padding: 18px 20px;
    font-size: 18px;
    font-weight: 700;
    border-bottom: 1px solid #eee;
}

/* Card */
.recent-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: 0.3s ease;
}

.recent-card:hover {
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    transform: translateY(-3px);
}

/* Image */
.recent-img-wrapper {
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}

.recent-img-wrapper img {
    max-height: 160px;
    max-width: 100%;
    object-fit: contain;
}

/* Content */
.recent-content {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

/* Title */
.recent-title {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    line-height: 1.4;
    height: 42px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 8px;
}

/* Price */
.recent-price {
    margin-top: auto;
}

.new-price {
    font-weight: 700;
    color: #e62e04;
    font-size: 15px;
}

.old-price {
    font-size: 13px;
    color: #999;
    margin-left: 6px;
}
    
</style>