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
                    <div>
                        <h5 class="mb-4 text-sm 2xl:text-md font-bold">Price</h5>
                        <div class="flex items-center justify-between gap-3 mb-2">
                            <span class="text-body text-xxs font-medium">From, $</span>
                            <span class="text-body text-xxs font-medium">To, $</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <input
                                name="filters[price][from]"
                                value="{{ request()->input('filters.price.from', 0) }}"
                                type="number"
                                class="w-full h-12 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xs shadow-transparent outline-0 transition"
                                placeholder="From"
                            >
                            <span class="text-body text-sm font-medium">–</span>
                            <input
                                name="filters[price][to]"
                                value="{{ request()->input('filters.price.to', 100000) }}"
                                type="number"
                                class="w-full h-12 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xs shadow-transparent outline-0 transition"
                                placeholder="To"
                            >
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-4 text-sm 2xl:text-md font-bold">Brand</h5>

                        @foreach($brands as $brand)
                            <div class="form-checkbox">
                                <input
                                    name="filters[brands][{{ $brand->id }}]"
                                    value="{{ $brand->id }}"
                                    type="checkbox"
                                    id="filters-brands-{{ $brand->id }}"
                                    @checked(request()->input("filters.brands.{$brand->id}"))
                                >
                                <label for="filters-brands-{{ $brand->id }}" class="form-checkbox-label">
                                    {{ $brand->title }}
                                </label>
                            </div>
                        @endforeach
                    </div>

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
                        <div class="flex items-center gap-2">
                            <a href="catalog.html"
                               class="pointer-events-none inline-flex items-center justify-center w-10 h-10 rounded-md bg-card text-pink">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 52 52">
                                    <path fill-rule="evenodd"
                                          d="M2.6 28.6h18.2a2.6 2.6 0 0 1 2.6 2.6v18.2a2.6 2.6 0 0 1-2.6 2.6H2.6A2.6 2.6 0 0 1 0 49.4V31.2a2.6 2.6 0 0 1 2.6-2.6Zm15.6 18.2v-13h-13v13h13ZM31.2 0h18.2A2.6 2.6 0 0 1 52 2.6v18.2a2.6 2.6 0 0 1-2.6 2.6H31.2a2.6 2.6 0 0 1-2.6-2.6V2.6A2.6 2.6 0 0 1 31.2 0Zm15.6 18.2v-13h-13v13h13ZM31.2 28.6h18.2a2.6 2.6 0 0 1 2.6 2.6v18.2a2.6 2.6 0 0 1-2.6 2.6H31.2a2.6 2.6 0 0 1-2.6-2.6V31.2a2.6 2.6 0 0 1 2.6-2.6Zm15.6 18.2v-13h-13v13h13ZM2.6 0h18.2a2.6 2.6 0 0 1 2.6 2.6v18.2a2.6 2.6 0 0 1-2.6 2.6H2.6A2.6 2.6 0 0 1 0 20.8V2.6A2.6 2.6 0 0 1 2.6 0Zm15.6 18.2v-13h-13v13h13Z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </a>
                            <a href="catalog-list.html"
                               class="inline-flex items-center justify-center w-10 h-10 rounded-md bg-card text-white hover:text-pink">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 52 52">
                                    <path fill-rule="evenodd"
                                          d="M7.224 4.875v4.694h37.555V4.875H7.224ZM4.877.181a2.347 2.347 0 0 0-2.348 2.347v9.389a2.347 2.347 0 0 0 2.348 2.347h42.25a2.347 2.347 0 0 0 2.347-2.347v-9.39A2.347 2.347 0 0 0 47.127.182H4.877Zm2.347 23.472v4.694h37.555v-4.694H7.224Zm-2.347-4.695a2.347 2.347 0 0 0-2.348 2.348v9.389a2.347 2.347 0 0 0 2.348 2.347h42.25a2.347 2.347 0 0 0 2.347-2.348v-9.388a2.347 2.347 0 0 0-2.347-2.348H4.877ZM7.224 42.43v4.695h37.555v-4.694H7.224Zm-2.347-4.694a2.347 2.347 0 0 0-2.348 2.347v9.39a2.347 2.347 0 0 0 2.348 2.346h42.25a2.347 2.347 0 0 0 2.347-2.347v-9.389a2.347 2.347 0 0 0-2.347-2.347H4.877Z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </a>
                        </div>
                        <div class="text-body text-xxs sm:text-xs">Found: {{ $products->count() }} products</div>
                    </div>
                    <div x-data="{}" class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <span class="text-body text-xxs sm:text-xs">Sort by</span>
                        <form x-ref="sort" action="{{ route('catalog', $category) }}">
                            <select
                                x-on:change="$refs.sort.submit()"
                                name="sort"
                                class="form-select w-full h-12 px-4 rounded-lg border border-body/10 focus:border-pink focus:shadow-[0_0_0_3px_#EC4176] bg-white/5 text-white text-xxs sm:text-xs shadow-transparent outline-0 transition">
                                <option value="" class="text-dark">Default</option>
                                <option
                                    @selected(request()->input('sort') === 'price')
                                    value="price" class="text-dark">Cheaper
                                </option>
                                <option
                                    @selected(request()->input('sort') === '-price')
                                    value="-price" class="text-dark">More expensive
                                </option>
                                <option
                                    @selected(request()->input('sort') === 'title')
                                    value="title" class="text-dark">By name
                                </option>
                            </select>
                        </form>
                    </div>
                </div>

                <div
                    class="products grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-x-6 2xl:gap-x-8 gap-y-8 lg:gap-y-10 2xl:gap-y-12">
                    @each('catalog.shared.product', $products, 'product')
                </div>

                <div class="mt-12">
                    {{ $products->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
