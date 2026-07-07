@extends('frontend.layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="mb-5 py-5" style="background: linear-gradient(135deg, #e4e0f4 0%, #f6f7fa 50%, #eef0f5 100%); position: relative; overflow: hidden;">
        <!-- decorative background blur -->
        <div class="position-absolute rounded-circle" style="width: 400px; height: 400px; background: rgba(144, 98, 255, 0.1); filter: blur(80px); top: -100px; left: -100px;"></div>
        <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(85, 110, 230, 0.1); filter: blur(60px); bottom: -50px; right: -50px;"></div>

        <div class="container position-relative z-1 py-4">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 col-xl-7">
                    <h1 class="fw-800 display-4 mb-3 text-dark" style="letter-spacing: -0.5px;">Discover Top Brands</h1>
                    <p class="lead text-secondary mb-4 fs-16">Explore 500+ top brands encompassing fashion, beauty, electronics, home, and more. Find exactly what you're looking for.</p>
                    
                    <form action="{{ route('search') }}" method="GET" class="bg-white rounded-pill p-2 shadow-sm d-flex align-items-center mb-4 mx-auto" style="max-width: 600px; transition: box-shadow 0.3s ease;">
                        <i class="las la-search text-muted fs-22 ml-3"></i>
                        <input type="text" name="keyword" class="form-control border-0 bg-transparent shadow-none fs-15" placeholder="Search for your favorite brand...">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600 fs-14">Search</button>
                    </form>

                    <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 fs-13">
                        <span class="text-muted fw-700 mr-2 fs-12">TRENDING:</span>
                        @foreach($top_brands->take(5) as $tb)
                            <a href="{{ route('products.brand', $tb->slug) }}" class="badge badge-inline bg-white text-primary py-2 px-3 rounded-pill fw-600 shadow-sm hov-bg-primary hov-text-white has-transition border">{{ $tb->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="mb-5 position-sticky z-3" style="top: 70px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap border-bottom pb-3">
                <div class="d-flex flex-wrap gap-2 mb-2 mb-md-0 align-items-center w-100 w-lg-auto overflow-auto no-scrollbar pb-2 pb-lg-0">
                    <a href="{{ route('brands.all') }}" class="btn btn-primary rounded-pill btn-sm px-4 fw-600 shadow-sm">All</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('products.category', $cat->slug) }}" class="btn btn-outline-light border text-dark rounded-pill btn-sm px-3 fw-600 hov-bg-soft-primary hov-text-primary has-transition">{{ $cat->getTranslation('name') }}</a>
                    @endforeach
                    
                    <div class="border-left mx-3 h-100 d-none d-lg-block" style="border-color: #e2e5ec; height: 24px;"></div>
                    
                    <div class="d-flex gap-1">
                        @foreach(range('A', 'Z') as $char)
                            <a href="#brand-{{ $char }}" class="text-secondary px-2 py-1 rounded hov-bg-soft-primary hov-text-primary fw-700 fs-14 has-transition">{{ $char }}</a>
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
                <h3 class="fw-800 fs-24 mb-0 text-dark">Featured Brands <span class="badge badge-soft-primary text-primary ml-2 rounded-pill fs-11 px-2 py-1">PROMOTED</span></h3>
                <a href="#" class="text-primary fw-700 fs-14 hov-text-dark has-transition">See all Featured</a>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @foreach($top_brands as $index => $brand)
                <div class="col mt-4">
                    <div class="card rounded-xl overflow-hidden border-0 shadow-sm hov-shadow-lg has-transition position-relative group" style="min-height: 280px; border-radius: 16px;">
                        <!-- background image from picsum -->
                        <div class="position-absolute w-100 h-100" style="background: url('https://picsum.photos/400/400?random={{ $brand->id }}') center/cover; transition: transform 0.5s ease;"></div>
                        <!-- gradient overlay -->
                        <div class="position-absolute w-100 h-100" style="background: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.8) 100%);"></div>
                        
                        <div class="card-body d-flex flex-column justify-content-between position-relative z-1 h-100 p-4">
                            <!-- Top left icon -->
                            <div class="bg-white rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; border-radius: 12px !important;">
                                <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->name }}" class="mw-100 mh-100 p-1" style="max-width: 36px; max-height: 36px; object-fit: contain;">
                            </div>
                            
                            <div class="mt-auto text-white">
                                <h5 class="fw-800 fs-20 mb-3 text-white">{{ $brand->name }}</h5>
                                <a href="{{ route('products.brand', $brand->slug) }}" class="btn btn-primary btn-sm rounded-pill px-4 py-2 fw-700 fs-13 w-100 shadow-sm" style="opacity: 0.9;">Explore Collection</a>
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
            <div class="d-flex align-items-center mb-5">
                <h3 class="fw-800 fs-24 mb-0 text-dark">All Brands</h3>
                <span class="badge bg-light border text-secondary ml-3 rounded-pill fs-12 px-3 py-1">{{ $brands->count() }}+ BRANDS</span>
            </div>
            
            @foreach($grouped_brands as $letter => $letter_brands)
            <div class="mb-5" id="brand-{{ $letter }}">
                <h2 class="fw-800 display-4 mb-4 border-bottom pb-2" style="color: #9062ff; opacity: 0.8; letter-spacing: -2px;">{{ $letter }}</h2>
                <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-4 mt-3">
                    @foreach($letter_brands as $brand)
                    <div class="col mt-4">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="card text-center h-100 border border-light shadow-sm hov-shadow-lg has-transition text-reset d-block" style="border-radius: 16px; background-color: #fff;">
                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="w-100 d-flex align-items-center justify-content-center mb-3" style="height: 70px;">
                                    <img src="{{ uploaded_asset($brand->logo) }}" alt="{{ $brand->name }}" class="mw-100 mh-100 lazyload" style="max-height: 60px; object-fit: contain;">
                                </div>
                                <h6 class="fw-800 fs-15 mb-1 text-dark" style="letter-spacing: -0.2px;">{{ $brand->getTranslation('name') }}</h6>
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
@endsection
