@extends('frontend.layouts.app')

@section('content')
    <!-- Header Section -->
    <section class="pt-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-center text-lg-left mb-3 mb-lg-0">
                    <!-- Breadcrumb -->
                    <ul class="breadcrumb bg-transparent p-0 justify-content-center justify-content-lg-start mb-2 fs-14">
                        <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            {{ translate('All Categories') }}
                        </li>
                    </ul>
                    <h1 class="fw-700 fs-24 text-dark mb-2">
                        {{ translate('All Categories') }}
                    </h1>
                    <div class="fs-14 text-secondary">
                        {{ translate('Browse categories across departments - find exactly what you need.') }}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap flex-md-nowrap">
                        <div class="input-group mb-2 mb-md-0 mr-md-3 bg-white border rounded-lg" style="max-width: 300px; min-width: 200px; border-color: #e2e8f0;">
                            <div class="input-group-prepend border-0">
                                <span class="input-group-text bg-transparent border-0 text-muted pl-3"><i class="las la-search"></i></span>
                            </div>
                            <input type="text" class="form-control border-0 bg-transparent shadow-none fs-14" id="categorySearch" placeholder="{{ translate('Filter categories...') }}" style="padding-left: 0;">
                        </div>
                        <div class="dropdown">
                            <button class="btn bg-white border rounded-lg text-dark dropdown-toggle d-flex align-items-center shadow-sm fs-14" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="min-width: 140px; justify-content: space-between; border-color: #e2e8f0; height: 44px;">
                                <span>{{ translate('Sort A-Z') }}</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item sort-category fs-14" href="javascript:void(0)" data-sort="asc">{{ translate('Sort A-Z') }}</a>
                                <a class="dropdown-item sort-category fs-14" href="javascript:void(0)" data-sort="desc">{{ translate('Sort Z-A') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- All Categories -->
    <section class="mb-5 pb-3">
        <div class="container" id="category-list">
            @foreach ($categories as $key => $category)
                @php
                    $isExpanded = $key == 0;
                @endphp
                <div class="card border mb-4 overflow-hidden category-card bg-white {{ $isExpanded ? 'border-primary shadow-sm expanded' : '' }}" style="{{ !$isExpanded ? 'border-color: #e2e8f0;' : 'border-color: var(--primary);' }}" data-name="{{ strtolower($category->getTranslation('name')) }}">
                    <!-- Category Header -->
                    <div class="card-header bg-white border-bottom-0 p-3 p-md-4 d-flex align-items-center justify-content-between" style="cursor: pointer;" data-toggle="collapse" data-target="#collapseCategory{{ $category->id }}" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}">
                        <div class="d-flex align-items-center">
                            <!-- Category Icon -->
                            <div class="category-icon-box bg-primary text-white mr-3 mr-md-4 d-flex align-items-center justify-content-center shadow-sm p-2">
                                @php
                                    $mainIcon = $category->icon ? $category->icon : $category->banner;
                                @endphp
                                @if($mainIcon)
                                    <img src="{{ uploaded_asset($mainIcon) }}" alt="{{ $category->getTranslation('name') }}" class="img-fit h-100 w-100" style="object-fit: contain;" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='block';">
                                    <i class="las la-tags fs-24" style="display: none;"></i>
                                @else
                                    <i class="las la-tags fs-24"></i>
                                @endif
                            </div>
                            <!-- Category Title -->
                            <div>
                                <h3 class="fs-16 fs-md-18 fw-700 text-dark mb-1 category-name">{{ $category->getTranslation('name') }}</h3>
                                <div class="fs-12 fs-md-13 text-secondary">
                                    {{ $category->childrenCategories->count() }} {{ translate('subcategories') }}
                                </div>
                            </div>
                        </div>
                        <!-- View All Toggle -->
                        <div class="d-none d-md-flex align-items-center text-primary hov-text-primary has-transition">
                            <span class="fw-600 fs-13 fs-md-14 toggle-text mr-1">{{ translate('View All') }}</span>
                            <i class="las {{ $isExpanded ? 'la-angle-up' : 'la-angle-down' }} fs-14 fs-md-16 toggle-icon"></i>
                        </div>
                        <div class="d-md-none text-primary">
                            <i class="las {{ $isExpanded ? 'la-angle-up' : 'la-angle-down' }} fs-20 toggle-icon"></i>
                        </div>
                    </div>

                    <!-- Category Content -->
                    <div id="collapseCategory{{ $category->id }}" class="collapse {{ $isExpanded ? 'show' : '' }}">
                        <div class="card-body p-3 p-md-4 pt-0">
                            <div class="row row-cols-xxl-4 row-cols-lg-3 row-cols-md-2 row-cols-1 gutters-16">
                                @foreach ($category->childrenCategories as $sub_key => $child_category)
                                    @php
                                        $isSubcatExpanded = $sub_key < 2; // Expand first 2 for preview
                                    @endphp
                                    <div class="col mb-4">
                                        <div class="p-3 h-100 d-flex flex-column bg-white subcat-card">
                                            <!-- Sub Category Header -->
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <a href="{{ route('products.category', $child_category->slug) }}" class="text-dark fw-600 fs-14 hov-text-primary d-flex align-items-center flex-grow-1">
                                                    @if($child_category->icon)
                                                        <img src="{{ uploaded_asset($child_category->icon) }}" class="size-20px mr-2" style="object-fit: contain;" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='inline-block';">
                                                        <i class="las la-tag text-primary opacity-60 fs-18 mr-2" style="display: none;"></i>
                                                    @else
                                                        <i class="las la-tag text-primary opacity-60 fs-18 mr-2"></i>
                                                    @endif
                                                    <span class="text-truncate" style="max-width: 85%;">{{ $child_category->getTranslation('name') }}</span>
                                                </a>
                                                
                                                @if ($child_category->childrenCategories->count())
                                                    <a href="#subcat-{{ $child_category->id }}" data-toggle="collapse" class="text-primary p-1 hov-text-primary toggle-subcat ml-2" aria-expanded="{{ $isSubcatExpanded ? 'true' : 'false' }}">
                                                        <i class="las {{ $isSubcatExpanded ? 'la-angle-up' : 'la-angle-down' }} fs-14"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Sub-sub Categories list -->
                                            @if ($child_category->childrenCategories->count())
                                                <div id="subcat-{{ $child_category->id }}" class="collapse subcat-collapse ml-1 {{ $isSubcatExpanded ? 'show' : '' }}" style="margin-top: 8px;">
                                                    <ul class="list-unstyled mb-0 pb-1">
                                                        @foreach ($child_category->childrenCategories as $second_level_category)
                                                            <li class="mb-2">
                                                                <a class="text-secondary fw-500 fs-13 hov-text-primary d-flex align-items-center"
                                                                   href="{{ route('products.category', $second_level_category->slug) }}">
                                                                    <span class="mr-2 text-primary" style="font-size:12px; font-weight:bold;">|</span>
                                                                    {{ $second_level_category->getTranslation('name') }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            
                                            <!-- Item count -->
                                            <div class="mt-auto pt-2 fs-12 text-muted fw-500 item-count" style="{{ $isSubcatExpanded && $child_category->childrenCategories->count() ? 'display: none;' : '' }}">
                                                {{ $child_category->childrenCategories->count() }} {{ translate('items') }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@section('script')
    <script>
        // Toggle main category view all arrow
        $('.card-header').on('click', function() {
            let card = $(this).closest('.category-card');
            let icon = $(this).find('.toggle-icon');
            
            if(card.hasClass('expanded')) {
                card.removeClass('expanded border-primary shadow-sm');
                card.css('border-color', '#e2e8f0');
                icon.removeClass('la-angle-up').addClass('la-angle-down');
            } else {
                card.addClass('expanded border-primary shadow-sm');
                card.css('border-color', 'var(--primary)');
                icon.removeClass('la-angle-down').addClass('la-angle-up');
            }
        });

        // Toggle subcategory arrow and item count visibility
        $('.toggle-subcat').on('click', function(e) {
            e.stopPropagation();
            let icon = $(this).find('i');
            let card = $(this).closest('.subcat-card');
            let itemCount = card.find('.item-count');
            
            if(icon.hasClass('la-angle-down')) {
                icon.removeClass('la-angle-down').addClass('la-angle-up');
                itemCount.hide();
            } else {
                icon.removeClass('la-angle-up').addClass('la-angle-down');
                itemCount.show();
            }
        });

        // Search Filter
        $('#categorySearch').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#category-list .category-card').filter(function() {
                var categoryName = $(this).attr('data-name');
                $(this).toggle(categoryName.indexOf(value) > -1)
            });
        });

        // Sort Categories
        $('.sort-category').on('click', function() {
            var sort = $(this).data('sort');
            var $list = $('#category-list');
            var $cards = $list.children('.category-card').get();
            
            $cards.sort(function(a, b) {
                var compA = $(a).attr('data-name');
                var compB = $(b).attr('data-name');
                if (sort === 'asc') {
                    return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                } else {
                    return (compA > compB) ? -1 : (compA < compB) ? 1 : 0;
                }
            });
            
            $.each($cards, function(idx, itm) { $list.append(itm); });
            
            // Update dropdown text
            $('#dropdownMenuButton span').text($(this).text());
        });
        
        // Prevent collapse when clicking links inside headers
        $('.card-header a').on('click', function(e) {
            e.stopPropagation();
        });
    </script>
    <style>
        .category-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }
        .category-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
        }
        .category-card.expanded {
            border-color: var(--primary) !important;
        }
        .category-icon-box {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            flex-shrink: 0;
            background-color: var(--primary);
        }
        .subcat-card {
            border-radius: 10px;
            border: 1px solid #f1f5f9;
        }
        .subcat-card:hover {
            border-color: #e2e8f0;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        }
    </style>
@endsection
