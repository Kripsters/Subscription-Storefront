<div class="py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="bg-gradient-to-r from-zinc-200 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden shadow-xl dark:shadow-zinc-800 sm:rounded-2xl hover:shadow-2xl transition">
            <div class="p-8 border-b border-zinc-300 dark:border-zinc-700 flex items-center space-x-6">
                <x-application-logo class="hidden sm:block h-24 w-24 flex-shrink-0 fill-current text-zinc-800 dark:text-zinc-200 animate-pulse" />
                <div>
                    <p class="text-xl font-semibold text-zinc-900 dark:text-zinc-100">
                        {{ $title }}
                    </p>
                    <p class="text-zinc-600 dark:text-zinc-400">
                        {{ $subtext }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>