<x-app-layout>
    <div class="py-4 sm:py-12">
    <div class="py-0 sm:py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
        
            <!-- Header Welcome -->
            <x-about-card :title="__('dashboard.welcome')" :subtext="__('dashboard.welcome_subtext')"> </x-about-card>

            
            <div class="relative rounded-2xl overflow-hidden shadow-lg hover:shadow-xl shadow-zinc-100 dark:shadow-zinc-900 transition-all duration-300">
                <!-- Subtle gradient border effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-zinc-200 via-zinc-100 to-zinc-200 dark:from-zinc-800 dark:via-zinc-850 dark:to-zinc-800"></div>
                
                <!-- Inner content with slight inset for border effect -->
                <div class="relative m-[1px] bg-white dark:bg-zinc-900 rounded-2xl">
                    <!-- Cards Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6 sm:p-8">
                        
                        <!-- Orders -->
                        <x-dash-button :href="route('subscription.index')" :title="__('dashboard.orders')" :subtext="__('dashboard.orders_subtext')" />
            
                        <!-- Products -->
                        <x-dash-button :href="route('products.index')" :title="__('dashboard.products')" :subtext="__('dashboard.products_subtext')" />
            
                        <!-- About Us -->
                        <x-dash-button :href="route('about')" :title="__('dashboard.about')" :subtext="__('dashboard.about_subtext')" />
            
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
