<x-app-layout>
    <div class="flex items-center justify-center h-screen bg-red-300 dark:bg-red-600">
        <div class="max-w-md w-full p-8 bg-zinc-100 dark:bg-zinc-800 rounded-2xl shadow-lg text-center">
            <div class="text-red-600 dark:text-red-400 text-6xl animate-pulse mb-4">
                ❌
            </div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mb-2">
                {{ __('profile.handle_cancel') }}
            </h1>
        </div>
    </div>
</x-app-layout>