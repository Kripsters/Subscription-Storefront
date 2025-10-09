<div class="py-4 sm:py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="group relative bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-xl shadow-zinc-100 dark:shadow-zinc-900 transition-all duration-300 border border-zinc-200 dark:border-zinc-800" 
             id="animated-card">
            <!-- Subtle gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-50/50 to-transparent dark:from-zinc-800/30 dark:to-transparent pointer-events-none"></div>
            
            <!-- Content -->
            <div class="relative p-6 sm:p-8 flex items-center gap-6">
                <div class="hidden sm:flex items-center justify-center h-16 w-16 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex-shrink-0 group-hover:scale-105 transition-transform duration-300"
                     id="animated-logo">
                    <x-application-logo class="h-10 w-10 fill-current text-zinc-700 dark:text-zinc-300" />
                </div>
                
                <div class="flex-1 min-w-0" id="animated-content">
                    <h3 class="text-lg sm:text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1"
                        id="animated-title">
                        {{ $title }}
                    </h3>
                    <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400" id="horizontal-split">
                        {{ $subtext }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
<script>
// Cool staggered entrance animation
anime.timeline({
    easing: 'easeOutExpo'
})
.add({
    targets: '#animated-card',
    opacity: [0, 1],
    translateY: [40, 0],
    duration: 1200,
})
.add({
    targets: '#animated-logo',
    opacity: [0, 1],
    scale: [0.5, 1],
    rotate: [-180, 0],
    duration: 800,
}, '-=800') // Start 800ms before previous animation ends
.add({
    targets: '#animated-title',
    opacity: [0, 1],
    translateX: [-30, 0],
    duration: 600,
}, '-=600')
.add({
    targets: '#horizontal-split',
    opacity: [0, 1],
    translateX: [-30, 0],
    duration: 600,
}, '-=400');
</script>