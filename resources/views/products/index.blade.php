<x-app-layout>
    @section('title', 'Products')
    <div class="pt-5 px-5">
        <form method="GET" action="{{ route('products.search') }}">
            <div class="flex items-center">
                <input type="text" name="search" value="{{ request()->input('search') }}"
                    placeholder="Search products..." class="w-full rounded-md border-zinc-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    <select name="order"
                        class="rounded-md border-zinc-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">Sort by</option>
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Z-A</option>
                    </select>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-zinc-200 dark:bg-zinc-200 border border-transparent rounded-md font-semibold text-xs text-zinc dark:text-zinc-800 uppercase tracking-widest hover:bg-zinc-400 dark:hover:bg-zinc-50 focus:bg-zinc-700 dark:focus:bg-zinc active:bg-zinc-900 dark:active:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-800 transition ease-in-out duration-150 ml-2">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
                        <path d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    @if ($products->count())
    <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-3 md:gap-4 pt-5 px-5">
        @foreach ($products as $item)
            <div class="block p-6 bg-zinc-100 rounded-lg border border-zinc-200 shadow hover:bg-zinc-50 dark:bg-zinc-800 dark:border-zinc-700 dark:hover:bg-zinc-700">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">
                    <a href="{{ route('products.show', $item->id) }}">{{ $item->title }}</a>
                </h5>
                <p class="font-normal text-zinc-700 dark:text-zinc-300">{{ Str::limit($item->description, 100) }}</p>
                <img class="rounded-lg shadow-lg mx-auto size-64" src="{{ asset($item->image) }}" alt="{{ 'an image of ' . $item->title }}"/>
                <p class="font-normal text-lime-500 dark:text-lime-500">${{ $item->price }}</p>
                @if (Auth::user()->role == 'admin')
                    <div class="flex justify-end mt-4 space-x-2">
                        <a href="{{ route('products.edit', $item->id) }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-zinc text-xs font-medium rounded-lg hover:bg-indigo-500 focus:ring-2 focus:ring-indigo-400 dark:bg-indigo-500 dark:hover:bg-indigo-400 focus:outline-none">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('products.delete', $item->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 text-zinc text-xs font-medium rounded-lg hover:bg-red-500 focus:ring-2 focus:ring-red-400 dark:bg-red-500 dark:hover:bg-red-400 focus:outline-none">
                                Delete
                            </button>
                        </form>
                    </div>
                @else
                    <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" class="inline-flex items-center px-3 py-3 bg-lime-400 text-zinc text-xs font-medium rounded-lg hover:bg-lime-500 focus:ring-2 focus:ring-lime-400 dark:bg-lime-500 dark:hover:bg-lime-400 focus:outline-none">
                            Add to cart
                        </button>
                    </form>
                        <!-- <a href="{{ route('cart.add', $item->id) }}" class="inline-flex items-center px-3 py-2 bg-lime-400 text-zinc text-xs font-medium rounded-lg hover:bg-lime-500 focus:ring-2 focus:ring-lime-400 dark:bg-lime-500 dark:hover:bg-lime-400 focus:outline-none">
                            Add to cart
                        </a> -->
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="text-center text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc py-10">
        No products found.
    </div>
@endif
</x-app-layout>
