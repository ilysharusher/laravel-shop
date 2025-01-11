@extends('layouts.app')

@section('title', $category->title ?? 'Catalog')

@section('content')
    <ul class="breadcrumbs flex flex-wrap gap-y-1 gap-x-4 mb-6">
        <li><a href="{{ route('home') }}" class="text-body hover:text-pink text-xs">Main</a></li>
        <li><a href="{{ route('catalog') }}" class="text-body hover:text-pink text-xs">Catalog</a></li>

        @if($category->exists)
            <li><span class="text-body text-xs">{{ $category->title }}</span></li>
        @endif
    </ul>

    <section>
        <h2 class="text-lg lg:text-[42px] font-black">Categories</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-3 sm:gap-4 md:gap-5 mt-8">
            @each('catalog.shared.category', $categories, 'category')
        </div>
    </section>

    <section class="mt-16 lg:mt-24">
        <h2 class="text-lg lg:text-[42px] font-black">Product catalog</h2>

        <div class="flex flex-col lg:flex-row gap-12 lg:gap-6 2xl:gap-8 mt-8">
            <aside class="basis-2/5 xl:basis-1/4">
                <form action="{{ route('catalog', $category) }}"
                      class="overflow-auto max-h-[320px] lg:max-h-[100%] space-y-10 p-6 2xl:p-8 rounded-2xl bg-card">

                    <input type="hidden" name="sort" value="{{ request()->input('sort') }}">

                    @foreach(filters() as $filter)
                        {!! $filter !!}
                    @endforeach

                    <div>
                        <button type="submit" class="w-full !h-16 btn btn-pink">
                            Apply filters
                        </button>
                    </div>

                    @if(request()->input('filters'))
                        <div>
                            <a href="{{ route('catalog', $category) }}" class="w-full !h-16 btn btn-outline">
                                Reset filters
                            </a>
                        </div>
                    @endif
                </form>
            </aside>

            <div class="basis-auto xl:basis-3/4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">

                        @include('catalog.shared.view')

                        <div class="text-body text-xxs sm:text-xs">Found: {{ $products->total() }} products</div>
                    </div>
                    <div x-data="{}" class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <span class="text-body text-xxs sm:text-xs">Sort by</span>

                        <div>
                            {!! sorter() !!}
                        </div>
                    </div>
                </div>

                @if(is_catalog_view('grid'))
                    <div
                        class="products grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 2xl:gap-x-8 gap-y-8 lg:gap-y-10 2xl:gap-y-12">
                        @each('catalog.shared.product', $products, 'product')
                    </div>
                @elseif(is_catalog_view('list'))
                    <div class="products grid grid-cols-1 gap-y-8">
                        @each('catalog.shared.list-product', $products, 'product')
                    </div>
                @endif

                <div class="mt-12">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        function updateSortUrl(sortValue) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);

            const filters = document.querySelectorAll('input[name^="filters"]');
            filters.forEach(filter => {
                if (filter.type === 'checkbox' && !filter.checked) return;
                url.searchParams.set(filter.name, filter.value);
            });

            window.location.href = url.toString();
        }
    </script>
@endsection
