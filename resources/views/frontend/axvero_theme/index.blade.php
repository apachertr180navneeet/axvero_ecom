@extends('frontend.layouts.app')

@section('content')
    @php $lang = get_system_language()->code; @endphp
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        /* Global resets */
        .h-250px {
            height: 250px !important;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .bg-dark-blue {
            background-color: #0f172a;
        }

        .text-dark-blue {
            color: #0f172a;
        }

        .bg-light-peach {
            background-color: #fdfbfb;
        }

        /* 1. Hero Section — full-width split: white left + peach-to-navy gradient right */
        .axvero-hero-fullbleed {
            width: 100vw;
            max-width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            overflow-x: clip;
        }

        .hero-section {
            background: #ffffff;
            position: relative;
            overflow: hidden;
            min-height: 85vh;
            width: 100%;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(180deg, #F5CCB4 0%, #031556 100%);
            z-index: 0;
        }

        .hero-huge-text {
            position: absolute;
            top: 48%;
            left: 50%;
            width: 100%;
            max-width: 100vw;
            text-align: center;
            transform: translate(-50%, -50%);
            font-size: clamp(64px, 11vw, 176px);
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            color: #3a3a3a;
            z-index: 1;
            opacity: 0.92;
            white-space: nowrap;
            pointer-events: none;
            letter-spacing: -4px;
            line-height: 1;
        }

        .hero-inner {
            min-height: 85vh;
            display: flex;
            align-items: flex-end;
            position: relative;
            z-index: 2;
            padding-bottom: 56px;
        }

        .hero-text {
            padding: 0 1rem 0 0;
            z-index: 4;
            position: relative;
            max-width: 420px;
        }

        .hero-subtitle {
            font-size: 13px;
            color: #6b6b6b;
            line-height: 1.85;
            margin-bottom: 1.75rem;
        }

        .hero-btn-shop {
            display: inline-block;
            padding: 12px 28px;
            background: linear-gradient(90deg, #293868 0%, #031556 100%);
            border: none;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 0;
            box-shadow: 0 8px 24px rgba(3, 21, 86, 0.25);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hero-btn-shop:hover {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(26, 35, 126, 0.45);
        }

        .hero-img {
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            height: 94%;
            max-height: 720px;
            z-index: 2;
            object-fit: contain;
            pointer-events: none;
        }

        .hero-floating-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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
            top: 22%;
            left: 30%;
            background: rgba(255, 255, 255, 0.78);
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
        }

        .hero-floating-card.top-left .prod-title,
        .hero-floating-card.top-left .prod-price {
            color: #333;
        }

        .hero-floating-card.bottom-right {
            bottom: 22%;
            right: 8%;
            background: linear-gradient(135deg, #4f6fd8 0%, #2f3f8f 100%);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 18px 40px rgba(33, 40, 82, 0.35);
        }

        .hero-floating-card.bottom-right .prod-title,
        .hero-floating-card.bottom-right .prod-price {
            color: #fff;
        }

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

            .hero-inner {
                min-height: 560px;
                padding-bottom: 32px;
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
        .aiz-main-wrapper.aiz-axvero_theme~.floating-buttons-section {
            display: none !important;
        }

        /* 2. Shop by Category — premium vertical cards */
        .home-premium-categories {
            padding: 60px 0;
            background: #ffffff;
        }

        .categories-flex-row {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 15px;
            margin-bottom: -15px;
            -webkit-overflow-scrolling: touch;
        }

        .categories-flex-row::-webkit-scrollbar {
            display: none;
        }

        .category-flex-item {
            flex: 0 0 calc(100% / 7 - 11px);
            min-width: 160px;
            scroll-snap-align: start;
        }

        .premium-cat-card {
            position: relative;
            aspect-ratio: 3 / 4;
            overflow: hidden;
            cursor: pointer;
            background: #000;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .premium-cat-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .premium-cat-card:hover img {
            transform: scale(1.08);
        }

        .premium-cat-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 15px;
            text-align: center;
            transition: background 0.4s ease;
        }

        .premium-cat-card:hover .premium-cat-overlay {
            background: rgba(0, 0, 0, 0.6);
        }

        .premium-cat-title {
            color: #ffffff;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.3rem;
            font-weight: 500;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transition: transform 0.4s ease;
        }

        .premium-cat-card:hover .premium-cat-title {
            transform: translateY(-5px);
        }

        .premium-cat-btn {
            background: #ffffff;
            color: #1a1a2e;
            border: none;
            padding: 6px 14px;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.4s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .premium-cat-card:hover .premium-cat-btn {
            background: #1a1a2e;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 1200px) {
            .category-flex-item {
                flex: 0 0 calc(25% - 10px);
            }
        }

        @media (max-width: 768px) {
            .category-flex-item {
                flex: 0 0 calc(45% - 8px);
            }

            .premium-cat-title {
                font-size: 1.15rem;
                letter-spacing: 1.5px;
            }
        }

        /* 3. New Products — Trending Now UI */
        .axvero-new-products-section {
            padding: 20px 0 10px;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-new-products-section .trending-custom-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 1px solid #e5e5e5;
            padding-bottom: 15px;
        }

        .axvero-new-products-section .trending-title-area {
            display: flex;
            align-items: baseline;
            gap: 20px;
        }

        .axvero-new-products-section .trending-title-main {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
            margin: 0;
        }

        .axvero-new-products-section .trending-view-all {
            font-size: 1rem;
            font-weight: 700;
            color: #000;
            text-decoration: underline;
            text-underline-offset: 4px;
            transition: color 0.2s ease;
        }

        .axvero-new-products-section .trending-view-all:hover {
            color: #031556;
        }

        .axvero-new-products-section .trending-nav-arrows {
            display: flex;
            gap: 12px;
        }

        .axvero-new-products-section .trending-nav-arrow {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .axvero-new-products-section .trending-nav-arrow.prev {
            background-color: #9fb3d9;
            color: #fff;
        }

        .axvero-new-products-section .trending-nav-arrow.next {
            background-color: #426cb4;
            color: #fff;
        }

        .axvero-new-products-section .trending-nav-arrow:hover {
            opacity: 0.9;
            transform: scale(1.05);
        }

        .axvero-new-products-slider {
            overflow: hidden;
        }

        .axvero-new-products-carousel .slick-arrow {
            display: none !important;
        }

        .axvero-new-products-carousel .carousel-box {
            padding: 0 8px;
        }

        .axvero-new-products-section .trending-product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            cursor: pointer;
        }

        .axvero-new-products-section .trending-card-img-wrapper {
            position: relative;
            width: 100%;
            aspect-ratio: 3 / 4;
            overflow: hidden;
            margin-bottom: 12px;
            background: #f7f7f7;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
        }

        .axvero-new-products-section .trending-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            transition: transform 0.4s ease;
        }

        .axvero-new-products-section .trending-product-card:hover .trending-card-img-wrapper img {
            transform: scale(1.03);
        }

        .axvero-new-products-section .trending-card-info {
            padding-top: 4px;
        }

        .axvero-new-products-section .trending-card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d2d44;
            margin: 0 0 4px 0;
            line-height: 1.3;
        }

        .axvero-new-products-section .trending-card-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 0.88rem;
            color: #718096;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .axvero-new-products-section .trending-card-price-box {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .axvero-new-products-section .trending-price-current {
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a1a2e;
            line-height: 1.2;
        }

        .axvero-new-products-section .trending-price-old {
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            color: #a0aec0;
            text-decoration: line-through;
            line-height: 1.2;
        }

        @media (max-width: 767.98px) {
            .axvero-new-products-section .trending-title-main {
                font-size: 1.6rem;
            }

            .axvero-new-products-section .trending-custom-header {
                margin-bottom: 24px;
            }
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

        .section-title span {
            color: #001f3f;
        }

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
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
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

        .product-info {
            padding: 15px;
        }

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
            0% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }

            45% {
                opacity: 0.35;
                transform: translateY(10px) scale(0.98);
            }

            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
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

        /* 5. Our Collection — New Collection UI */
        .axvero-our-collection {
            padding: 60px 0;
            background: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-our-collection .new-col-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
        }

        .axvero-our-collection .new-col-section-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #000000;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            position: relative;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .axvero-our-collection .new-col-section-title::after {
            content: '*';
            color: #4270c1;
            font-size: 2.5rem;
            position: absolute;
            top: -15px;
            right: -25px;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
        }

        .axvero-our-collection .new-col-all-collections-btn {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #2d2d44;
            padding: 12px 36px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            font-family: 'Playfair Display', Georgia, serif;
            white-space: nowrap;
        }

        .axvero-our-collection .new-col-all-collections-btn:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #000000;
        }

        .axvero-our-collection .new-col-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .axvero-our-collection .new-col-card {
            position: relative;
            border-radius: 0px;
            overflow: hidden;
            padding: 24px;
            transition: transform 0.4s cubic-bezier(0.165, 0.84, 0.44, 1), box-shadow 0.4s ease;
            min-height: 360px;
        }

        .axvero-our-collection .new-col-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .axvero-our-collection .new-col-card.bg-light-grey {
            background-color: #f4f4f4;
        }

        .axvero-our-collection .new-col-card.bg-red {
            background-color: #c53a33;
        }

        .axvero-our-collection .new-col-card.bg-dark-grey {
            background-color: #636466;
        }

        .axvero-our-collection .new-col-card.bg-offwhite {
            background-color: #f8f9fa;
        }

        .axvero-our-collection .new-col-card-inner {
            position: relative;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .axvero-our-collection .new-col-img-box {
            width: 100%;
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
        }

        .axvero-our-collection .new-col-img-box img {
            max-height: 88%;
            max-width: 88%;
            object-fit: contain;
        }

        .axvero-our-collection .new-col-info-box {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            gap: 14px;
        }

        .axvero-our-collection .new-col-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.15rem;
            font-weight: 600;
            margin: 0;
            color: #000000;
            line-height: 1.25;
        }

        .axvero-our-collection .new-col-plus-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: rgba(255, 255, 255, 0.92);
            color: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.25s ease, background 0.25s ease;
            flex-shrink: 0;
            text-decoration: none;
        }

        .axvero-our-collection .new-col-plus-btn:hover {
            transform: scale(1.06);
            background: #ffffff;
            color: #031556;
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .axvero-our-collection .new-col-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 575.98px) {
            .axvero-our-collection .new-col-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 14px;
            }

            .axvero-our-collection .new-col-grid {
                grid-template-columns: 1fr;
            }

            .axvero-our-collection .new-col-section-title {
                font-size: 1.6rem;
            }

            .axvero-our-collection .new-col-section-title::after {
                right: -18px;
                top: -12px;
                font-size: 2.1rem;
            }
        }

        /* 6. Offer Banner (Get 50% Off) */
        .axvero-offer-banner {
            background-color: #faf9f6;
            padding: 60px 0;
            overflow: hidden;
            position: relative;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-offer-banner .offer-layout-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .axvero-offer-banner .offer-images-left-side {
            display: flex;
            align-items: center;
            gap: 20px;
            flex: 0 0 35%;
            min-width: 320px;
        }

        .axvero-offer-banner .offer-images-right-side {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 0 0 35%;
            min-width: 320px;
        }

        .axvero-offer-banner .offer-center-side {
            flex: 1;
            text-align: center;
            padding: 0 20px;
            z-index: 5;
        }

        .axvero-offer-banner .offer-center-title {
            font-size: 2.6rem;
            font-weight: 850;
            color: #000000;
            margin-bottom: 12px;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-offer-banner .offer-center-subtitle {
            font-size: 0.95rem;
            color: #2d2d44;
            line-height: 1.6;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .axvero-offer-banner .offer-center-btn {
            display: inline-block;
            background: #ffffff;
            color: #000000;
            border: 1px solid #cbd5e1;
            padding: 10px 48px;
            font-size: 0.88rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-radius: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            text-decoration: none;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .axvero-offer-banner .offer-center-btn:hover {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .axvero-offer-banner .offer-img-float {
            transition: transform 0.4s ease;
            flex: 1;
        }

        .axvero-offer-banner .offer-img-float img {
            width: 100%;
            height: auto;
            object-fit: contain;
            mix-blend-mode: multiply;
            display: block;
        }

        .axvero-offer-banner .offer-images-right-side .offer-img-float:nth-child(1) {
            transform: scale(0.95) rotate(-3deg);
        }

        .axvero-offer-banner .offer-images-right-side .offer-img-float:nth-child(2) {
            transform: scale(1) rotate(0deg);
            margin-left: -20px;
        }

        .axvero-offer-banner .offer-images-right-side .offer-img-float:nth-child(3) {
            transform: scale(0.95) rotate(3deg);
            margin-left: -20px;
        }

        .axvero-offer-banner .offer-layout-row:hover .offer-img-float {
            transform: translateY(-5px);
        }

        @media (max-width: 991px) {

            .axvero-offer-banner .offer-images-left-side,
            .axvero-offer-banner .offer-images-right-side {
                display: none !important;
            }

            .axvero-offer-banner .offer-center-side {
                padding: 40px 0;
            }
        }

        /* 7. Product Highlights (replaces Sweet Spring + Best Products) */
        .axvero-highlights-fullbleed {
            width: 100vw;
            max-width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
            overflow-x: clip;
        }

        .axvero-highlights-custom-section {
            padding: 80px 0;
            background: linear-gradient(180deg, #ebf3f9 0%, #ffffff 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-highlights-custom-header {
            margin-bottom: 50px;
            position: relative;
            display: inline-block;
        }

        .axvero-highlights-sparkle {
            font-size: 2.8rem;
            color: #4270c1;
            line-height: 1;
            margin-bottom: 5px;
            display: block;
        }

        .axvero-highlights-custom-title {
            font-family: 'Caveat', cursive;
            font-size: 3.6rem;
            font-weight: 700;
            color: #000000;
            margin: 0 0 10px 0;
            line-height: 1.1;
        }

        .axvero-highlights-custom-subtitle {
            font-size: 0.95rem;
            color: #a0aec0;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-highlights-arch-row {
            display: flex;
            align-items: flex-end;
            justify-content: center;
            gap: 20px;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 20px;
            overflow: visible;
        }

        .axvero-arch-card {
            flex: 1;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .axvero-arch-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
            transition: transform 0.6s ease;
        }

        .axvero-arch-card:hover img {
            transform: scale(1.06);
        }

        .axvero-arch-card-1 {
            height: 300px;
            border-radius: 150px 150px 0 0;
        }

        .axvero-arch-card-2 {
            height: 420px;
            border-radius: 210px 210px 0 0;
        }

        .axvero-arch-card-3 {
            height: 540px;
            border-radius: 270px 270px 0 0;
        }

        .axvero-arch-card-4 {
            height: 420px;
            border-radius: 210px 210px 0 0;
        }

        .axvero-arch-card-5 {
            height: 300px;
            border-radius: 150px 150px 0 0;
        }

        .axvero-arch-card-6 {
            height: 380px;
            border-radius: 190px 190px 0 0;
        }

        .axvero-arch-card-7 {
            height: 300px;
            border-radius: 150px 150px 0 0;
        }

        .axvero-arch-card-8 {
            height: 380px;
            border-radius: 190px 190px 0 0;
        }

        .axvero-arch-card-9 {
            height: 300px;
            border-radius: 150px 150px 0 0;
        }

        /* Stagger (top/bottom) like reference */
        .axvero-arch-card-1 {
            margin-bottom: 58px;
        }

        .axvero-arch-card-2 {
            margin-bottom: 18px;
        }

        .axvero-arch-card-3 {
            margin-bottom: 0;
        }

        .axvero-arch-card-4 {
            margin-bottom: 18px;
        }

        .axvero-arch-card-5 {
            margin-bottom: 58px;
        }

        .axvero-arch-card-6 {
            margin-bottom: 34px;
        }

        .axvero-arch-card-7 {
            margin-bottom: 58px;
        }

        .axvero-arch-card-8 {
            margin-bottom: 34px;
        }

        .axvero-arch-card-9 {
            margin-bottom: 58px;
        }

        /* Slight edge peek on the first card */
        .axvero-arch-card-1 {
            margin-left: -80px;
        }

        .axvero-arch-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .axvero-highlights-arch-row {
                gap: 10px;
                padding: 0 15px;
            }

            .axvero-arch-card-1,
            .axvero-arch-card-5,
            .axvero-arch-card-7,
            .axvero-arch-card-9 {
                height: 190px;
            }

            .axvero-arch-card-2,
            .axvero-arch-card-4,
            .axvero-arch-card-6,
            .axvero-arch-card-8 {
                height: 250px;
            }

            .axvero-arch-card-3 {
                height: 320px;
            }

            .axvero-highlights-custom-title {
                font-size: 2.8rem;
            }

            .axvero-arch-card-1 {
                margin-left: -24px;
                margin-bottom: 34px;
            }

            .axvero-arch-card-5,
            .axvero-arch-card-7,
            .axvero-arch-card-9 {
                margin-bottom: 34px;
            }

            .axvero-arch-card-2,
            .axvero-arch-card-4,
            .axvero-arch-card-6,
            .axvero-arch-card-8 {
                margin-bottom: 12px;
            }
        }

        @media (max-width: 576px) {

            .axvero-arch-card-1,
            .axvero-arch-card-5,
            .axvero-arch-card-7,
            .axvero-arch-card-9 {
                display: none !important;
            }
        }

        /* 7. Sweet Spring Lookbook */
        .arched-img {
            border-radius: 150px 150px 0 0;
            overflow: hidden;
            background: #f1f1f1;
            height: 100%;
        }

        .arched-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* 9. Trending Now (replaces Latest Stories) */
        .axvero-trending-section {
            padding: 70px 0;
            background: #ffffff;
            font-family: 'Poppins', sans-serif;
        }

        .axvero-trending-custom-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 36px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
        }

        .axvero-trending-title-area {
            display: flex;
            align-items: baseline;
            gap: 20px;
        }

        .axvero-trending-title-main {
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
            margin: 0;
        }

        .axvero-trending-view-all {
            font-size: 1rem;
            font-weight: 700;
            color: #000;
            text-decoration: underline;
            text-underline-offset: 4px;
        }

        .axvero-trending-nav-arrows {
            display: flex;
            gap: 12px;
        }

        .axvero-trending-nav-arrow {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 1.1rem;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .axvero-trending-nav-arrow.prev {
            background-color: #9fb3d9;
            color: #fff;
        }

        .axvero-trending-nav-arrow.next {
            background-color: #426cb4;
            color: #fff;
        }

        .axvero-trending-layout-container {
            display: grid;
            grid-template-columns: 180px 1fr;
            gap: 40px;
        }

        .axvero-trending-sidebar-tabs {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .axvero-trending-tab-btn {
            width: 100%;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 700;
            text-align: center;
            text-transform: uppercase;
            cursor: pointer;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #000;
        }

        .axvero-trending-tab-btn.active {
            background-color: #003366;
            color: #fff;
            border-color: #003366;
        }

        .axvero-trending-slider-wrapper {
            overflow: hidden;
            position: relative;
        }

        .axvero-trending-products-track {
            display: flex;
            gap: 24px;
            transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .axvero-trending-product-card {
            flex: 0 0 calc((100% - 48px) / 3);
            display: flex;
            flex-direction: column;
            text-decoration: none !important;
            color: inherit;
        }

        .axvero-trending-card-img-wrapper {
            width: 100%;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            margin-bottom: 16px;
            background: #f3f4f6;
        }

        .axvero-trending-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }

        .axvero-trending-product-card:hover .axvero-trending-card-img-wrapper img {
            transform: scale(1.05);
        }

        .axvero-trending-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #000;
            margin: 0 0 6px 0;
            line-height: 1.2;
        }

        .axvero-trending-card-desc {
            font-size: 0.9rem;
            color: #555;
            margin: 0 0 12px 0;
            line-height: 1.4;
        }

        .axvero-trending-card-shop-now {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #000;
            letter-spacing: 1px;
            margin-top: auto;
            display: inline-block;
        }

        @media (max-width: 991px) {
            .axvero-trending-product-card {
                flex: 0 0 calc((100% - 24px) / 2);
            }

            .axvero-trending-layout-container {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .axvero-trending-sidebar-tabs {
                flex-direction: row;
                justify-content: center;
            }

            .axvero-trending-tab-btn {
                width: auto;
                min-width: 120px;
            }
        }

        @media (max-width: 576px) {
            .axvero-trending-product-card {
                flex: 0 0 100%;
            }
        }

        /* 10. Categories (plants style) */
        .axvero-categories-plants-section {
            background-color: #fff;
            padding-top: 80px;
            position: relative;
        }

        .axvero-categories-plants-header {
            margin-bottom: 40px;
        }

        .axvero-categories-plants-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 800;
            text-transform: uppercase;
            color: #000;
            letter-spacing: 1px;
            margin: 0 0 8px 0;
        }

        .axvero-categories-plants-subtitle {
            font-size: 1rem;
            color: #888;
            margin: 0;
        }

        .axvero-categories-plants-body {
            background-color: #063462;
            padding: 100px 0;
            margin-top: 140px;
            position: relative;
        }

        .axvero-plant-category-card {
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .axvero-plant-card-img-wrapper {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            margin-bottom: 20px;
            background-color: #f0f0f0;
        }

        .axvero-plant-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.45s ease;
        }

        .axvero-plant-category-card:hover .axvero-plant-card-img-wrapper img {
            transform: scale(1.05);
        }

        .axvero-plant-category-card.side-card {
            margin-top: -240px;
        }

        .axvero-plant-category-card.side-card .axvero-plant-card-img-wrapper {
            height: 480px;
        }

        .axvero-plant-category-card.center-card {
            margin-top: -140px;
        }

        .axvero-plant-category-card.center-card .axvero-plant-card-img-wrapper {
            height: 380px;
        }

        .axvero-plant-card-title {
            font-family: 'Poppins', sans-serif;
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .axvero-plant-card-desc {
            font-family: 'Poppins', sans-serif;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0 0 20px 0;
            max-width: 280px;
            margin-left: auto;
            margin-right: auto;
        }

        .axvero-btn-plant-explore {
            background-color: #fff;
            color: #063462;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .axvero-btn-plant-explore:hover {
            background-color: #f0f5fa;
            color: #063462;
            transform: translateY(-2px);
            text-decoration: none;
        }

        @media (max-width: 991.98px) {
            .axvero-categories-plants-body {
                margin-top: 40px;
                padding: 50px 0;
            }

            .axvero-plant-category-card.side-card,
            .axvero-plant-category-card.center-card {
                margin-top: 0;
            }

            .axvero-plant-card-img-wrapper {
                height: 350px !important;
            }
        }

        /* 11. Home Decor (xampp UI) */
        .axvero-home-decor-section {
            padding: 80px 0 30px 0;
            background-color: #fff;
            font-family: 'Poppins', sans-serif;
        }
        .axvero-home-decor-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .axvero-home-decor-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #000;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .axvero-home-decor-sparkle {
            color: #4270c1;
            font-size: 2.2rem;
            line-height: 1;
        }
        .axvero-home-decor-all-btn {
            font-size: 0.9rem;
            font-weight: 600;
            color: #2a254b;
            border: 1px solid #e5e7eb;
            border-radius: 999px;
            padding: 10px 24px;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            white-space: nowrap;
        }
        .axvero-home-decor-all-btn:hover {
            background-color: #f5f5f5;
            border-color: #2a254b;
            color: #2a254b;
            text-decoration: none;
        }
        .axvero-home-decor-products-bg {
            background-color: #f6f6f6;
            padding: 30px;
            border-radius: 8px;
        }
        .axvero-home-decor-product-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            text-decoration: none;
            color: inherit;
        }
        .axvero-home-decor-img-container {
            width: 100%;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            margin-bottom: 16px;
            border-radius: 4px;
            background: #fff;
        }
        .axvero-home-decor-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
            display: block;
        }
        .axvero-home-decor-product-card:hover .axvero-home-decor-img-container img {
            transform: scale(1.05);
        }
        .axvero-home-decor-product-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #000;
            margin: 0 0 4px 0;
            line-height: 1.25;
        }
        .axvero-home-decor-product-price {
            font-size: 0.95rem;
            color: #555;
            margin: 0;
        }

        /* 10. Vertical Plant Cards */
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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
        $hero_img =
            $hero_img_setting != null && $hero_img_setting != ''
                ? uploaded_asset($hero_img_setting)
                : static_asset('assets/img/demo/lady.png');
        $hero_title = get_setting('axvero_hero_title', null, $lang) ?: 'New Fashion';
        $hero_subtitle =
            get_setting('axvero_hero_subtitle', null, $lang) ?:
            'Elevate your wardrobe and make a statement with our fashion forward pieces. Step into a realm of endless possibilities and discover the perfect outfit that speaks to your unique style. Get ready to turn heads and exude confidence with every step you take. Your fashion journey starts here.';
        $hero_btn_text = get_setting('axvero_hero_btn_text', null, $lang) ?: 'SHOP NOW';
        $hero_btn_link = get_setting('axvero_hero_btn_link', null, $lang) ?: route('search');
        $latest_hero_products = filter_products(
            \App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'),
        )
            ->limit(2)
            ->get();
    @endphp
    <div class="axvero-hero-fullbleed mb-5">
        <div class="hero-section">
            <div class="hero-huge-text">{{ $hero_title }}</div>

            <img src="{{ $hero_img }}" class="hero-img d-none d-md-block" alt="Fashion Model"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/lady.png') }}';">

            <div class="container-fluid px-4 px-lg-5 hero-inner">
                <div class="row w-100">
                    <div class="col-md-5 col-lg-4">
                        <div class="hero-text">
                            <p class="hero-subtitle mb-0">{{ $hero_subtitle }}</p>
                            <a href="{{ $hero_btn_link }}" class="hero-btn-shop mt-4">{{ $hero_btn_text }}</a>
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($latest_hero_products[0]))
                <a href="{{ route('product', $latest_hero_products[0]->slug) }}"
                    class="hero-floating-card top-left text-decoration-none d-none d-md-flex">
                    <img src="{{ uploaded_asset($latest_hero_products[0]->thumbnail_img) }}" class="prod-img"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <div class="prod-info">
                        <div class="prod-title">{{ $latest_hero_products[0]->getTranslation('name') }}</div>
                        <div class="prod-price">{{ home_discounted_base_price($latest_hero_products[0]) }}</div>
                        <div class="rating-stars" style="color: #999; font-size: 10px;">
                            <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i
                                class="las la-star"></i><i class="las la-star"></i>
                        </div>
                    </div>
                    <div class="add-btn"><i class="las la-plus"></i></div>
                </a>
            @endif

            @if (isset($latest_hero_products[1]))
                <a href="{{ route('product', $latest_hero_products[1]->slug) }}"
                    class="hero-floating-card bottom-right text-decoration-none d-none d-md-flex">
                    <img src="{{ uploaded_asset($latest_hero_products[1]->thumbnail_img) }}" class="prod-img"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <div class="prod-info">
                        <div class="prod-title">{{ $latest_hero_products[1]->getTranslation('name') }}</div>
                        <div class="prod-price">{{ home_discounted_base_price($latest_hero_products[1]) }}</div>
                        <div class="rating-stars" style="color: rgba(255,255,255,0.75); font-size: 10px;">
                            <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i
                                class="las la-star"></i><i class="las la-star"></i>
                        </div>
                    </div>
                    <div class="add-btn"><i class="las la-plus"></i></div>
                </a>
            @endif
        </div>
    </div>

    <!-- 2. Shop by Category -->
    <section class="home-premium-categories mb-5">
        <div class="container-fluid px-3 px-lg-5">
            <div class="categories-flex-row">
                @foreach (get_level_zero_categories()->take(7) as $category)
                    <div class="category-flex-item">
                        <a href="{{ route('products.category', $category->slug) }}" class="d-block text-decoration-none">
                            <div class="premium-cat-card">
                                @if ($category->banner)
                                    <img src="{{ uploaded_asset($category->banner) }}"
                                        alt="{{ $category->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                                @elseif (isset($category->catIcon->file_name))
                                    <img src="{{ my_asset($category->catIcon->file_name) }}"
                                        alt="{{ $category->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                                @else
                                    <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}"
                                        alt="{{ $category->getTranslation('name') }}">
                                @endif
                                <div class="premium-cat-overlay">
                                    <h3 class="premium-cat-title">{{ strtoupper($category->getTranslation('name')) }}</h3>
                                    <span class="premium-cat-btn d-inline-block">Shop Now</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 3. New Products -->
    <section class="axvero-new-products-section mb-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="trending-custom-header">
                <div class="trending-title-area">
                    <h2 class="trending-title-main">NEW PRODUCTS</h2>
                    <a href="{{ route('search') }}" class="trending-view-all">View All</a>
                </div>
                <div class="trending-nav-arrows">
                    <button type="button" class="trending-nav-arrow prev" id="axveroNewProductsPrev" aria-label="Previous">
                        <i class="las la-angle-left"></i>
                    </button>
                    <button type="button" class="trending-nav-arrow next" id="axveroNewProductsNext" aria-label="Next">
                        <i class="las la-angle-right"></i>
                    </button>
                </div>
            </div>

            <div class="axvero-new-products-slider">
                <div class="aiz-carousel gutters-10 axvero-new-products-carousel" data-items="4" data-xl-items="4"
                    data-lg-items="4" data-md-items="2" data-sm-items="2" data-xs-items="1" data-arrows="false"
                    data-dots="false">
                    @php
                        $new_products = filter_products(
                            \App\Models\Product::where('published', 1)
                                ->with(['brand'])
                                ->orderBy('created_at', 'desc'),
                        )
                            ->limit(10)
                            ->get();
                    @endphp
                    @foreach ($new_products as $product)
                        @php
                            $product_brand = $product->brand ? $product->brand->getTranslation('name') : null;
                        @endphp
                        <div class="carousel-box">
                            <a href="{{ route('product', $product->slug) }}"
                                class="trending-product-card text-decoration-none">
                                <div class="trending-card-img-wrapper">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                                <div class="trending-card-info">
                                    <h3 class="trending-card-title text-truncate">{{ $product->getTranslation('name') }}
                                    </h3>
                                    @if ($product_brand)
                                        <p class="trending-card-brand text-truncate mb-0">{{ $product_brand }}</p>
                                    @endif
                                    <div class="trending-card-price-box">
                                        <span
                                            class="trending-price-current">{{ home_discounted_base_price($product) }}</span>
                                        @if (home_base_price($product) != home_discounted_base_price($product))
                                            <span class="trending-price-old">{{ home_base_price($product) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Latest Edition Card -->
    @php
        $latest_deck_products = filter_products(
            \App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'),
        )
            ->limit(3)
            ->get();

        $latest_title = get_setting('axvero_latest_title', null, $lang);
        $latest_subtitle = get_setting('axvero_latest_subtitle', null, $lang);
        $latest_price = get_setting('axvero_latest_price', null, $lang);
        $latest_link = get_setting('axvero_latest_link', null, $lang);
        $latest_product_image_setting = get_setting('axvero_latest_product_image', null, $lang);

        $deck_products = [];
        foreach ($latest_deck_products as $index => $product) {
            $deck_products[] = [
                'title' => $index === 0 && $latest_title ? $latest_title : $product->getTranslation('name'),
                'subtitle' => $latest_subtitle ?: 'Unwind in style with Lazy Head T-shirts – comfort meets cool.',
                'price' => $index === 0 && $latest_price ? $latest_price : home_discounted_base_price($product),
                'link' => $index === 0 && $latest_link ? $latest_link : route('product', $product->slug),
                'image' =>
                    $index === 0 && $latest_product_image_setting
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
                <div class="deck-container" id="axveroLatestDeck" role="button" tabindex="0"
                    aria-label="View next latest product">
                    <div class="deck-card deck-card-back deck-card-back-1"></div>
                    <div class="deck-card deck-card-back deck-card-back-2"></div>
                    <div class="deck-card deck-card-front" id="axveroLatestDeckFront">
                        <div class="deck-card-content">
                            <div class="deck-product-img">
                                <img id="axveroLatestDeckImage" src="{{ $deck_front['image'] }}"
                                    alt="{{ $deck_front['title'] }}"
                                    onerror="this.onerror=null;this.src='{{ $placeholder_img }}';">
                            </div>
                            <div class="deck-product-details">
                                <h3 class="deck-product-title" id="axveroLatestDeckTitle">{{ $deck_front['title'] }}</h3>
                                <p class="deck-product-desc mb-0" id="axveroLatestDeckSubtitle">
                                    {{ $deck_front['subtitle'] }}</p>
                                <div class="deck-product-price" id="axveroLatestDeckPrice">{{ $deck_front['price'] }}
                                </div>
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
    <section class="axvero-our-collection mb-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="new-col-header">
                <div class="new-col-header-left">
                    <h2 class="new-col-section-title">Our Collection</h2>
                </div>
                <div class="new-col-header-right">
                    <a href="{{ route('search') }}" class="new-col-all-collections-btn">All Collections</a>
                </div>
            </div>

            @php
                $collections = get_level_zero_categories()->take(4);
                $card_bg = ['bg-light-grey', 'bg-red', 'bg-dark-grey', 'bg-offwhite'];
            @endphp

            <div class="new-col-grid">
                @foreach ($collections as $key => $collection)
                    <div class="new-col-card {{ $card_bg[$key % 4] }}">
                        <div class="new-col-card-inner">
                            <a href="{{ route('products.category', $collection->slug) }}"
                                class="d-block text-decoration-none">
                                <div class="new-col-img-box">
                                    <img src="{{ uploaded_asset($collection->banner) }}"
                                        alt="{{ $collection->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}';">
                                </div>
                            </a>
                            <div class="new-col-info-box">
                                <div class="new-col-text-side">
                                    <h3 class="new-col-title text-truncate">{{ $collection->getTranslation('name') }}</h3>
                                </div>
                                <a href="{{ route('products.category', $collection->slug) }}" class="new-col-plus-btn"
                                    aria-label="Open category">
                                    <i class="las la-plus"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 6. Offer Banner -->
    @php
        $offer_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))
            ->limit(5)
            ->get();

        $offer_title = get_setting('axvero_offer_title', null, $lang) ?: 'Get 50% Off';
        $offer_subtitle =
            get_setting('axvero_offer_subtitle', null, $lang) ?:
            'for all new product purchases<br>min. purchase Rs. 350.000';
        $offer_btn_text = get_setting('axvero_offer_btn_text', null, $lang) ?: 'SHOP NOW';
        $offer_btn_link = get_setting('axvero_offer_btn_link', null, $lang) ?: route('search');
        $offer_placeholder = static_asset('assets/img/placeholder.jpg');
    @endphp
    <section class="axvero-offer-banner mb-5">
        <div class="container-fluid px-4 px-lg-5">
            <div class="offer-layout-row">
                <!-- Left side 2 images -->
                <div class="offer-images-left-side">
                    @for ($i = 0; $i < 2; $i++)
                        @if (isset($offer_products[$i]))
                            <a class="offer-img-float d-block" href="{{ route('product', $offer_products[$i]->slug) }}">
                                <img src="{{ uploaded_asset($offer_products[$i]->thumbnail_img) }}"
                                    alt="{{ $offer_products[$i]->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ $offer_placeholder }}';">
                            </a>
                        @endif
                    @endfor
                </div>

                <!-- Center content -->
                <div class="offer-center-side">
                    <h2 class="offer-center-title">{{ $offer_title }}</h2>
                    <p class="offer-center-subtitle mb-0">{!! $offer_subtitle !!}</p>
                    <a href="{{ $offer_btn_link }}" class="offer-center-btn mt-4">{{ $offer_btn_text }}</a>
                </div>

                <!-- Right side 3 images -->
                <div class="offer-images-right-side">
                    @for ($i = 2; $i < 5; $i++)
                        @if (isset($offer_products[$i]))
                            <a class="offer-img-float d-block" href="{{ route('product', $offer_products[$i]->slug) }}">
                                <img src="{{ uploaded_asset($offer_products[$i]->thumbnail_img) }}"
                                    alt="{{ $offer_products[$i]->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ $offer_placeholder }}';">
                            </a>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </section>



    <!-- 7. Product Highlights -->
    @php
        $highlight_count = 7;
        $highlight_products = filter_products(
            \App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'),
        )
            ->limit($highlight_count)
            ->get();
        $highlight_placeholder = static_asset('assets/img/placeholder.jpg');
    @endphp
    <div class="axvero-highlights-fullbleed mb-5">
        <section class="axvero-highlights-custom-section">
            <div class="container-fluid px-0">
                <div class="axvero-highlights-custom-header">
                    <span class="axvero-highlights-sparkle">✳</span>
                    <h2 class="axvero-highlights-custom-title">Product Highlights</h2>
                    <p class="axvero-highlights-custom-subtitle">Discover the perfect outfit that speaks to your unique
                        style.</p>
                </div>

                <div class="axvero-highlights-arch-row">
                    @for ($i = 0; $i < $highlight_count; $i++)
                        @if (isset($highlight_products[$i]))
                            <a href="{{ route('product', $highlight_products[$i]->slug) }}"
                                class="axvero-arch-card axvero-arch-card-{{ $i + 1 }} d-block">
                                <img src="{{ uploaded_asset($highlight_products[$i]->thumbnail_img) }}"
                                    alt="{{ $highlight_products[$i]->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ $highlight_placeholder }}';">
                            </a>
                        @else
                            <div class="axvero-arch-card axvero-arch-card-{{ $i + 1 }} d-block">
                                <img src="{{ $highlight_placeholder }}" alt="Highlight placeholder">
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
        </section>
    </div>

    <!-- 9. Trending Now -->
    @php
        $men_products = filter_products(
            \App\Models\Product::where('published', 1)
                ->whereHas('categories.category_translations', function ($query) {
                    $query->where('name', 'like', '%men%');
                })
                ->orderBy('num_of_sale', 'desc'),
        )
            ->limit(8)
            ->get();

        $women_products = filter_products(
            \App\Models\Product::where('published', 1)
                ->whereHas('categories.category_translations', function ($query) {
                    $query->where('name', 'like', '%women%');
                })
                ->orderBy('num_of_sale', 'desc'),
        )
            ->limit(8)
            ->get();

        if ($men_products->count() == 0) {
            $men_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))
                ->limit(8)
                ->get();
        }
        if ($women_products->count() == 0) {
            $women_products = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))
                ->limit(8)
                ->get();
        }
    @endphp
    <section class="axvero-trending-section mb-5">
        <div class="container-fluid px-4 px-md-5">
            <div class="axvero-trending-custom-header">
                <div class="axvero-trending-title-area">
                    <h2 class="axvero-trending-title-main">Trending Now</h2>
                    <a href="{{ route('search') }}" class="axvero-trending-view-all">View All</a>
                </div>
                <div class="axvero-trending-nav-arrows">
                    <button class="axvero-trending-nav-arrow prev" id="axveroTrendingPrevBtn" aria-label="Previous">
                        <i class="las la-angle-left"></i>
                    </button>
                    <button class="axvero-trending-nav-arrow next" id="axveroTrendingNextBtn" aria-label="Next">
                        <i class="las la-angle-right"></i>
                    </button>
                </div>
            </div>

            <div class="axvero-trending-layout-container">
                <div class="axvero-trending-sidebar-tabs">
                    <button class="axvero-trending-tab-btn active" data-category="men">Men</button>
                    <button class="axvero-trending-tab-btn" data-category="women">Women</button>
                </div>

                <div class="axvero-trending-slider-wrapper">
                    <div class="axvero-trending-products-track" id="axveroTrendingProductsTrack">
                        @foreach ($men_products as $product)
                            <a href="{{ route('product', $product->slug) }}"
                                class="axvero-trending-product-card men-item">
                                <div class="axvero-trending-card-img-wrapper">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                                </div>
                                <h3 class="axvero-trending-card-title">
                                    {{ \Illuminate\Support\Str::limit($product->getTranslation('name'), 36) }}</h3>
                                <p class="axvero-trending-card-desc">{{ home_discounted_base_price($product) }}</p>
                                <span class="axvero-trending-card-shop-now">Shop Now</span>
                            </a>
                        @endforeach

                        @foreach ($women_products as $product)
                            <a href="{{ route('product', $product->slug) }}"
                                class="axvero-trending-product-card women-item" style="display: none;">
                                <div class="axvero-trending-card-img-wrapper">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                                </div>
                                <h3 class="axvero-trending-card-title">
                                    {{ \Illuminate\Support\Str::limit($product->getTranslation('name'), 36) }}</h3>
                                <p class="axvero-trending-card-desc">{{ home_discounted_base_price($product) }}</p>
                                <span class="axvero-trending-card-shop-now">Shop Now</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 10. Categories -->
    @php
        $plants_categories = get_level_zero_categories()->take(3)->values();
    @endphp
    @if ($plants_categories->count() > 0)
        <section class="axvero-categories-plants-section mb-5">
            <div class="container-fluid px-0">
                <div class="axvero-categories-plants-header text-center">
                    <h2 class="axvero-categories-plants-title">CATEGORIES</h2>
                    <p class="axvero-categories-plants-subtitle">Find what you are looking for</p>
                </div>

                <div class="axvero-categories-plants-body">
                    <div class="container">
                        <div class="row align-items-end justify-content-center g-4">
                            @foreach ($plants_categories as $idx => $category)
                                @php
                                    $cardClass = $idx === 1 ? 'center-card' : 'side-card';
                                    $banner = $category->banner
                                        ? uploaded_asset($category->banner)
                                        : static_asset('assets/img/placeholder.jpg');
                                    $catLink = route('products.category', $category->slug);
                                @endphp
                                <div class="col-md-4">
                                    <div class="axvero-plant-category-card {{ $cardClass }} text-center">
                                        <a href="{{ $catLink }}" class="d-block text-decoration-none">
                                            <div class="axvero-plant-card-img-wrapper">
                                                <img src="{{ $banner }}"
                                                    alt="{{ $category->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                                            </div>
                                        </a>
                                        <h3 class="axvero-plant-card-title">{{ $category->getTranslation('name') }}</h3>
                                        @if ($idx === 1)
                                            <p class="axvero-plant-card-desc">Explore curated collections for every room
                                                and style.</p>
                                            <a href="{{ $catLink }}" class="axvero-btn-plant-explore">
                                                Explore <i class="las la-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- <!-- 10. Square Categories (hidden as requested) -->
    <div class="container mb-5">
        <div class="section-title">
            <span>LATEST TRENDS</span>
            <a href="{{ route('search') }}" class="btn btn-outline-dark btn-sm rounded-0">DISCOVER</a>
        </div>
        <div class="row gutters-10">
            @php
                $trending_categories = \App\Models\Category::where('featured', 1)->limit(4)->get();
                if ($trending_categories->count() == 0) {
                    $trending_categories = get_level_zero_categories()->take(4);
                }
            @endphp
            @foreach ($trending_categories as $category)
                <div class="col-6 col-md-3 mb-3">
                    <a href="{{ route('products.category', $category->slug) }}"
                        class="d-block text-center text-dark text-decoration-none">
                        <div class="square-cat mb-2">
                            <img src="{{ uploaded_asset($category->banner) }}"
                                style="max-height: 80%; max-width: 80%; object-fit: contain;"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                        <div class="fw-700 text-truncate">{{ $category->getTranslation('name') }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div> --}}

    <!-- 11. Dark Banner Section -->
    <div class="container mb-5">
        @php
            $banner_product = filter_products(\App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'))
                ->skip(4)
                ->first();
        @endphp
        <div class="dark-interior-banner rounded overflow-hidden">
            <div class="row no-gutters w-100">
                <div class="col-md-6 d-flex flex-column justify-content-center p-5">
                    <div class="fs-12 text-uppercase mb-2" style="letter-spacing: 2px;">New Collection</div>
                    <h2 class="fw-800 mb-4" style="font-family: 'Playfair Display', serif;">
                        {{ $banner_product ? $banner_product->getTranslation('name') : 'ELEVATE YOUR SPACE' }}</h2>
                    <p class="mb-4 opacity-70">Discover our premium new collections today.</p>
                    <div>
                        <a href="{{ $banner_product ? route('product', $banner_product->slug) : route('search') }}"
                            class="btn btn-outline-light text-white rounded-0 px-4">EXPLORE</a>
                    </div>
                </div>
                <div class="col-md-6 bg-white d-flex align-items-center justify-content-center">
                    @if ($banner_product)
                        <img src="{{ uploaded_asset($banner_product->thumbnail_img) }}"
                            class="w-100 h-100 object-fit-contain p-4" style="min-height: 350px; max-height: 400px;"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    @else
                        <img src="{{ static_asset('assets/img/demo/demo_thumb_automobile.png') }}"
                            class="w-100 h-100 object-fit-cover" style="min-height: 350px;">
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
                $new_arrivals = filter_products(
                    \App\Models\Product::where('published', 1)->orderBy('created_at', 'desc'),
                )
                    ->limit(4)
                    ->get();
            @endphp
            @foreach ($new_arrivals as $key => $product)
                <div class="col-6 col-md-3 mb-3">
                    <a href="{{ route('product', $product->slug) }}"
                        class="d-block promo-square d-flex flex-column align-items-center justify-content-center text-decoration-none"
                        style="background-color: {{ $promo_bg[$key % 4] }}; height: 250px;">
                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                            style="max-height: 60%; max-width: 80%; object-fit: contain;"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        <div class="mt-3 text-dark fw-600 text-truncate px-3 w-100 text-center">
                            {{ $product->getTranslation('name') }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 13. Lower Banners -->
    <div class="container mb-5">
        <div class="row gutters-10">
            <div class="col-md-12 mb-3">
                <div class="d-flex align-items-center justify-content-between p-4 px-md-5 rounded"
                    style="background: linear-gradient(to right, #ffecd2 0%, #fcb69f 100%);">
                    <div>
                        <div class="btn btn-warning btn-sm rounded-0 fw-700 mb-2">NEW OFFERS</div>
                        <h2 class="fw-800 text-dark mb-0" style="font-family: 'Playfair Display', serif;">Special Offers
                        </h2>
                    </div>
                    <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}"
                        style="max-height: 150px; object-fit: contain;">
                    <a href="{{ route('search') }}"
                        class="btn btn-light rounded-0 fw-700 shadow-sm d-none d-md-inline-block">SHOP NOW</a>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="d-flex align-items-center justify-content-center p-5 rounded position-relative overflow-hidden"
                    style="background: #252342; min-height: 300px;">
                    <div class="position-absolute z-1"
                        style="font-size: 15rem; font-weight: 900; color: rgba(255,255,255,0.05); top: -20px; text-align: center; width: 100%;">
                        70</div>
                    <div class="position-relative z-2 text-center text-white w-100">
                        <img src="{{ static_asset('assets/img/demo/demo_thumb_fashion.png') }}" class="mx-auto"
                            style="height: 250px; object-fit: contain; position: absolute; bottom: -50px; left: 50%; transform: translateX(-50%); z-index: 3;">
                        <h2 class="fw-800 mb-0 position-relative z-4"
                            style="font-family: 'Playfair Display', serif; letter-spacing: 5px; color: rgba(255,255,255,0.8);">
                            UP TO 70% OFF</h2>
                        <h3 class="fw-400 position-relative z-4 text-uppercase mt-2" style="letter-spacing: 2px;">
                            OUTERWEAR</h3>
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
        (function() {
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

            deck.addEventListener('click', function(event) {
                if (event.target.closest('#axveroLatestDeckBtn')) return;
                rotateDeck();
            });

            deck.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    rotateDeck();
                }
            });
        })();
    </script>
    <script>
        (function() {
            const prevBtn = document.getElementById('axveroNewProductsPrev');
            const nextBtn = document.getElementById('axveroNewProductsNext');
            const carousel = document.querySelector('.axvero-new-products-carousel');
            if (!prevBtn || !nextBtn || !carousel || typeof jQuery === 'undefined') return;

            function moveCarousel(direction) {
                const $carousel = jQuery(carousel);
                if (!$carousel.hasClass('slick-initialized')) return;
                if (direction === 'prev') {
                    $carousel.slick('slickPrev');
                } else {
                    $carousel.slick('slickNext');
                }
            }

            prevBtn.addEventListener('click', function() {
                moveCarousel('prev');
            });
            nextBtn.addEventListener('click', function() {
                moveCarousel('next');
            });
        })();
    </script>
    <script>
        (function() {
            const track = document.getElementById('axveroTrendingProductsTrack');
            const prevBtn = document.getElementById('axveroTrendingPrevBtn');
            const nextBtn = document.getElementById('axveroTrendingNextBtn');
            const tabBtns = document.querySelectorAll('.axvero-trending-tab-btn');
            if (!track || !prevBtn || !nextBtn || !tabBtns.length) return;

            let currentCategory = 'men';
            let currentIndex = 0;

            function getVisibleCardsCount() {
                const width = window.innerWidth;
                if (width <= 576) return 1;
                if (width <= 991) return 2;
                return 3;
            }

            function updateSlider() {
                const items = track.querySelectorAll('.' + currentCategory + '-item');
                const visibleCount = getVisibleCardsCount();
                const maxIndex = Math.max(0, items.length - visibleCount);

                if (currentIndex > maxIndex) currentIndex = maxIndex;

                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                prevBtn.style.pointerEvents = currentIndex === 0 ? 'none' : 'auto';
                nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
                nextBtn.style.pointerEvents = currentIndex >= maxIndex ? 'none' : 'auto';

                if (items.length > 0) {
                    const cardWidth = items[0].getBoundingClientRect().width;
                    const gap = 24;
                    const shift = currentIndex * (cardWidth + gap);
                    track.style.transform = 'translateX(-' + shift + 'px)';
                } else {
                    track.style.transform = 'translateX(0)';
                }
            }

            function switchCategory(category) {
                currentCategory = category;
                currentIndex = 0;

                const allItems = track.querySelectorAll('.axvero-trending-product-card');
                allItems.forEach(function(item) {
                    if (item.classList.contains(category + '-item')) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });

                tabBtns.forEach(function(btn) {
                    btn.classList.toggle('active', btn.getAttribute('data-category') === category);
                });

                track.style.transform = 'translateX(0)';
                setTimeout(updateSlider, 50);
            }

            tabBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    switchCategory(this.getAttribute('data-category'));
                });
            });

            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', function() {
                const items = track.querySelectorAll('.' + currentCategory + '-item');
                const visibleCount = getVisibleCardsCount();
                const maxIndex = Math.max(0, items.length - visibleCount);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            });

            switchCategory('men');
            window.addEventListener('resize', updateSlider);
        })();
    </script>
@endsection
