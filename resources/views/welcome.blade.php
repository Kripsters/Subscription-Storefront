<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 leading-tight">
            {{ __('Welcome') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-zinc dark:bg-zinc-900  overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-zinc-800 dark:text-zinc-400">
                    <a href="{{route('login')}}">Login</a>
                </div>
                <div class="p-6 text-zinc-800 dark:text-zinc-400">
                    <a href="{{route('register')}}">Register</a>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>