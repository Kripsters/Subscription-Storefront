<?php use App\Models\Subscription; ?>
<nav x-data="{ open: false }"
     class="sticky top-0 z-50 border-0 bg-zinc-50/70 dark:bg-zinc-900/60 backdrop-blur-md supports-[backdrop-filter]:bg-zinc-50/70 ring-1 ring-zinc-900/10 dark:ring-white/10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <!-- Left: Logo + Primary links -->
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="shrink-0 flex items-center">
                    <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200 animate-pulse" />
                </a>

                <!-- Primary Navigation Links -->
                @php
                    $base = 'relative inline-flex items-center rounded-full px-3 py-2 text-sm font-medium transition-colors duration-200 ' .
                            'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white ' .
                            'hover:bg-zinc-950/5 dark:hover:bg-white/5 focus:outline-none focus-visible:ring-2 ' .
                            'focus-visible:ring-indigo-500/60';
                    $active = 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ' .
                              'ring-zinc-900/10 dark:ring-white/10';
                @endphp

                <div class="hidden sm:flex sm:items-center sm:gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="{{ request()->routeIs('dashboard') ? "$base $active" : $base }}">
                        {{ __('navigation.dashboard') }}
                    </a>
                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? "$base $active" : $base }}">
                        {{ __('navigation.about') }}
                    </a>
                    <a href="{{ route('products.index') }}"
                       class="{{ (request()->routeIs('products.index') || request()->routeIs('products.search')) ? "$base $active" : $base }}">
                        {{ __('navigation.products') }}
                    </a>
                    <a href="{{ route('cart.index') }}"
                       class="{{ request()->routeIs('cart.index') ? "$base $active" : $base }}">
                        {{ __('navigation.cart') }}
                    </a>
                    <a href="{{ route('subscription.index') }}"
                       class="{{ request()->routeIs('subscription.index') ? "$base $active" : $base }}">
                        {{ __('navigation.subscription') }}
                    </a>
                    @if (Subscription::billingSubscription())
                    <a href="{{ route('subscription.cart') }}"
                    class="{{ request()->routeIs('subscription.cart') ? "$base $active" : $base }}">
                     {{ __('navigation.subscription-cart') }}
                 </a>
                 @endif
                </div>
            </div>

            <!-- Right: User dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-64">
                                    
                    <!-- Dark mode toggle -->
                    <button id="theme-toggle" 
                        class="p-2 rounded-lg bg-zinc-200 dark:bg-zinc-800 text-zinc-800 dark:text-zinc-200 hover:bg-zinc-300 dark:hover:bg-zinc-600 transition sm:mx-12">
                        <span id="theme-toggle-light" class="hidden">🌙</span>
                        <span id="theme-toggle-dark" class="hidden">🌞</span>
                    </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-full ps-3 pe-2 py-1.5 text-sm font-medium 
                                   text-zinc-600 dark:text-zinc-300 
                                   hover:text-zinc-900 dark:hover:text-white 
                                   bg-white/60 dark:bg-zinc-800/60
                                   ring-1 ring-zinc-900/10 dark:ring-white/10 
                                   hover:bg-zinc-50/80 dark:hover:bg-zinc-800
                                   transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60">
                            <span class="truncate max-w-[10rem]">{{ Auth::user()->name }}</span>
                            <svg class="ms-0.5 h-4 w-4 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="sr-only">{{ __('navigation.user_menu') }}</span>
                        </button>
                    </x-slot>



                    <x-slot name="content">
                        <x-dropdown-link class="block px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5" href="{{ route('lang.switch', 'en') }}">
                            {{ __('English') }}
                        </x-dropdown-link>
    
                        <x-dropdown-link class="block px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5" href="{{ route('lang.switch', 'lv') }}">
                            {{ __('Latviešu') }}
                        </x-dropdown-link>

                        <x-dropdown-link class="block px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5" :href="route('profile.edit')">
                            {{ __('navigation.profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link class="block px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5" :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('navigation.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                        :aria-expanded="open"
                        aria-controls="mobile-menu"
                        class="inline-flex items-center justify-center rounded-md p-2 
                               text-zinc-500 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white
                               hover:bg-zinc-950/5 dark:hover:bg-white/5 
                               focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/60 transition">
                    <span class="sr-only">{{ __('navigation.menu') }}</span>
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="sm:hidden border-t border-zinc-200/50 dark:border-zinc-700/50">
        <div class="space-y-1 px-4 py-3">
            <a href="{{ route('dashboard') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium
                      {{ request()->routeIs('dashboard')
                        ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                        : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
                {{ __('navigation.dashboard') }}
            </a>
            <a href="{{ route('about') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium
                      {{ request()->routeIs('about')
                        ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                        : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
                {{ __('navigation.about') }}
            </a>
            <a href="{{ route('products.index') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium
                      {{ (request()->routeIs('products.index') || request()->routeIs('products.search'))
                        ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                        : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
                {{ __('navigation.products') }}
            </a>
            <a href="{{ route('cart.index') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium
                      {{ request()->routeIs('cart.index')
                        ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                        : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
                {{ __('navigation.cart') }}
            </a>
            <a href="{{ route('subscription.index') }}"
               class="block rounded-lg px-3 py-2 text-sm font-medium
                      {{ request()->routeIs('subscription.index')
                        ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                        : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
                {{ __('navigation.subscription') }}
            </a>
            @if (Subscription::billingSubscription())
            <a href="{{ route('subscription.cart') }}"
            class="block rounded-lg px-3 py-2 text-sm font-medium
                   {{ request()->routeIs('subscription.cart')
                     ? 'bg-zinc-950/5 dark:bg-white/5 text-zinc-900 dark:text-white ring-1 ring-inset ring-zinc-900/10 dark:ring-white/10'
                     : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white hover:bg-zinc-950/5 dark:hover:bg-white/5' }}">
             {{ __('navigation.subscription-cart') }}
         </a>
         @endif
        </div>

        <!-- Responsive Settings -->
        <div class="border-t border-zinc-200/50 dark:border-zinc-700/50 px-4 py-4">
            <div class="mb-3">
                <div class="font-medium text-base text-zinc-800 dark:text-zinc-100">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-zinc-500 dark:text-zinc-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium
                          text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white
                          hover:bg-zinc-950/5 dark:hover:bg-white/5">
                    {{ __('navigation.profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block rounded-lg px-3 py-2 text-sm font-medium
                              text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white
                              hover:bg-zinc-950/5 dark:hover:bg-white/5">
                        {{ __('navigation.logout') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
