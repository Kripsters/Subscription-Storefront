@props(['id', 'title', 'price', 'subtext', 'error' => false])

<div class="w-full max-w-sm bg-white dark:bg-zinc-800 shadow-lg rounded-2xl p-8 text-center">
    <h2 class="text-2xl font-bold mb-3 {{ $error ? 'text-red-600' : 'text-zinc-900 dark:text-zinc-100' }}">
        {{ $title }}
    </h2>

    @if (! $error)
        <p class="text-zinc-600 dark:text-zinc-400 mb-2">
            €{{ number_format($price, 2) }} / {{ __('cart.month') }}
        </p>
        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
            {{ $subtext }}
        </p>

            <button
            class="px-4 py-2 bg-indigo-600 text-white rounded-md"
            @if($error) disabled @endif
            data-subscribe
            data-plan="{{ $id }}"
            id="subscribe-button-{{ $id }}"
            >
            {{ $error ? __('cart.unavailable') : __('cart.subscribe') }}
            </button>

      
    @else
        <p class="text-zinc-600 dark:text-zinc-400">
            {{ $subtext }}
        </p>
    @endif
</div>