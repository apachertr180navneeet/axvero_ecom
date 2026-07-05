@extends('frontend.layouts.app')

@section('content')
    <!-- Breadcrumb -->
    <section class="pt-4 mb-4">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <h1 class="fw-700 fs-20 fs-md-24 text-dark">
                        {{ translate('All Categories') }}
                    </h1>
                </div>
                <div class="col-lg-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-end">
                        <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate('All Categories') }}"
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- All Categories -->
    <section class="mb-5 pb-3">
        <div class="container">
            @foreach ($categories as $key => $category)
                <div class="card border-0 shadow-sm rounded-lg mb-4 overflow-hidden">
                    <!-- Category Name -->
                    <a href="{{ route('products.category', $category->slug) }}" class="bg-light p-4 d-flex align-items-center hov-bg-primary hov-text-white" style="transition: all 0.3s;">
                        <div class="size-60px overflow-hidden rounded-circle shadow-sm bg-white p-2 border-0 mr-4">
                            <img src="{{ uploaded_asset($category->banner) }}" alt="" class="img-fit h-100 w-100 object-fit-cover">
                        </div>
                        <div class="text-dark fs-18 fw-700 m-0">
                            {{ $category->getTranslation('name') }}
                        </div>
                    </a>
                    <div class="p-4 bg-white">
                        <div class="row row-cols-xl-5 row-cols-md-3 row-cols-sm-2 row-cols-1 gutters-16">
                            @foreach ($category->childrenCategories as $key => $child_category)
                                <div class="col text-left mb-4">
                                    <!-- Sub Category Name -->
                                    <h6 class="text-dark mb-3 d-flex justify-content-between align-items-center border-bottom pb-2">
                                        <a class="text-reset fw-700 fs-15 hov-text-primary"
                                           href="{{ route('products.category', $child_category->slug) }}">
                                            {{ $child_category->getTranslation('name') }}
                                        </a>
                                    
                                        @if ($child_category->childrenCategories->count())
                                            <button
                                                type="button"
                                                class="toggle-second btn btn-light btn-sm rounded-circle p-0 ml-2 d-flex align-items-center justify-content-center"
                                                data-target="second-{{ $child_category->id }}"
                                                style="width: 24px; height: 24px; text-decoration:none;"
                                            >
                                                <i class="las la-plus fs-14"></i>
                                            </button>
                                        @endif
                                    </h6>


                                    <!-- Sub-sub Categories -->
                                    <ul
                                        id="second-{{ $child_category->id }}"
                                        class="mb-2 list-unstyled d-none pl-2" style="border-left: 2px solid #f1f1f1;"
                                    >
                                        @foreach ($child_category->childrenCategories as $second_level_category)
                                            <li class="text-muted mb-2">
                                                <a class="text-reset fw-500 fs-14 hov-text-primary animate-underline-primary"
                                                   href="{{ route('products.category', $second_level_category->slug) }}">
                                                    {{ $second_level_category->getTranslation('name') }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    @if ($child_category->childrenCategories->count() > 5)
                                        <a href="javascript:void(1)"
                                            class="show-hide-cetegoty text-primary hov-text-primary fs-13 fw-600 mt-2 d-inline-block">{{ translate('View More') }}
                                            <i class="las la-angle-down"></i></a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('script')
    <script>
        $('.show-hide-cetegoty').on('click', function() {
            var el = $(this).siblings('ul');
            if (el.hasClass('less')) {
                el.removeClass('less');
                $(this).html('{{ translate('Less') }} <i class="las la-angle-up"></i>');
            } else {
                el.addClass('less');
                $(this).html('{{ translate('More') }} <i class="las la-angle-down"></i>');
            }
        });
    </script>
    <script>
    // Event delegation (AIZ compatible)
    $(document).on('click', '.toggle-second', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let targetId = $(this).data('target');
        let $target = $('#' + targetId);

        if (!$target.length) return;

        if ($target.hasClass('d-none')) {
            $target.removeClass('d-none');
            $(this).text('-');
        } else {
            $target.addClass('d-none');
            $(this).text('+');
        }
    });
</script>
@endsection
