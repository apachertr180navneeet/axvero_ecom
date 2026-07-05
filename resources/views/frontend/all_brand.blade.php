@extends('frontend.layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <section class="mb-4 pt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-lg-left text-center">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">{{ translate('All Brands') }}</h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb justify-content-center justify-content-lg-end bg-transparent p-0">
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
    <!-- All Brands -->
    <section class="mb-5">
        <div class="container">
            <div class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-3 g-4">
                @foreach ($brands as $brand)
                    <div class="col text-center mb-4">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-4 card border-0 shadow-sm rounded-lg h-100 hov-shadow-lg has-transition" style="transition: transform 0.3s ease, box-shadow 0.3s ease;">
                            <div class="d-flex align-items-center justify-content-center h-md-100px mb-3">
                                <img src="{{ uploaded_asset($brand->logo) }}" class="lazyload mw-100 max-h-100" style="object-fit: contain;"
                                    alt="{{ $brand->getTranslation('name') }}">
                            </div>
                            <p class="text-center text-dark fs-15 fw-700 m-0">{{ $brand->getTranslation('name') }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
