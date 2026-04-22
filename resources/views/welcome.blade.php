<x-guest-layout>
    <div class="min-h-screen bg-white dark:bg-zinc-950 font-sans flex flex-col w-full">
    
        {{-- ── NAV ── --}}
        <header class="fixed top-0 inset-x-0 z-50 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-zinc-100 dark:border-zinc-800">
            <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between gap-4">
    
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200 animate-pulse" />
                    <span class="text-xl font-black tracking-tight text-zinc-900 dark:text-white">
                        {{ __('stockedup.p1') }}<span class="text-amber-400">{{ __('stockedup.p2') }}</span>
                    </span>
                </a>


    
                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Language switcher --}}
                    <div class="flex items-center gap-1">
                        @foreach (['en' => 'EN', 'lv' => 'LV'] as $locale => $label)
                            <a href="{{ route('lang.switch', $locale) }}"
                               class="px-2.5 py-1 text-xs font-bold rounded transition
                                      {{ app()->getLocale() === $locale
                                          ? 'bg-amber-400 text-white'
                                          : 'text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
    
                    {{-- Login --}}
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-1.5 px-4 py-2 text-sm font-bold rounded-xl border-2 border-amber-400 text-amber-500 hover:bg-amber-400 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
                        </svg>
                        {{ __('welcome.login') }}
                    </a>
    
                    {{-- Dark mode toggle --}}
                    <button id="theme-toggle"
                            class="p-2 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition text-sm">
                        <span id="theme-icon-light" class="hidden">🌙</span>
                        <span id="theme-icon-dark"  class="hidden">🌞</span>
                    </button>
                </div>
            </div>
        </header>
    
        {{-- ── HERO ── --}}
        <section class="relative overflow-hidden bg-amber-400 dark:bg-amber-500 pt-16">
            {{-- Decorative blobs --}}
            <div class="absolute -top-20 -right-20 w-96 h-96 rounded-full bg-amber-300/50 dark:bg-amber-400/30 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full bg-orange-400/40 blur-2xl pointer-events-none"></div>
    
            <div class="max-w-7xl mx-auto px-6 py-20 lg:py-28 flex flex-col lg:flex-row items-center gap-12">
    
                {{-- Left copy --}}
                <div class="flex-1 text-white z-10">
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black leading-none tracking-tight mb-4">
                        {{ __('welcome.forgot') }}<br>{{ __('welcome.milk') }}
                    </h1>
                    <p class="text-amber-100 text-lg mb-10 max-w-sm">
                        {{ __('welcome.hero_desc') }}
                    </p>
                </div>
    
                {{-- Hero food image --}}
                <div class="flex-1 flex justify-center lg:justify-end z-10">
                    <div class="relative w-72 h-72 sm:w-96 sm:h-96 rounded-full overflow-hidden shadow-2xl border-4 border-white/30 ring-8 ring-amber-300/30">
                        <img src="https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=600&q=80"
                             alt="Delicious ramen bowl"
                             class="w-full h-full object-cover"/>
                    </div>
                </div>
            </div>
        </section>
    
        {{-- ── FOOD CATEGORY CARDS ── --}}
        <section class="bg-white dark:bg-zinc-950 py-16 px-6">
            <div class="max-w-7xl mx-auto">
    
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <p class="text-amber-500 font-bold text-sm uppercase tracking-widest mb-1">{{ __('welcome.explore') }}</p>
                        <h2 class="text-3xl font-black text-zinc-900 dark:text-white">{{ __('welcome.popular') }}</h2>
                    </div>
                </div>
    
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    @php
                        $categories = [
                            ['label' => 'Salads',  'img' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=400&q=80', 'color' => 'from-green-400/20'],
                            ['label' => 'Pasta',   'img' => 'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?w=400&q=80', 'color' => 'from-yellow-400/20'],
                            ['label' => 'Bowls',   'img' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&q=80', 'color' => 'from-orange-400/20'],
                            ['label' => 'Soups',   'img' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=400&q=80', 'color' => 'from-red-400/20'],
                        ];
                    @endphp
    
                    @foreach ($categories as $cat)
                        <div class="group relative overflow-hidden rounded-2xl aspect-square cursor-pointer shadow-md hover:shadow-xl transition-shadow">
                            <img src="{{ $cat['img'] }}" alt="{{ $cat['label'] }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"/>
                            <div class="absolute inset-0 bg-gradient-to-t {{ $cat['color'] }} to-black/50"></div>
                            <span class="absolute bottom-4 left-4 text-white font-black text-lg drop-shadow">
                                {{ $cat['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    
        {{-- ── HOW IT WORKS ── --}}
        <section class="bg-amber-50 dark:bg-zinc-900 py-20 px-6">
            <div class="max-w-7xl mx-auto text-center mb-12">
                <p class="text-amber-500 font-bold text-sm uppercase tracking-widest mb-2">{{ __('welcome.simple_steps') }}</p>
                <h2 class="text-3xl font-black text-zinc-900 dark:text-white">{{ __('welcome.hiw_title') }}</h2>
            </div>
    
            <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                @php
                    $steps = [
                        ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                         'title' => __('welcome.hiw_p1_title'), 'desc' => __('welcome.hiw_p1_desc')],
                        ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                         'title' => __('welcome.hiw_p2_title'),    'desc' => __('welcome.hiw_p2_desc')],
                        ['icon' => 'M1 3h13v13H1V3zM14 8h4l3 3v5h-7V8zM5.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z',
                         'title' => __('welcome.hiw_p3_title'),   'desc' => __('welcome.hiw_p3_desc')],
                    ];
                @endphp
    
                @foreach ($steps as $i => $step)
                    <div class="flex flex-col items-center gap-4">
                        <div class="relative w-20 h-20 flex items-center justify-center rounded-2xl bg-amber-400 shadow-lg shadow-amber-200 dark:shadow-amber-900/40">
                            <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/>
                            </svg>
                            <span class="absolute -top-2 -right-2 w-6 h-6 rounded-full bg-red-500 text-white text-xs font-black flex items-center justify-center shadow">
                                {{ $i + 1 }}
                            </span>
                        </div>
                        <h3 class="text-lg font-black text-zinc-900 dark:text-white">{{ $step['title'] }}</h3>
                        <p class="text-sm text-zinc-500 dark:text-zinc-400 max-w-xs">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    
        {{-- ── CTA REGISTER ── --}}
        <section class="bg-zinc-950 dark:bg-white py-20 px-6 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle at 20% 50%, #f59e0b 0%, transparent 50%), radial-gradient(circle at 80% 50%, #ef4444 0%, transparent 50%);">
            </div>
            <div class="relative z-10 max-w-xl mx-auto">
                <p class="text-amber-400 font-bold text-sm uppercase tracking-widest mb-3">{{ __('welcome.new_here') }}</p>
                <h2 class="text-4xl sm:text-5xl text-white dark:text-zinc-900 font-black mb-4 leading-tight">
                    {{ __('welcome.join') }} <span class="text-amber-400">{{ __('stockedup.name') }}</span> {{ __('welcome.today') }}
                </h2>
                <p class="text-zinc-400 mb-8">{{ __('welcome.reg_desc') }}</p>
                <a href="{{ route('register') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-amber-400 hover:bg-amber-300 active:scale-95 text-zinc-900 text-base font-black rounded-2xl transition shadow-lg shadow-amber-400/30">
                    {{ __('welcome.register') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </section>
    
        {{-- ── FOOTER ── --}}
        <footer class="bg-white dark:bg-zinc-950 border-t border-zinc-100 dark:border-zinc-800 py-8 px-6">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-zinc-400">&copy; {{ date('Y') }} {{ config('app.name') }}.</p>
            </div>
        </footer>
    
    </div>
    
    {{-- ── JS: dark mode + delivery tab toggle ── --}}
    <script>
        // Dark mode
        const html = document.documentElement;
        const iconLight = document.getElementById('theme-icon-light');
        const iconDark  = document.getElementById('theme-icon-dark');
    
        function applyTheme(dark) {
            dark ? html.classList.add('dark') : html.classList.remove('dark');
            if (iconLight) iconLight.classList.toggle('hidden', dark);
            if (iconDark)  iconDark.classList.toggle('hidden', !dark);
        }
    
        const saved = localStorage.getItem('theme');
        applyTheme(saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches));
    
        document.getElementById('theme-toggle')?.addEventListener('click', () => {
            const isDark = html.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            applyTheme(!isDark);
        });
    
        // Delivery / Pickup tab
        function setTab(tab) {
            const active   = 'bg-amber-50 text-amber-600 border-amber-400';
            const inactive = 'text-zinc-500 dark:text-zinc-400 border-transparent hover:border-zinc-200 dark:hover:border-zinc-700';
    
            const delivery = document.getElementById('tab-delivery');
            const pickup   = document.getElementById('tab-pickup');
    
            if (tab === 'delivery') {
                delivery.className = delivery.className.replace(inactive, '') + ' ' + active;
                pickup.className   = pickup.className.replace(active, '')   + ' ' + inactive;
            } else {
                pickup.className   = pickup.className.replace(inactive, '') + ' ' + active;
                delivery.className = delivery.className.replace(active, '') + ' ' + inactive;
            }
        }
    </script>
    </x-guest-layout>