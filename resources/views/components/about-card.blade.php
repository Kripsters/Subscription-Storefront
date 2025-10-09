
<div class="py-4 sm:py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="group relative bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl shadow-zinc-100 dark:shadow-zinc-900 transition-all duration-300 border border-zinc-200 dark:border-zinc-800">
            <!-- Subtle gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-50/50 to-transparent dark:from-zinc-800/30 dark:to-transparent pointer-events-none"></div>
            
            <!-- Content -->
            <div class="relative p-6 sm:p-8 flex items-center gap-6">
                <div class="hidden sm:flex items-center justify-center h-16 w-16 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                    <x-application-logo class="h-10 w-10 fill-current text-zinc-700 dark:text-zinc-300" />
                </div>
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1">
                        {{ $title }}
                    </h3>
                    <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400">
                        {{ $subtext }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>