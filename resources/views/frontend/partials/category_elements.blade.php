<div class="card-columns">
    @foreach ($categories->childrenCategories as $key => $category)
        <div class="card shadow-none border-0">
            <ul class="list-unstyled mb-3">

                {{-- Parent category with PLUS --}}
              <li class="fs-14 fw-700 mb-3 d-flex align-items-center justify-content-between">
                <a class="text-reset hov-text-primary"
                   href="{{ route('products.category', $category->slug) }}">
                    {{ $category->getTranslation('name') }}
                </a>
            
                @if($category->childrenCategories->count())
                    <button
                        type="button"
                        class="toggle-child btn btn-link p-0 ml-2"
                        data-target="child-{{ $category->id }}"
                        style="font-size:18px;font-weight:bold;text-decoration:none;"
                    >
                        +
                    </button>

                @endif
            </li>

                {{-- Child categories (HIDDEN by default) --}}
                @if($category->childrenCategories->count())
                    <ul id="child-{{ $category->id }}" class="list-unstyled d-none">
                        @foreach ($category->childrenCategories as $child_category)
                            <li class="mb-2 fs-14 pl-3">
                                <a class="text-reset hov-text-primary animate-underline-primary"
                                   href="{{ route('products.category', $child_category->slug) }}">
                                    {{ $child_category->getTranslation('name') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </ul>
        </div>
    @endforeach
</div>