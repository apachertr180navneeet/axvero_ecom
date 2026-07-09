@extends('frontend.layouts.app')

@section('content')
    <style>
        .all-brands-page {
            --primary: #000000;
        }
        .all-brands-page .btn-primary:hover {
            background-color: #000000 !important;
            border-color: #000000 !important;
        }
    </style>
    <div class="all-brands-page">
    <!-- Breadcrumb -->
    <section class="pt-4 mb-4">
        <div class="container text-center text-lg-left">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate('All Brands') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate('All Brands') }}"
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="mb-4">
        <div class="container">
            <div class="bg-light rounded-lg p-4 p-md-5 text-center position-relative overflow-hidden" style="background: url('https://picsum.photos/1600/400?random=hero') center/cover no-repeat;">
                <div class="position-absolute w-100 h-100" style="top: 0; left: 0; background: rgba(255, 255, 255, 0.75);"></div>
                <div class="position-absolute" style="width: 300px; height: 300px; background: rgba(144, 98, 255, 0.15); filter: blur(50px); top: -50px; left: -50px; border-radius: 50%;"></div>

                <div class="position-relative z-1">
                    <h1 class="fw-700 fs-24 fs-md-36 text-dark mb-3">Discover Top Brands</h1>
                    <p class="fs-14 fs-md-15 text-dark mb-4 mx-auto fw-500" style="max-width: 600px;">Explore 500+ top brands encompassing fashion, beauty, electronics, home, and more.</p>

                    <form action="{{ route('search') }}" method="GET" class="bg-white rounded-pill p-1 shadow-sm d-flex align-items-center mb-4 mx-auto" style="max-width: 500px;">
                        <i class="las la-search text-muted fs-20 ml-3 mr-2"></i>
                        <input type="text" name="keyword" class="form-control border-0 bg-transparent shadow-none fs-14 pl-0" placeholder="Search for your favorite brand...">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">Search</button>
                    </form>

                    <div class="d-flex align-items-center justify-content-center flex-wrap fs-13">
                        <span class="text-dark fw-700 mr-2 mb-2">TRENDING:</span>
                        @foreach($top_brands->take(5) as $tb)
                            <a href="{{ route('products.brand', $tb->slug) }}" class="badge badge-inline badge-light border text-primary py-1 px-3 rounded-pill fw-600 shadow-sm hov-bg-primary hov-text-white mb-2 mr-2">{{ $tb->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="mb-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap border-bottom pb-3">
                <div class="d-flex flex-wrap align-items-center w-100 w-lg-auto mb-3 mb-lg-0">
                    <a href="{{ route('brands.all') }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-600 shadow-sm mr-2 mb-2">All</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="btn btn-soft-secondary rounded-pill btn-sm px-3 fw-600 text-dark mr-2 mb-2 has-transition">{{ $cat->getTranslation('name') }}</a>
                    @endforeach

                    <div class="border-left mx-3 h-100 d-none d-lg-block" style="border-color: #e2e5ec; height: 20px;"></div>

                    <div class="d-flex flex-wrap mt-2 mt-lg-0">
                        @foreach(range('A', 'Z') as $char)
                            <a href="#brand-{{ $char }}" class="text-secondary px-2 py-1 rounded hov-bg-soft-primary hov-text-primary fw-700 fs-13 mx-1">{{ $char }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="dropdown mt-2 mt-lg-0 ml-auto">
                    <button class="btn btn-light border btn-sm dropdown-toggle rounded-pill fw-600 px-3 shadow-sm" type="button" data-toggle="dropdown">
                        <span class="text-muted mr-1">Sort by:</span> Popularity
                    </button>
                    <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 rounded-lg">
                        <a class="dropdown-item fs-14" href="#">Popularity</a>
                        <a class="dropdown-item fs-14" href="#">Name (A-Z)</a>
                        <a class="dropdown-item fs-14" href="#">Name (Z-A)</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Brands -->
    @if($top_brands->count() > 0)
    <section class="mb-5 pb-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h3 class="fw-700 fs-20 mb-0 text-dark">Featured Brands <span class="badge badge-inline badge-soft-primary text-primary ml-2 rounded-pill fs-10 px-2 py-1">PROMOTED</span></h3>
                <a href="#" class="text-primary fw-600 fs-13 hov-text-dark">See all Featured</a>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 gutters-16">
                @foreach($top_brands as $index => $brand)
                <div class="col mb-4">
                    <div class="card rounded-lg overflow-hidden border-0 shadow-sm hov-shadow-lg has-transition position-relative" style="height: 250px;">
                        <div class="position-absolute w-100 h-100" style="background: url('https://picsum.photos/400/300?random={{ $brand->id }}') center/cover;"></div>
                        <div class="position-absolute w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.8) 100%);"></div>

                        <div class="card-body d-flex flex-column justify-content-between position-relative h-100 p-4">
                            <div class="bg-white rounded d-flex align-items-center justify-content-center shadow-sm p-1" style="width: 44px; height: 44px;">
                                <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->name }}" class="mw-100 mh-100 object-fit-contain" style="max-height: 32px; max-width: 32px;">
                            </div>

                            <div class="mt-auto text-white">
                                <h5 class="fw-700 fs-18 mb-3 text-white">{{ $brand->name }}</h5>
                                <a href="{{ route('products.brand', $brand->slug) }}" class="btn btn-primary btn-sm rounded-pill px-3 fw-600 fs-12 w-100 shadow-sm">Explore Collection</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- All Brands Grouped -->
    <section class="mb-5 pb-5">
        <div class="container">
            <div class="d-flex align-items-center mb-4">
                <h3 class="fw-700 fs-20 mb-0 text-dark">All Brands</h3>
                <span class="badge badge-inline badge-light border text-secondary ml-3 rounded-pill fs-11 px-2 py-1">{{ $brands->count() }}+ BRANDS</span>
            </div>

            @foreach($grouped_brands as $letter => $letter_brands)
            <div class="mb-5" id="brand-{{ $letter }}">
                <h2 class="fw-700 fs-24 mb-4 border-bottom pb-2 text-primary" style="opacity: 0.8;">{{ $letter }}</h2>
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 gutters-16">
                    @foreach($letter_brands as $brand)
                    <div class="col mb-4">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="card border-light shadow-sm hov-shadow-lg has-transition text-center h-100 rounded-lg text-reset">
                            <div class="card-body p-3 p-md-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="w-100 d-flex align-items-center justify-content-center mb-3" style="height: 60px;">
                                    <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->name }}" class="mw-100 mh-100 lazyload object-fit-contain" style="max-height: 50px;">
                                </div>
                                <h6 class="fw-700 fs-14 mb-1 text-dark text-truncate w-100">{{ $brand->getTranslation('name') }}</h6>
                                <p class="fs-12 text-muted mb-0 fw-600">
                                    @if($brand->products_count)
                                        {{ $brand->products_count }} Products
                                    @else
                                        Explore
                                    @endif
                                </p>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>
    </div>
@endsection
