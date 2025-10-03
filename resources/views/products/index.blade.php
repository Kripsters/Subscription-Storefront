<x-app-layout>
    <!-- Search & Filters -->
    <div class="pt-6 px-6">
        <form method="GET" action="{{ route('products.search') }}">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <!-- Search -->
                <input type="text" name="search" value="{{ request()->input('search') }}"
                    placeholder='{{ __('product.search_placeholder') }}'
                    class="w-full rounded-lg border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-100" />

                <!-- Sort -->
                <select name="order"
                    class="rounded-lg border dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{__('product.sort')}}</option>
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>A–Z</option>
                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Z–A</option>
                    <option value="price_asc" {{ request('order') === 'price_asc' ? 'selected' : '' }}>{{__('product.price_asc')}}</option>
                    <option value="price_desc" {{ request('order') === 'price_desc' ? 'selected' : '' }}>{{ __('product.price_desc') }}</option>
                </select>

                <!-- Per page -->
                <select name="per_page"
                    class="rounded-lg border dark:border-zinc-600 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @foreach ([12, 24, 48] as $size)
                        <option value="{{ $size }}" {{ (int)request('per_page', 12) === $size ? 'selected' : '' }}>
                            {{ $size }} {{ __('product.per_page') }}
                        </option>
                    @endforeach
                </select>

                <!-- Button -->
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400 focus:outline-none transition">
                    {{ __('product.search') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if ($products->count()) <!-- If there are products that match the search -->

        <!-- Pagination Top -->
        <div class="pt-6 px-6 flex items-center justify-center">


            @if ($products->previousPageUrl()) <!-- If there is a previous page -->
                <a class="px-3 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                href="{{ $products->previousPageUrl() }}">
                    <
                </a>
            @endif


            <div class="flex items-center space-x-2"> <!-- Pagination numbers -->
                @for ($i = max(1, $products->currentPage() - 2); $i <= min($products->lastPage(), $products->currentPage() + 2); $i++)
                    <button type="button"
                        class="px-3 py-1 rounded-lg text-sm font-medium {{ $i === $products->currentPage() ? 'bg-indigo-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                        onclick="window.location='{{ $products->url($i) }}'">
                        {{ $i }}
                    </button>
                @endfor
            </div>


            @if ($products->nextPageUrl()) <!-- If there is a next page -->
                <a class="px-3 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                href="{{ $products->nextPageUrl() }}">
                    >
                </a>
            @endif

        </div> <!-- End of pagination -->

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
            @foreach ($products as $item) <!-- For each product -->
                @php
                    // Adjust this number to how many product cards are visible on initial load
                    $isAboveFold = $loop->index < 4;
                @endphp

<div class="group relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-md hover:shadow-2xl transition duration-300 p-5 flex flex-col overflow-hidden">
    
    <!-- Image -->
    <a href="{{ route('products.show', $item->id) }}" class="block relative overflow-hidden rounded-xl">
        <img class="rounded-xl shadow-md w-full h-52 object-cover transform group-hover:scale-105 transition duration-500 ease-out"
             src="{{ asset($item->image) }}"
             alt="{{ __('product.img_alt'). $item->title }}"
             @if ($isAboveFold) 
             loading="eager"
             fetchpriority="high"
             @else  
             loading="lazy"
             fetchpriority="low"
             @endif 
             decoding="async"/>
        <span class="absolute inset-0 bg-gradient-to-t from-black/30 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></span>
    </a>

    <!-- Info -->
    <div class="mt-4 flex-1">
        <h5 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50 line-clamp-1">
            <a href="{{ route('products.show', $item->id) }}" class="hover:text-lime-600 transition">
                {{ $item->title }}
            </a>
        </h5>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300 line-clamp-2">
            {{ Str::limit($item->description, 80) }}
        </p>
        <p class="mt-2">
            <span class="inline-block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                {{ $item->category }}
            </span>
        </p>
    </div>

    <!-- Price -->
    <div class="mt-3 text-xl font-bold text-lime-600">
        ${{ $item->price }}
    </div>

    <!-- Action(s) -->
    <div class="mt-5 flex justify-between items-center gap-3">
        <form method="POST" action="{{ $isActive ? route('subscription.add') : route('cart.add') }}" class="flex items-center gap-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $item->id }}">
            <input type="number" name="quantity" value="1" min="1"
                   class="text-zinc-900 dark:text-zinc-200 w-16 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-2 py-1 text-center text-sm focus:border-lime-400 focus:ring focus:ring-lime-300/40 focus:outline-none transition" />
            
            <button type="submit"
                class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold rounded-xl text-white bg-lime-500 hover:bg-lime-400 shadow-md hover:shadow-lg focus:ring-2 focus:ring-lime-400 transition duration-300 ease-in-out">
                {{ $isActive ? __('subscription.add_to_cart') : __('product.add_to_cart') }}
            </button>
        </form>
    </div>
</div>

            @endforeach <!-- End of product foreach -->
            
        </div>

                <!-- Pagination Top -->
                <div class="pt-6 px-6 flex items-center justify-center">


                    @if ($products->previousPageUrl()) <!-- If there is a previous page -->
                        <a class="px-3 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        href="{{ $products->previousPageUrl() }}">
                            <
                        </a>
                    @endif
        
        
                    <div class="flex items-center space-x-2"> <!-- Pagination numbers -->
                        @for ($i = max(1, $products->currentPage() - 2); $i <= min($products->lastPage(), $products->currentPage() + 2); $i++)
                            <button type="button"
                                class="px-3 py-1 rounded-lg text-sm font-medium {{ $i === $products->currentPage() ? 'bg-indigo-600 text-white' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                                onclick="window.location='{{ $products->url($i) }}'">
                                {{ $i }}
                            </button>
                        @endfor
                    </div>
        
        
                    @if ($products->nextPageUrl()) <!-- If there is a next page -->
                        <a class="px-3 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700"
                        href="{{ $products->nextPageUrl() }}">
                            >
                        </a>
                    @endif
        
                </div> <!-- End of pagination -->
    @else
        <!-- No results -->
        <div class="text-center text-2xl font-bold text-zinc-700 dark:text-zinc-200 py-10">
            {{ __('product.no_results') }}
        </div>
    @endif <!-- End of product search if -->
</x-app-layout>

