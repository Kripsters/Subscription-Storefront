<x-app-layout>
    <x-slot name="header">
        <header class="bg-gradient-to-r from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-extrabold text-zinc-800 dark:text-zinc-100 tracking-tight">
                    {{ $product->title }}
                </h1>
                <div class="flex items-center space-x-4 mt-4">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-zinc-700 text-gray-600 dark:text-gray-300 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.293 12.95l.707.071-.707.071L15.657 8.027l-.707.071-.707.071L9.293 5.676l.292.682 4.978c-.025-.516-.502-1.055-.502-1.055l-.292.682-.292.682-.707.071-.707.071L5.636 8.027l-.707.071.707.071 1.414 1.414l.292.682.292.682c.502.502 1.055.502 1.055l.292.682.292.682.707.071.707.071L9.293 12.95z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2">{{ __('product.back') }}</span>
                    </a>
                </div>
            </div>
        </header>
    </x-slot>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            
            {{-- Product Image --}}
            <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition duration-300 mb-10 md:mb-0">
                <img src="{{ $product->image }}" 
                     alt="{{ __('product.img_alt') . $product->title }}" 
                     class="w-full h-auto object-cover">
            </div>

            {{-- Product Info --}}
            <div class="flex flex-col space-y-6 space-x-6 border border-zinc-200 dark:border-zinc-700 p-6 rounded-3xl bg-white dark:bg-zinc-800 shadow-lg">
                <div>
                    <h2 class="text-4xl font-bold text-zinc-800 dark:text-zinc-100 mb-4">
                        {{ $product->title }}
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{ $product->description }}
                    </p>
                </div>

                <div class="text-3xl font-extrabold text-zinc-800 dark:text-white">
                    {{ $product->price }} &euro;
                </div>

                {{-- Actions --}}
                <div>
                    @if ($isActive)
                    <form method="POST" action="{{ route('subscription.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1"
                               class="w-16 rounded-md border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-lime-500 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-lime-400 hover:shadow-xl transition duration-300 ease-in-out">
                            {{ __('subscription.add_to_cart') }}
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('cart.add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="number" name="quantity" value="1" min="1"
                               class="w-16 rounded-md border-zinc-300 shadow-sm focus:border-indigo-400 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                        <button type="submit"
                            class="inline-flex items-center px-8 py-3 bg-lime-500 text-white text-lg font-semibold rounded-2xl shadow-lg hover:bg-lime-400 hover:shadow-xl transition duration-300 ease-in-out">
                            {{ __('product.add_to_cart') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
</x-app-layout>

