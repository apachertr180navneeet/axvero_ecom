@extends('frontend.layouts.app')

@section('content')
@php $lang = get_system_language()->code; @endphp
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* Global resets */
    .h-250px { height: 250px !important; }
    .object-fit-cover { object-fit: cover; }
    .bg-dark-blue { background-color: #0f172a; }
    .text-dark-blue { color: #0f172a; }
    .bg-light-peach { background-color: #fdfbfb; }

    /* 1. Hero Section — split: white left + peach-to-navy gradient right */
    .hero-section {
        background: #ffffff;
        position: relative;
        overflow: hidden;
        min-height: 720px;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 50%;
        height: 100%;
        background: linear-gradient(180deg, #e8c4a8 0%, #d4a88a 12%, #a67f8e 38%, #5a628f 68%, #2a3560 88%, #1a237e 100%);
        z-index: 0;
    }
    .hero-huge-text {
        position: absolute;
        top: 48%;
        left: 50%;
        width: 100%;
        text-align: center;
        transform: translate(-50%, -50%);
        font-size: clamp(64px, 11vw, 176px);
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 700;
        color: #3a3a3a;
        z-index: 1;
        opacity: 0.9;
        white-space: nowrap;
        pointer-events: none;
        letter-spacing: -4px;
        line-height: 1;
    }
    .hero-text {
        padding: 0 1rem 5rem 0;
        z-index: 4;
        position: relative;
        max-width: 400px;
    }
    .hero-subtitle {
        font-size: 13px;
        color: #6b6b6b;
        line-height: 1.85;
        margin-bottom: 1.75rem;
    }
    .hero-img {
        position: absolute;
        left: 50%;
        bottom: 0;
        transform: translateX(-50%);
        height: 94%;
        max-height: 680px;
        z-index: 2;
        object-fit: contain;
        pointer-events: none;
    }
    .hero-floating-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        border-radius: 4px;
        padding: 12px;
        position: absolute;
        z-index: 3;
        display: flex;
        align-items: center;
        gap: 15px;
        width: 320px;
    }
    .hero-floating-card .prod-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        background: #fff;
        flex-shrink: 0;
    }
    .hero-floating-card .prod-info {
        flex: 1;
        min-width: 0;
    }
    .hero-floating-card .prod-title {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .hero-floating-card .prod-price {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 3px;
    }
    .hero-floating-card .add-btn {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #333;
        font-size: 16px;
        align-self: flex-end;
    }
    .hero-floating-card.top-left {
        top: 20%;
        left: 20%;
        background: rgba(255, 255, 255, 0.78);
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
    }
    .hero-floating-card.top-left .prod-title,
    .hero-floating-card.top-left .prod-price { color: #333; }

    .hero-floating-card.bottom-right {
        bottom: 20%;
        right: 5%;
        background: linear-gradient(135deg, #4f6fd8 0%, #2f3f8f 100%);
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 18px 40px rgba(33, 40, 82, 0.35);
    }
    .hero-floating-card.bottom-right .prod-title,
    .hero-floating-card.bottom-right .prod-price { color: #fff; }
    .hero-floating-card.bottom-right .add-btn {
        background: rgba(255, 255, 255, 0.95);
        color: #2f3f8f;
    }

    @media (max-width: 991.98px) {
        .hero-section {
            min-height: 560px;
            background: #ffffff;
        }
        .hero-section::before {
            width: 100%;
            opacity: 0.45;
        }
        .hero-huge-text {
            font-size: clamp(42px, 12vw, 72px);
            top: 42%;
            left: 50%;
        }
        .hero-img {
            left: 50%;
            height: 72%;
            opacity: 0.9;
        }
        .hero-floating-card {
            width: 260px;
            padding: 10px;
        }
        .hero-floating-card.top-left {
            top: auto;
            bottom: 130px;
            left: 12px;
        }
        .hero-floating-card.bottom-right {
            bottom: 24px;
            right: 12px;
        }
    }

    /* Hide left floating action buttons on Axvero homepage */
    .aiz-axvero_theme .floating-buttons-section {
        display: none !important;
    }

    /* 2. Category Circles */
    .cat-circle-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        text-decoration: none !important;
        color: #333;
    }
    .cat-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 10px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cat-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .cat-circle-wrap:hover .cat-circle img {
        transform: scale(1.1);
    }
    
    /* Section Headers */
    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        text-align: left;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }
    .section-title span { color: #001f3f; }
    
    /* Product Cards (General) */
    .product-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: inherit;
        display: block;
        height: 100%;
    }
    .product-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .product-img-wrap {
        height: 250px;
        padding: 20px;
        background: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
    .product-info { padding: 15px; }

    /* 4. Latest Edition Card */
    .latest-banner-deck {
        padding: 80px 0 100px;
        background: #ffffff;
        overflow: visible;
        position: relative;
        font-family: 'Poppins', sans-serif;
    }
    .deck-stage {
        position: relative;
        width: 100%;
        max-width: 680px;
        padding-bottom: 120px;
    }
    .deck-container {
        position: relative;
        width: 100%;
        max-width: 680px;
        aspect-ratio: 3 / 2;
        height: auto;
        margin-bottom: 0;
        z-index: 5;
        perspective: 1200px;
        overflow: visible;
        padding: 0 24px;
    }
    .deck-card {
        position: absolute;
        top: 0;
        left: 24px;
        right: 24px;
        width: calc(100% - 48px);
        height: 100%;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.07);
        border: 1px solid rgba(0, 0, 0, 0.04);
        transition: transform 0.6s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.3s ease;
        transform-origin: center 88%;
    }
    .deck-card-back {
        pointer-events: none;
        box-shadow: 0 14px 42px rgba(0, 0, 0, 0.08);
    }
    .deck-card-back-1 {
        transform: rotate(-11deg);
        z-index: 1;
    }
    .deck-card-back-2 {
        transform: rotate(9deg);
        z-index: 2;
    }
    .deck-card-front {
        z-index: 3;
        cursor: pointer;
        transform: rotate(0deg);
        box-shadow: 0 18px 50px rgba(0, 0, 0, 0.09);
    }
    .deck-container:hover .deck-card-back-1 {
        transform: rotate(-14deg);
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.1);
    }
    .deck-container:hover .deck-card-back-2 {
        transform: rotate(12deg);
        box-shadow: 0 18px 48px rgba(0, 0, 0, 0.1);
    }
    .deck-container:hover .deck-card-front {
        transform: translateY(-6px) rotate(0deg);
        box-shadow: 0 22px 56px rgba(0, 0, 0, 0.11);
    }
    .deck-card-front.is-switching {
        animation: deckCardSwap 0.45s ease;
    }
    @keyframes deckCardSwap {
        0% { opacity: 1; transform: translateY(0) scale(1); }
        45% { opacity: 0.35; transform: translateY(10px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .deck-card-content {
        display: flex;
        align-items: center;
        height: 100%;
        padding: 30px 40px;
        gap: 30px;
        color: inherit;
    }
    .deck-product-img {
        flex: 0 0 45%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .deck-product-img img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.4s ease;
    }
    .deck-container:hover .deck-product-img img {
        transform: scale(1.03);
    }
    .deck-product-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .deck-product-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #000000;
        margin-bottom: 12px;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .deck-product-desc {
        font-family: 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 400;
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 24px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .deck-product-price {
        font-family: 'Poppins', sans-serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: #000000;
        line-height: 1;
    }
    .deck-bottom-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 8px;
        position: relative;
        z-index: 4;
        pointer-events: none;
    }
    .deck-giant-text {
        font-size: clamp(5rem, 14vw, 9rem);
        font-weight: 900;
        color: #053C6B;
        letter-spacing: -2px;
        line-height: 0.92;
        margin: 0 0 18px 0;
        padding-top: 0;
        text-transform: uppercase;
        font-family: 'Poppins', sans-serif;
        user-select: none;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    .deck-btn-getnow {
        background-color: #4ac7f8;
        color: #000000;
        border: none;
        padding: 12px 64px;
        font-size: 0.95rem;
        font-weight: 700;
        font-family: 'Poppins', sans-serif;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(74, 199, 248, 0.3);
        display: inline-block;
        position: relative;
        z-index: 6;
        pointer-events: auto;
    }
    .deck-btn-getnow:hover {
        background-color: #35b0e0;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(74, 199, 248, 0.4);
        color: #000000;
        text-decoration: none;
    }
    @media (max-width: 768px) {
        .latest-banner-deck {
            padding-bottom: 80px;
        }
        .deck-stage {
            max-width: 92%;
            padding-bottom: 90px;
        }
        .deck-container {
            max-width: 100%;
            aspect-ratio: 3 / 2;
            height: auto;
            padding: 0 16px;
        }
        .deck-card {
            left: 16px;
            right: 16px;
            width: calc(100% - 32px);
        }
        .deck-container .deck-card-back-1 {
            transform: rotate(-9deg);
        }
        .deck-container .deck-card-back-2 {
            transform: rotate(7deg);
        }
        .deck-card-content {
            flex-direction: row;
            padding: 20px 22px;
            text-align: left;
            gap: 16px;
        }
        .deck-product-img {
            flex: 0 0 42%;
            height: 100%;
            width: auto;
        }
        .deck-product-title {
            font-size: 1.25rem;
            margin-bottom: 6px;
            -webkit-line-clamp: 2;
        }
        .deck-product-desc {
            font-size: 0.8rem;
            margin-bottom: 10px;
            line-height: 1.5;
            -webkit-line-clamp: 2;
        }
        .deck-product-price {
            font-size: 1.25rem;
        }
        .deck-giant-text {
            font-size: clamp(3.5rem, 18vw, 5rem);
        }
        .deck-bottom-wrapper {
            margin-top: -14px;
        }
    }
    
    /* 5. Our Collection */
    .our-collection-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        border-radius: 0;
        height: 250px;
        text-decoration: none !important;
        transition: transform 0.3s;
    }
    .our-collection-box:hover { transform: translateY(-5px); }
    .our-collection-box img { max-height: 120px; margin-bottom: 15px; object-fit: contain; }
    
    /* 7. Sweet Spring Lookbook */
    .arched-img {
        border-radius: 150px 150px 0 0;
        overflow: hidden;
        background: #f1f1f1;
        height: 100%;
    }
    .arched-img img { width: 100%; height: 100%; object-fit: cover; }
    
    /* 9. Vertical Plant Cards */
    .vertical-dark-section {
        background: #001f3f;
        padding: 80px 0;
        position: relative;
    }
    .vertical-card {
        background: #fff;
        padding: 15px;
        height: 350px;
        border-radius: 5px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .vertical-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* 10. Square Categories */
    .square-cat {
        background: #f4f4f4;
        height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    /* 11. Dark Banner Section */
    .dark-interior-banner {
        background: #252342;
        color: white;
        display: flex;
        min-height: 350px;
    }
    
    /* 12. Lower Promo Squares */
    .promo-square {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0;
    }
</style>

<!-- 1. Hero Section -->
@php
    $hero_img_setting = get_setting('axvero_hero_image', null, $lang);
    $hero_img = ($hero_img_setting != null && $hero_img_setting != '') ? uploaded_asset($hero_img_setting) : static_asset('assets/img/demo/lady.png');
    $hero_title = get_setting('axvero_hero_title', null, $lang) ?: 'New Fashion';
    $hero_subtitle = get_setting('axvero_hero_subtitle', null, $lang) ?: 'Elevate your wardrobe and make a statement with our fashion forward pieces. Step into a realm of endless possibilities and discover the perfect outfit that speaks to your unique style. Get ready to turn heads and exude confidence with every step you take. Your fashion journey starts here.';
    $hero_btn_text = get_setting('axvero_hero_btn_text', null, $lang) ?: 'SHOP NOW';
    $hero_btn_link = get_setting('axvero_hero_btn_link', null, $lang) ?: route('search');
    $latest_hero_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))->limit(2)->get();
@endphp
<div class="container-fluid px-0 mb-5">
    <div class="hero-section">
        <div class="hero-huge-text">{{ $hero_title }}</div>

        <img src="{{ $hero_img }}" class="hero-img d-none d-md-block" alt="Fashion Model" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/lady.png') }}';">

        <div class="container h-100 position-relative z-2">
            <div class="row align-items-end h-100" style="min-height: 720px;">
                <div class="col-md-5 col-lg-4">
                    <div class="hero-text">
                        <p class="hero-subtitle mb-0">{{ $hero_subtitle }}</p>
                        <a href="{{ $hero_btn_link }}" class="btn btn-primary px-4 py-2 rounded-0 shadow-sm fw-600 mt-4 d-inline-block" style="background-color: #293868; border-color: #293868;">{{ $hero_btn_text }}</a>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($latest_hero_products[0]))
        <a href="{{ route('product', $latest_hero_products[0]->slug) }}" class="hero-floating-card top-left text-decoration-none d-none d-md-flex">
            <img src="{{ uploaded_asset($latest_hero_products[0]->thumbnail_img) }}" class="prod-img" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <div class="prod-info">
                <div class="prod-title">{{ $latest_hero_products[0]->getTranslation('name') }}</div>
                <div class="prod-price">{{ home_discounted_base_price($latest_hero_products[0]) }}</div>
                <div class="rating-stars" style="color: #999; font-size: 10px;">
                    <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                </div>
            </div>
            <div class="add-btn"><i class="las la-plus"></i></div>
        </a>
        @endif

        @if(isset($latest_hero_products[1]))
        <a href="{{ route('product', $latest_hero_products[1]->slug) }}" class="hero-floating-card bottom-right text-decoration-none d-none d-md-flex">
            <img src="{{ uploaded_asset($latest_hero_products[1]->thumbnail_img) }}" class="prod-img" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            <div class="prod-info">
                <div class="prod-title">{{ $latest_hero_products[1]->getTranslation('name') }}</div>
                <div class="prod-price">{{ home_discounted_base_price($latest_hero_products[1]) }}</div>
                <div class="rating-stars" style="color: rgba(255,255,255,0.75); font-size: 10px;">
                    <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                </div>
            </div>
            <div class="add-btn"><i class="las la-plus"></i></div>
        </a>
        @endif
    </div>
</div>

<!-- 2. Category Circles -->
<div class="container mb-5">
    <div class="row gutters-10 justify-content-center">
        @foreach (get_level_zero_categories()->take(8) as $key => $category)
        <div class="col-6 col-sm-4 col-md-3 col-lg-auto mb-3">
            <a href="{{ route('products.category', $category->slug) }}" class="cat-circle-wrap">
                <div class="cat-circle shadow-sm">
                    @if ($category->banner)
                        <img src="{{ uploaded_asset($category->banner) }}" alt="{{ $category->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                    @elseif (isset($category->catIcon->file_name))
                        <img src="{{ my_asset($category->catIcon->file_name) }}" alt="{{ $category->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}" alt="{{ $category->getTranslation('name') }}">
                    @endif
                </div>
                <div class="fs-14 fw-600 mt-2 text-dark">{{ $category->getTranslation('name') }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 3. New Products Grid 1 -->
<div class="container mb-5">
    <div class="section-title">
        <span>NEW PRODUCTS</span>
        <a href="{{ route('search') }}" class="btn btn-primary btn-sm rounded-0">VIEW ALL</a>
    </div>
    <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="4" data-xl-items="4" data-lg-items="3" data-md-items="2" data-sm-items="2" data-xs-items="2" data-arrows="true">
        @php
            $new_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))->limit(10)->get();
        @endphp
        @foreach($new_products as $product)
        <div class="carousel-box mb-3">
            <a href="{{ route('product', $product->slug) }}" class="product-card">
                <div class="product-img-wrap">
                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                <div class="product-info">
                    <div class="fs-14 fw-600 mb-1 text-dark text-truncate-2">{{ $product->getTranslation('name') }}</div>
                    <div class="fs-16 fw-700 text-primary">{{ home_discounted_base_price($product) }}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 4. Latest Edition Card -->
@php
    $latest_deck_products = filter_products(
        \App\Models\Product::where('published', 1)->orderBy('created_at', 'desc')
    )->limit(3)->get();

    $latest_title = get_setting('axvero_latest_title', null, $lang);
    $latest_subtitle = get_setting('axvero_latest_subtitle', null, $lang);
    $latest_price = get_setting('axvero_latest_price', null, $lang);
    $latest_link = get_setting('axvero_latest_link', null, $lang);
    $latest_product_image_setting = get_setting('axvero_latest_product_image', null, $lang);

    $deck_products = [];
    foreach ($latest_deck_products as $index => $product) {
        $deck_products[] = [
            'title' => ($index === 0 && $latest_title) ? $latest_title : $product->getTranslation('name'),
            'subtitle' => $latest_subtitle ?: 'Unwind in style with Lazy Head T-shirts – comfort meets cool.',
            'price' => ($index === 0 && $latest_price) ? $latest_price : home_discounted_base_price($product),
            'link' => ($index === 0 && $latest_link) ? $latest_link : route('product', $product->slug),
            'image' => ($index === 0 && $latest_product_image_setting)
                ? uploaded_asset($latest_product_image_setting)
                : uploaded_asset($product->thumbnail_img),
        ];
    }

    if (empty($deck_products)) {
        $deck_products[] = [
            'title' => $latest_title ?: 'Skyline Tee',
            'subtitle' => $latest_subtitle ?: 'Unwind in style with Lazy Head T-shirts – comfort meets cool.',
            'price' => $latest_price ?: '₹1999',
            'link' => $latest_link ?: route('search'),
            'image' => $latest_product_image_setting
                ? uploaded_asset($latest_product_image_setting)
                : static_asset('assets/img/demo/demo_thumb_fashion.png'),
        ];
    }

    $deck_front = $deck_products[0];
    $placeholder_img = static_asset('assets/img/placeholder.jpg');
@endphp
<section class="latest-banner-deck mb-5">
    <div class="container d-flex flex-column align-items-center">
        <div class="deck-stage">
            <div class="deck-container" id="axveroLatestDeck" role="button" tabindex="0" aria-label="View next latest product">
                <div class="deck-card deck-card-back deck-card-back-1"></div>
                <div class="deck-card deck-card-back deck-card-back-2"></div>
                <div class="deck-card deck-card-front" id="axveroLatestDeckFront">
                    <div class="deck-card-content">
                        <div class="deck-product-img">
                            <img id="axveroLatestDeckImage" src="{{ $deck_front['image'] }}" alt="{{ $deck_front['title'] }}" onerror="this.onerror=null;this.src='{{ $placeholder_img }}';">
                        </div>
                        <div class="deck-product-details">
                            <h3 class="deck-product-title" id="axveroLatestDeckTitle">{{ $deck_front['title'] }}</h3>
                            <p class="deck-product-desc mb-0" id="axveroLatestDeckSubtitle">{{ $deck_front['subtitle'] }}</p>
                            <div class="deck-product-price" id="axveroLatestDeckPrice">{{ $deck_front['price'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="deck-bottom-wrapper">
                <h2 class="deck-giant-text">LATEST</h2>
                <a href="{{ $deck_front['link'] }}" class="deck-btn-getnow" id="axveroLatestDeckBtn">Get Now</a>
            </div>
        </div>
    </div>
</section>

<script type="application/json" id="axveroLatestDeckData">@json($deck_products)</script>

<!-- 5. Our Collection -->
<div class="container mb-5">
    <div class="section-title">
        <span>Our Collection</span>
        <div class="d-flex gap-2">
            <!-- arrows if needed -->
        </div>
    </div>
    <div class="row gutters-10">
        @php
            $bg_colors = ['#f8d7da', '#dc3545', '#6c757d', '#f8f9fa'];
            $text_colors = ['#333', '#fff', '#fff', '#333'];
            $collections = get_level_zero_categories()->take(4);
        @endphp
        @foreach($collections as $key => $collection)
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('products.category', $collection->slug) }}" class="our-collection-box" style="background-color: {{ $bg_colors[$key%4] }}; color: {{ $text_colors[$key%4] }};">
                <img src="{{ uploaded_asset($collection->banner) }}" alt="{{ $collection->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                <div class="fs-16 fw-700 mt-3 text-truncate">{{ $collection->getTranslation('name') }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>



<!-- 7. Sweet Spring Lookbook -->
<div class="container mb-5">
    <div class="text-center mb-4">
        <h2 class="fw-800 mb-1" style="font-family: 'Playfair Display', serif;">Sweet Spring</h2>
        <p class="text-muted">Discover our beautiful spring collection.</p>
    </div>
    <div class="row gutters-10 justify-content-center" style="height: 500px;">
        @php
            $top_two_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(2)->get();
            $bg_colors_spring = ['#d84a2c', '#7b1933'];
        @endphp
        @foreach($top_two_products as $key => $product)
        <div class="col-md-6 h-100">
            <a href="{{ route('product', $product->slug) }}" class="d-block h-100 text-decoration-none">
                <div class="arched-img d-flex flex-column align-items-center justify-content-end pb-5 text-center transition" style="background-color: {{ $bg_colors_spring[$key%2] }}; border-radius: 250px 250px 0 0;">
                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" class="mb-4" style="height: 60%; width: auto; object-fit: contain; max-width: 80%;" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <div class="text-white fs-22 fw-700 px-4 text-truncate w-100">{{ $product->getTranslation('name') }}</div>
                    <div class="text-white fs-18 mt-2 fw-600 opacity-90">{{ home_discounted_base_price($product) }}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 8. Best Products -->
<div class="container mb-5">
    <div class="section-title">
        <span>BEST PRODUCTS</span>
        <a href="{{ route('search') }}" class="btn btn-primary btn-sm rounded-0">VIEW ALL</a>
    </div>
    <div class="row gutters-10">
        @php
            $best_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(4)->get();
        @endphp
        @foreach($best_products as $product)
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('product', $product->slug) }}" class="product-card">
                <div class="product-img-wrap" style="background: #fff; border-bottom: 1px solid #eee;">
                    <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="{{ $product->getTranslation('name') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                <div class="product-info text-center">
                    <div class="fs-14 fw-600 mb-1 text-dark text-truncate-2">{{ $product->getTranslation('name') }}</div>
                    <div class="fs-16 fw-700 text-primary">{{ home_discounted_base_price($product) }}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 9. Vertical Plant Cards (Dark Section) -->
<div class="vertical-dark-section mb-5">
    <div class="container">
        <div class="text-center text-white mb-5">
            <h2 class="fw-800" style="font-family: 'Playfair Display', serif;">LATEST STORIES</h2>
        </div>
        <div class="row gutters-10 justify-content-center">
            @php
                $latest_blogs = \App\Models\Blog::where('status', 1)->orderBy('created_at', 'desc')->limit(3)->get();
            @endphp
            @foreach($latest_blogs as $blog)
            <div class="col-md-4 mb-3">
                <div class="vertical-card" style="padding: 10px;">
                    <a href="{{ route('blog.details', $blog->slug) }}" class="d-block w-100 h-100">
                        <img src="{{ uploaded_asset($blog->banner) }}" class="rounded" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </a>
                </div>
                <a href="{{ route('blog.details', $blog->slug) }}" class="text-white text-center mt-3 fw-600 d-block text-truncate text-decoration-none">{{ $blog->title }}</a>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- 10. Square Categories -->
<div class="container mb-5">
    <div class="section-title">
        <span>LATEST TRENDS</span>
        <a href="{{ route('search') }}" class="btn btn-outline-dark btn-sm rounded-0">DISCOVER</a>
    </div>
    <div class="row gutters-10">
        @php
            $trending_categories = \App\Models\Category::where('featured', 1)->limit(4)->get();
            if($trending_categories->count() == 0) $trending_categories = get_level_zero_categories()->take(4);
        @endphp
        @foreach($trending_categories as $category)
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('products.category', $category->slug) }}" class="d-block text-center text-dark text-decoration-none">
                <div class="square-cat mb-2">
                    <img src="{{ uploaded_asset($category->banner) }}" style="max-height: 80%; max-width: 80%; object-fit: contain;" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                <div class="fw-700 text-truncate">{{ $category->getTranslation('name') }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 11. Dark Banner Section -->
<div class="container mb-5">
    @php
        $banner_product = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))->skip(4)->first();
    @endphp
    <div class="dark-interior-banner rounded overflow-hidden">
        <div class="row no-gutters w-100">
            <div class="col-md-6 d-flex flex-column justify-content-center p-5">
                <div class="fs-12 text-uppercase mb-2" style="letter-spacing: 2px;">New Collection</div>
                <h2 class="fw-800 mb-4" style="font-family: 'Playfair Display', serif;">{{ $banner_product ? $banner_product->getTranslation('name') : 'ELEVATE YOUR SPACE' }}</h2>
                <p class="mb-4 opacity-70">Discover our premium new collections today.</p>
                <div>
                    <a href="{{ $banner_product ? route('product', $banner_product->slug) : route('search') }}" class="btn btn-outline-light text-white rounded-0 px-4">EXPLORE</a>
                </div>
            </div>
            <div class="col-md-6 bg-white d-flex align-items-center justify-content-center">
                @if($banner_product)
                <img src="{{ uploaded_asset($banner_product->thumbnail_img) }}" class="w-100 h-100 object-fit-contain p-4" style="min-height: 350px; max-height: 400px;" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                @else
                <img src="{{ static_asset('assets/img/demo/demo_thumb_automobile.png') }}" class="w-100 h-100 object-fit-cover" style="min-height: 350px;">
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 12. Square Promo Grid -->
<div class="container mb-5">
    <div class="section-title">
        <span>NEW ARRIVALS</span>
        <div class="d-flex gap-2"></div>
    </div>
    <div class="row gutters-10">
        @php 
            $promo_bg = ['#f4fdf8', '#f8f4fd', '#fdf4f4', '#f4f9fd']; 
            $new_arrivals = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))->limit(4)->get();
        @endphp
        @foreach($new_arrivals as $key => $product)
        <div class="col-6 col-md-3 mb-3">
            <a href="{{ route('product', $product->slug) }}" class="d-block promo-square d-flex flex-column align-items-center justify-content-center text-decoration-none" style="background-color: {{ $promo_bg[$key%4] }}; height: 250px;">
                <img src="{{ uploaded_asset($product->thumbnail_img) }}" style="max-height: 60%; max-width: 80%; object-fit: contain;" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                <div class="mt-3 text-dark fw-600 text-truncate px-3 w-100 text-center">{{ $product->getTranslation('name') }}</div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- 13. Lower Banners -->
<div class="container mb-5">
    <div class="row gutters-10">
        <div class="col-md-12 mb-3">
            <div class="d-flex align-items-center justify-content-between p-4 px-md-5 rounded" style="background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);">
                <div>
                    <div class="btn btn-warning btn-sm rounded-0 fw-700 mb-2">NEW OFFERS</div>
                    <h2 class="fw-800 text-dark mb-0" style="font-family: 'Playfair Display', serif;">Special Offers</h2>
                </div>
                <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}" style="max-height: 150px; object-fit: contain;">
                <a href="{{ route('search') }}" class="btn btn-light rounded-0 fw-700 shadow-sm d-none d-md-inline-block">SHOP NOW</a>
            </div>
        </div>
        
        <div class="col-md-12 mb-3">
            <div class="d-flex align-items-center justify-content-center p-5 rounded position-relative overflow-hidden" style="background: #252342; min-height: 300px;">
                <div class="position-absolute z-1" style="font-size: 15rem; font-weight: 900; color: rgba(255,255,255,0.05); top: -20px; text-align: center; width: 100%;">70</div>
                <div class="position-relative z-2 text-center text-white w-100">
                    <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}" class="mx-auto" style="height: 250px; object-fit: contain; position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); z-index: 3;">
                    <h2 class="fw-800 mb-0 position-relative z-4" style="font-family: 'Playfair Display', serif; letter-spacing: 5px; color: rgba(255,255,255,0.8);">UP TO 70% OFF</h2>
                    <h3 class="fw-400 position-relative z-4 text-uppercase mt-2" style="letter-spacing: 2px;">OUTERWEAR</h3>
                </div>
            </div>
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
(function () {
    const deck = document.getElementById('axveroLatestDeck');
    const deckFront = document.getElementById('axveroLatestDeckFront');
    const deckBtn = document.getElementById('axveroLatestDeckBtn');
    const dataEl = document.getElementById('axveroLatestDeckData');
    if (!deck || !deckFront || !dataEl) return;

    let products = [];
    try {
        products = JSON.parse(dataEl.textContent || '[]');
    } catch (e) {
        products = [];
    }
    if (products.length < 2) return;

    let activeIndex = 0;
    const titleEl = document.getElementById('axveroLatestDeckTitle');
    const subtitleEl = document.getElementById('axveroLatestDeckSubtitle');
    const priceEl = document.getElementById('axveroLatestDeckPrice');
    const imageEl = document.getElementById('axveroLatestDeckImage');

    function renderProduct(index) {
        const product = products[index];
        if (!product) return;
        if (titleEl) titleEl.textContent = product.title || '';
        if (subtitleEl) subtitleEl.textContent = product.subtitle || '';
        if (priceEl) priceEl.textContent = product.price || '';
        if (imageEl) {
            imageEl.src = product.image || '';
            imageEl.alt = product.title || '';
        }
        if (deckBtn) deckBtn.href = product.link || deckBtn.href;
    }

    function rotateDeck() {
        activeIndex = (activeIndex + 1) % products.length;
        deckFront.classList.remove('is-switching');
        void deckFront.offsetWidth;
        deckFront.classList.add('is-switching');
        renderProduct(activeIndex);
    }

    deck.addEventListener('click', function (event) {
        if (event.target.closest('#axveroLatestDeckBtn')) return;
        rotateDeck();
    });

    deck.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            rotateDeck();
        }
    });
})();
</script>
@endsection