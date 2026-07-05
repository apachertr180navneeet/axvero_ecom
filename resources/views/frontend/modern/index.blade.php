@extends('frontend.layouts.app')

@section('content')
@php 
    $lang = get_system_language()->code;
    $featured_categories = \App\Models\Category::where('featured', 1)->take(8)->get();
@endphp

<style>
    .modern-hero-section {
        background: linear-gradient(135deg, #e64e1c 0%, #b41919 100%);
        border-radius: 10px;
        color: white;
        overflow: hidden;
        position: relative;
        height: 400px;
        display: flex;
        align-items: center;
        padding: 3rem;
    }
    .modern-hero-text h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    .modern-hero-text h2 {
        font-size: 2rem;
        font-weight: 300;
    }
    .modern-hero-text .discount {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        margin: 1rem 0;
    }
    .modern-hero-img {
        position: absolute;
        bottom: 0;
        left: 5%;
        height: 100%;
        object-fit: cover;
    }
    .hot-categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        text-align: center;
    }
    .hot-cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .hot-cat-item img {
        width: 70px;
        height: 70px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        margin-bottom: 8px;
        transition: transform 0.3s;
    }
    .hot-cat-item:hover img {
        transform: scale(1.05);
    }
    .hot-cat-item span {
        font-size: 11px;
        font-weight: 600;
        color: #555;
    }
    .section-title-modern {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    .section-title-modern i {
        color: #ff5722;
        margin-right: 8px;
        font-size: 1.5rem;
    }
    
    /* Section Wrappers */
    .bg-featured-products { background-color: #e5edb8; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
    .bg-featured-categories { background-color: #4df0fc; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; position: relative; }
    .bg-best-selling { background-color: #f2dbdb; padding: 2rem; border-radius: 10px; margin-bottom: 2rem; }
    .bg-todays-deal { background-color: #f5f5f5; padding: 2rem; border-radius: 10px; border: 1px solid #ddd; margin-bottom: 2rem; }
</style>

<div class="container pt-4">
    <div class="row gutters-10 mb-4">
        <!-- Hero Banner -->
        <div class="col-lg-6 mb-3 mb-lg-0">
            <div class="modern-hero-section">
                <!-- If you have a real banner image, you can replace the hardcoded gradient with it -->
                <div style="position:absolute; top:0; left:0; width:100%; height:100%; background: url('{{ static_asset('assets/img/placeholder.jpg') }}') center/cover no-repeat; opacity: 0.2;"></div>
                <div class="modern-hero-text position-relative z-1 ml-auto text-right w-100">
                    <h1 class="text-uppercase">AXVERO</h1>
                    <h2>END OF SEASON<br><strong>SALE</strong></h2>
                    <div class="discount">UP TO<br>75<span style="font-size: 2rem;">+15% OFF</span></div>
                    <p class="mb-2">ON YOUR 1st ORDER</p>
                    <div class="d-inline-block px-3 py-1 rounded" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);">
                        Use Code: <strong>AFNEW15</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hot Categories -->
        <div class="col-lg-6">
            <div class="bg-white p-4 rounded-lg h-100 shadow-sm border-0">
                <h4 class="section-title-modern"><i class="las la-fire"></i> Hot Categories</h4>
                <div class="hot-categories-grid mt-4">
                    @foreach ($featured_categories as $category)
                        <a href="{{ route('products.category', $category->slug) }}" class="hot-cat-item">
                            <img src="{{ uploaded_asset($category->banner) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="{{ $category->getTranslation('name') }}">
                            <span class="text-truncate" style="max-width: 100%;">{{ $category->getTranslation('name') }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div id="section_featured"></div>

    <!-- Featured Categories Section -->
    <div id="section_home_categories"></div>

    <div class="row gutters-10">
        <div class="col-lg-6">
            <!-- Best Selling -->
            <div id="section_best_selling"></div>
        </div>
        <div class="col-lg-6">
            <!-- Todays Deal -->
            <div id="section_todays_deal"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $.post('{{ route('home.section.featured') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_featured').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.best_selling') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_best_selling').html(data);
                AIZ.plugins.slickCarousel();
            });
            $.post('{{ route('home.section.home_categories') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_home_categories').html(data);
                AIZ.plugins.slickCarousel();
            });
            // Todays Deal
            $.post('{{ route('home.section.todays_deal') }}', {_token:'{{ csrf_token() }}'}, function(data){
                $('#section_todays_deal').html(data);
                AIZ.plugins.slickCarousel();
            });
        });
    </script>
@endsection
