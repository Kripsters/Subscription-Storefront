<x-app-layout>
    <x-slot name="header">
        <header class="bg-gradient-to-r from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-extrabold text-zinc-800 dark:text-zinc-100 tracking-tight animate-fade-in">
                    {{ $product->category }}
                </h1>
                <div class="flex items-center space-x-4 mt-4 animate-slide-up">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-zinc-600 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">{{ __('product.back') }}</span>
                    </a>
                </div>
            </div>
        </header>
    </x-slot>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            
            {{-- Product Image with Zoom --}}
            <div class="animate-slide-in-left">
                <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300 mb-4 relative group cursor-zoom-in" id="product-image-container">
                    <img src="{{ $product->image }}" 
                         alt="{{ __('product.img_alt') . $product->title }}" 
                         class="w-full h-auto object-cover transition-transform duration-300"
                         id="product-image">
                    
                    {{-- Zoom Indicator --}}
                    <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 8a1 1 0 011-1h1V6a1 1 0 012 0v1h1a1 1 0 110 2H9v1a1 1 0 11-2 0V9H6a1 1 0 01-1-1z" />
                            <path fill-rule="evenodd" d="M2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8zm6-4a4 4 0 100 8 4 4 0 000-8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 text-center">{{ __('product.hover_to_zoom') }}</p>
            </div>

            {{-- Product Info --}}
            <div class="flex flex-col space-y-6 border border-zinc-200 dark:border-zinc-700 p-8 rounded-3xl bg-white dark:bg-zinc-800 shadow-lg animate-slide-in-right">
                <div class="animate-fade-in" style="animation-delay: 0.1s;">
                    <h2 class="text-4xl font-bold text-zinc-800 dark:text-zinc-100 mb-4">
                        {{ $product->title }}
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $product->description }}
                    </p>
                </div>

                <div class="text-3xl font-extrabold text-zinc-800 dark:text-white animate-fade-in" style="animation-delay: 0.2s;">
                    <span class="bg-gradient-to-r from-lime-500 to-green-600 bg-clip-text text-transparent">
                        {{ $product->price }} &euro;
                    </span>
                </div>

                {{-- Actions --}}
                <div class="animate-fade-in" style="animation-delay: 0.3s;">
                    @if ($isActive)
                    <form method="POST" action="{{ route('subscription.add') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        {{-- Custom Quantity Selector --}}
                        <div class="flex items-center space-x-4">
                            <label class="text-zinc-700 dark:text-zinc-300 font-medium">{{ __('product.quantity') }}:</label>
                            <div class="flex items-center border border-zinc-300 dark:border-zinc-600 rounded-lg overflow-hidden">
                                <button type="button" onclick="decrementQuantity()" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="number" name="quantity" value="1" min="1" id="quantity-input"
                                       class="w-16 text-center border-0 focus:ring-0 dark:bg-zinc-800 dark:text-zinc-100" readonly />
                                <button type="button" onclick="incrementQuantity()" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-8 py-4 bg-lime-500 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-lime-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('subscription.add_to_cart') }}
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        {{-- Custom Quantity Selector --}}
                        <div class="flex items-center space-x-4">
                            <label class="text-zinc-700 dark:text-zinc-300 font-medium">{{ __('product.quantity') }}:</label>
                            <div class="flex items-center border border-zinc-300 dark:border-zinc-600 rounded-lg overflow-hidden">
                                <button type="button" onclick="decrementQuantity()" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <input type="number" name="quantity" value="1" min="1" id="quantity-input"
                                       class="w-16 text-center border-0 focus:ring-0 dark:bg-zinc-800 dark:text-zinc-100" readonly />
                                <button type="button" onclick="incrementQuantity()" class="px-4 py-2 bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 dark:hover:bg-zinc-600 transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-8 py-4 bg-lime-500 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-lime-400 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            {{ __('product.add_to_cart') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        .animate-slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out forwards;
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }

        /* Image Zoom Effect */
        #product-image-container:hover #product-image {
            transform: scale(1.5);
        }

        #product-image-container {
            position: relative;
            overflow: hidden;
        }

        #product-image {
            transform-origin: center;
            cursor: zoom-in;
        }
    </style>

    <script>
        // Quantity selector functions
        function incrementQuantity() {
            const input = document.getElementById('quantity-input');
            input.value = parseInt(input.value) + 1;
        }

        function decrementQuantity() {
            const input = document.getElementById('quantity-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        // Advanced image zoom on mouse move
        const container = document.getElementById('product-image-container');
        const image = document.getElementById('product-image');

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            
            image.style.transformOrigin = `${x}% ${y}%`;
        });

        container.addEventListener('mouseleave', () => {
            image.style.transformOrigin = 'center';
        });
    </script>
</x-app-layout>