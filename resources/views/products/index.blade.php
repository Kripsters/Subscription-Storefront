<x-app-layout>
    <!-- Search & Filters -->
    <div class="pt-6 px-6">
        <form method="GET" action="{{ route('products.search') }}">
            <div class="flex flex-col md:flex-row md:items-center gap-3">
                <!-- Search -->
                <input type="text" name="search" value="{{ request()->input('search') }}"
                    placeholder='{{ __('product.search_placeholder') }}'
                    class="w-full rounded-lg border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />

                <!-- Sort -->
                <select name="order"
                    class="rounded-lg border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">{{__('product.sort')}}</option>
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>A–Z</option>
                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Z–A</option>
                    <option value="price_asc" {{ request('order') === 'price_asc' ? 'selected' : '' }}>{{__('product.price_asc')}}</option>
                    <option value="price_desc" {{ request('order') === 'price_desc' ? 'selected' : '' }}>{{ __('product.price_desc') }}</option>
                </select>

                <!-- Per page -->
                <select name="per_page"
                    class="rounded-lg border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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

                <div class="group relative bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-sm hover:shadow-lg transition p-4 flex flex-col">
                    
                    <!-- Image -->
                    <a href="{{ route('products.show', $item->id) }}">
                        <img class="rounded-lg shadow-md mx-auto w-full h-48 object-cover group-hover:scale-105 transition"
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
                    </a>

                    <!-- Info -->
                    <div class="mt-4 flex-1">
                        <h5 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 truncate">
                            <a href="{{ route('products.show', $item->id) }}">{{ $item->title }}</a>
                        </h5>
                        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">
                            {{ Str::limit($item->description, 60) }}
                        </p>
                        <p>
                                <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                    {{ $item->category }} 
                                </span>
                        </p>
                    </div>

                    <!-- Price -->
                    <div class="mt-3 text-lg font-bold text-lime-600">
                        ${{ $item->price }}
                    </div>

                    <!-- Action(s) -->
                    <div class="mt-4 flex justify-between items-center">
                        
                        <!-- Add to cart -->
                        @if ($isActive)
                        <form method="POST" action="{{ route('subscription.add') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="1" min="1"
                                   class="w-16 rounded-md border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-lime-500 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-lime-400 hover:shadow-xl transition duration-300 ease-in-out">
                                {{ __('subscription.add_to_cart') }}
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('cart.add') }}" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="1" min="1"
                                    class="w-16 rounded-md border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <button type="submit"
                                    class="px-3 py-1 bg-lime-500 text-white text-sm rounded-md hover:bg-lime-400 focus:ring-2 focus:ring-lime-400 transition duration-300 ease-in-out">
                                {{ __('product.add_to_cart') }}
                            </button>
                        </form>
                        @endif

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

