<x-app-layout>
    <!-- Search & Filters - Sticky on Scroll -->
    <div class="sticky top-0 z-40 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 shadow-sm transition-all duration-300" id="filter-bar">
        <div class="pt-6 px-6 pb-4">
            <form method="GET" action="{{ route('products.search') }}" id="search-form">
                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <!-- Search with Icon -->
                    <div class="relative flex-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" name="search" value="{{ request()->input('search') }}" id="search-input"
                            placeholder='{{ __('product.search_placeholder') }}'
                            class="w-full pl-10 pr-10 rounded-lg border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-zinc-800 dark:border-zinc-600 dark:text-zinc-100" />
                        @if(request()->input('search'))
                        <button type="button" onclick="clearSearch()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        @endif
                    </div>

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

            <!-- Active Filters Chips -->
            @if(request()->input('search') || request()->input('order'))
            <div class="mt-3 flex flex-wrap gap-2">
                @if(request()->input('search'))
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                    Search: "{{ request()->input('search') }}"
                    <button type="button" onclick="clearSearch()" class="ml-2 hover:text-indigo-600 dark:hover:text-indigo-300">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </span>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Results -->
    @if ($products->count())

        <!-- Results Counter -->
        <div class="px-6 pt-6 flex items-center justify-center">
            <p class="text-sm text-zinc-600 dark:text-zinc-400 font-medium animate-fade-in">
                {{ __('product.showing') }} 
                <span class="text-zinc-900 dark:text-zinc-100 font-semibold">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span> 
                {{ __('product.of') }} 
                <span class="text-zinc-900 dark:text-zinc-100 font-semibold">{{ $products->total() }}</span> 
                {{ __('product.products') }}
            </p>
        </div>

        <!-- Pagination Top -->
        <div class="pt-4 px-6 flex items-center justify-center gap-2">
            @if ($products->previousPageUrl())
                <a class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                href="{{ $products->previousPageUrl() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            <div class="flex items-center space-x-2">
                @for ($i = max(1, $products->currentPage() - 2); $i <= min($products->lastPage(), $products->currentPage() + 2); $i++)
                    <button type="button"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200 {{ $i === $products->currentPage() ? 'bg-indigo-600 text-white shadow-md' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                        onclick="window.location='{{ $products->url($i) }}'">
                        {{ $i }}
                    </button>
                @endfor
            </div>

            @if ($products->nextPageUrl())
                <a class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                href="{{ $products->nextPageUrl() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-6">
            @foreach ($products as $item)
                @php
                    $isAboveFold = $loop->index < 4;
                @endphp

<div class="group relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-md hover:shadow-2xl transition-all duration-300 p-5 flex flex-col overflow-hidden card-animate" style="animation-delay: {{ $loop->index * 0.05 }}s;">
    
    <!-- Quick View Button (appears on hover) -->
    <button onclick="showQuickView({{ $item->id }})" 
        class="absolute top-3 right-3 z-10 p-2 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 hover:scale-110 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-700 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>

    <!-- Image -->
    <a href="{{ route('products.show', $item->id) }}" class="block relative overflow-hidden rounded-xl">
        <img class="rounded-xl shadow-md w-full h-52 object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out"
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
        <span class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>
    </a>

    <!-- Info -->
    <div class="mt-4 flex-1">
        <h5 class="text-xl font-semibold text-zinc-900 dark:text-zinc-50 line-clamp-1">
            <a href="{{ route('products.show', $item->id) }}" class="hover:text-lime-600 transition-colors duration-200">
                {{ $item->title }}
            </a>
        </h5>
        <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300 line-clamp-2">
            {{ Str::limit($item->description, 80) }}
        </p>
        <p class="mt-2">
            <span class="inline-block text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400 px-2 py-1 bg-zinc-100 dark:bg-zinc-800 rounded-md">
                {{ $item->category }}
            </span>
        </p>
    </div>

    <!-- Price -->
    <div class="mt-3 text-2xl font-bold text-lime-600">
        ${{ $item->price }}
    </div>

    <!-- Action(s) -->
    <div class="mt-5 flex justify-between items-center gap-3">
        <form method="POST" action="{{ $isActive ? route('subscription.add') : route('cart.add') }}" class="flex items-center gap-3 w-full">
            @csrf
            <input type="hidden" name="product_id" value="{{ $item->id }}">
            <input type="number" name="quantity" value="1" min="1"
                   class="text-zinc-900 dark:text-zinc-200 w-16 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-2 py-1 text-center text-sm focus:border-lime-400 focus:ring focus:ring-lime-300/40 focus:outline-none transition" />
            
            <button type="submit"
                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold rounded-xl text-white bg-lime-500 hover:bg-lime-400 hover:-translate-y-0.5 shadow-md hover:shadow-lg focus:ring-2 focus:ring-lime-400 transition-all duration-300 ease-in-out group/btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $isActive ? __('subscription.add_to_cart') : __('product.add_to_cart') }}
            </button>
        </form>
    </div>
</div>

            @endforeach
            
        </div>

        <!-- Pagination Bottom -->
        <div class="pb-6 px-6 flex items-center justify-center gap-2">
            @if ($products->previousPageUrl())
                <a class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                href="{{ $products->previousPageUrl() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            <div class="flex items-center space-x-2">
                @for ($i = max(1, $products->currentPage() - 2); $i <= min($products->lastPage(), $products->currentPage() + 2); $i++)
                    <button type="button"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-all duration-200 {{ $i === $products->currentPage() ? 'bg-indigo-600 text-white shadow-md' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                        onclick="window.location='{{ $products->url($i) }}'">
                        {{ $i }}
                    </button>
                @endfor
            </div>

            @if ($products->nextPageUrl())
                <a class="px-3 py-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors"
                href="{{ $products->nextPageUrl() }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif
        </div>
    @else
        <!-- Enhanced Empty State -->
        <div class="flex flex-col items-center justify-center py-20 px-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-2xl font-bold text-zinc-700 dark:text-zinc-200 mb-2">
                {{ __('product.no_results') }}
            </h3>
            <p class="text-zinc-500 dark:text-zinc-400 mb-6">
                {{ __('product.try_different_search') }}
            </p>
            <a href="{{ route('products.search') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500 transition-colors">
                {{ __('product.view_all_products') }}
            </a>
        </div>
    @endif

    <!-- Scroll to Top Button -->
    <button id="scroll-top" 
        class="fixed bottom-8 right-8 p-3 bg-indigo-600 text-white rounded-full shadow-lg opacity-0 pointer-events-none transition-all duration-300 hover:bg-indigo-500 hover:scale-110 z-50"
        onclick="scrollToTop()">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .card-animate {
            opacity: 0;
            animation: slideUp 0.5s ease-out forwards;
        }

        /* Sticky filter bar enhancement */
        #filter-bar.scrolled {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        // Clear search function
        function clearSearch() {
            document.getElementById('search-input').value = '';
            document.getElementById('search-form').submit();
        }

        // Scroll to top function
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            const scrollBtn = document.getElementById('scroll-top');
            const filterBar = document.getElementById('filter-bar');
            
            if (window.pageYOffset > 300) {
                scrollBtn.classList.remove('opacity-0', 'pointer-events-none');
                scrollBtn.classList.add('opacity-100');
                filterBar.classList.add('scrolled');
            } else {
                scrollBtn.classList.add('opacity-0', 'pointer-events-none');
                scrollBtn.classList.remove('opacity-100');
                filterBar.classList.remove('scrolled');
            }
        });

        // Quick view function (placeholder - you'll need to implement the modal)
        function showQuickView(productId) {
            // This would open a modal with product details 
            // For now, just navigate to the product page
            window.location.href = `/products/show/${productId}`;
            
            // In a full implementation, you would:
            // 1. Fetch product data via AJAX
            // 2. Open a modal with the data
            // 3. Allow adding to cart from the modal
        }
    </script>
</x-app-layout>