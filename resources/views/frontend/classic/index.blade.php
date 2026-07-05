@extends('frontend.layouts.app')

@section('content')
@php $lang = get_system_language()->code; @endphp
<style>
    .hero-section {
        background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
        border-radius: 15px;
        position: relative;
        overflow: hidden;
    }
    .hero-text {
        padding: 5rem 2rem;
        z-index: 2;
        position: relative;
    }
    .hero-title {
        font-size: 4rem;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: #222;
        margin-bottom: 1rem;
    }
    .hero-img {
        position: absolute;
        right: 5%;
        bottom: 0;
        height: 110%;
        z-index: 1;
    }
    .floating-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 15px;
        position: absolute;
        z-index: 3;
    }
    .floating-card.top-left { top: 20%; left: 10%; }
    .floating-card.bottom-right { bottom: 20%; right: 40%; }
    
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .category-tile {
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        display: block;
    }
    .category-tile img {
        transition: transform 0.3s ease;
    }
    .category-tile:hover img {
        transform: scale(1.05);
    }
    .category-tile .overlay {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background: #fff;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 600;
        color: #333;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .promo-banner {
        background: #f8f9fa;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 2rem 4rem;
    }
    
    .lookbook-item {
        border-radius: 15px;
        overflow: hidden;
    }
    .lookbook-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Utility class for object fit since it might not be in bootstrap 4 */
    .object-fit-cover {
        object-fit: cover;
    }
    .h-250px { height: 250px !important; }
</style>

<!-- 1. Hero Section -->
<div class="container mt-4 mb-5">
    <div class="hero-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="hero-text">
                    <h1 class="hero-title">New Fashion</h1>
                    <p class="lead text-muted mb-4">Discover the latest trends and styles for this season. Elevate your wardrobe with our premium collection.</p>
                    <a href="{{ route('search') }}" class="btn btn-dark btn-lg rounded-pill px-5 shadow">Shop Now</a>
                </div>
            </div>
            <div class="col-md-6 position-relative d-none d-md-block" style="min-height: 400px;">
                <!-- Decorative Elements -->
                <div class="floating-card top-left d-flex align-items-center">
                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" width="40" height="40" class="rounded-circle mr-2">
                    <div>
                        <div class="font-weight-bold fs-12">New Arrival</div>
                        <div class="text-primary fs-14 fw-700">$120.00</div>
                    </div>
                </div>
                
                <div class="floating-card bottom-right d-flex align-items-center">
                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" width="40" height="40" class="rounded-circle mr-2">
                    <div>
                        <div class="font-weight-bold fs-12">Trending</div>
                        <div class="text-primary fs-14 fw-700">$89.99</div>
                    </div>
                </div>
                
                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" class="hero-img" alt="Fashion Model">
            </div>
        </div>
    </div>
</div>

<!-- 2. New Products -->
<div id="section_newest"></div>

<!-- 3. Latest Edition Promo Card -->
<div class="container mb-5">
    <div class="position-relative rounded overflow-hidden shadow-lg" style="background: url('{{ static_asset('assets/img/placeholder-rect.jpg') }}') center/cover; min-height: 400px;">
        <div class="position-absolute w-100 h-100" style="background: rgba(0,30,80,0.6);"></div>
        <div class="position-relative z-1 d-flex flex-column justify-content-center align-items-center text-center h-100 text-white p-5" style="min-height: 400px;">
            <div class="bg-white text-dark p-4 rounded shadow-sm mb-4" style="max-width: 400px;">
                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" class="img-fluid mb-3 rounded" style="max-height: 250px; object-fit: cover;">
                <h3 class="fw-700 fs-20">Skyline Tee</h3>
                <p class="text-muted mb-2">Premium Cotton</p>
                <div class="text-primary fw-bold fs-18">$59.00</div>
            </div>
            <h2 class="display-4 fw-800 mb-4" style="font-family: 'Playfair Display', serif;">LATEST EDITION</h2>
            <a href="{{ route('search') }}" class="btn btn-info rounded-pill px-5 text-white fw-600">Shop Now</a>
        </div>
    </div>
</div>

<!-- 4. Our Collection -->
@if(count($featured_categories) > 0)
<div class="container mb-5">
    <h2 class="section-title">Our Collection</h2>
    <div class="row gutters-16 justify-content-center">
        @foreach($featured_categories->take(4) as $category)
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('products.category', $category->slug) }}" class="category-tile shadow-sm h-100">
                <img src="{{ uploaded_asset($category->banner) }}" class="w-100 h-250px object-fit-cover" alt="{{ $category->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                <div class="overlay">{{ $category->getTranslation('name') }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- 5. Promo Banner (30% Off) -->
<div class="container mb-5">
    <div class="promo-banner shadow-sm flex-column flex-md-row">
        <div class="d-flex align-items-center mb-3 mb-md-0">
            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" width="80" class="mr-4 rounded">
            <img src="{{ static_asset('assets/img/placeholder.jpg') }}" width="80" class="mr-4 rounded">
            <div>
                <h3 class="fw-700 mb-0">Get 30% Off</h3>
                <p class="text-muted mb-0">On selected tops & tees</p>
            </div>
        </div>
        <a href="{{ route('search') }}" class="btn btn-dark rounded-pill px-4 py-2">Shop Now</a>
    </div>
</div>

<!-- 6. Sweet Spring Lookbook -->
<div class="container mb-5">
    <h2 class="section-title">Sweet Spring</h2>
    <p class="text-center text-muted mb-4">Discover our beautiful spring collection lookbook.</p>
    <div class="row gutters-10" style="min-height: 400px;">
        <div class="col-md-4 mb-3">
            <div class="lookbook-item h-100 shadow-sm">
                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="Look 1">
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="row gutters-10 h-100">
                <div class="col-12 mb-3" style="height: calc(50% - 0.5rem);">
                    <div class="lookbook-item h-100 shadow-sm">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" alt="Look 2">
                    </div>
                </div>
                <div class="col-12" style="height: calc(50% - 0.5rem);">
                    <div class="lookbook-item h-100 shadow-sm">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" alt="Look 3">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="lookbook-item h-100 shadow-sm">
                <img src="{{ static_asset('assets/img/placeholder.jpg') }}" alt="Look 4">
            </div>
        </div>
    </div>
</div>

<!-- 7. Best Products -->
<div id="section_best_selling"></div>

<!-- 8. Special Offers Lower Banners -->
<div class="container mb-5">
    <div class="row gutters-16">
        <div class="col-md-6 mb-3">
            <a href="{{ route('search') }}" class="d-block rounded overflow-hidden position-relative shadow-sm hov-scale-img" style="height: 250px;">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Special Offer 1">
                <div class="position-absolute absolute-full d-flex align-items-center p-4 bg-dark" style="background-color: rgba(0,0,0,0.3) !important;">
                    <div class="text-white">
                        <h3 class="fw-700">Special Offers</h3>
                        <p class="mb-0">Up to 50% Off</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-6 mb-3">
            <a href="{{ route('search') }}" class="d-block rounded overflow-hidden position-relative shadow-sm hov-scale-img" style="height: 250px;">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" class="w-100 h-100 object-fit-cover" alt="Special Offer 2">
                <div class="position-absolute absolute-full d-flex align-items-center p-4 bg-dark" style="background-color: rgba(0,0,0,0.3) !important;">
                    <div class="text-white">
                        <h3 class="fw-700">New Outerwear</h3>
                        <p class="mb-0">Discover the collection</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Hidden containers for remaining dynamic sections to prevent JS errors -->
<div id="section_featured" class="d-none"></div>
<div id="todays_deal" class="d-none"></div>
<div id="auction_products" class="d-none"></div>
<div id="section_featured_preorder_products" class="d-none"></div>
<div id="section_home_categories" class="d-none"></div>

@endsection

@section('script')
<script>
    // Include existing necessary scripts if any
</script>
@endsection