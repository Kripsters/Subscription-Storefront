<x-app-layout>
    <div class="py-4 sm:py-12">
    <div class="py-0 sm:py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
        
            <!-- Header Welcome -->
            <x-about-card :title="__('dashboard.welcome')" :subtext="__('dashboard.welcome_subtext')"> </x-about-card>

            
            <div class="bg-gradient-to-r rounded-2xl from-zinc-200 to-zinc-100 dark:from-zinc-800 dark:to-zinc-900 overflow-hidden shadow-xl hover:shadow-2xl shadow-zinc-800 hover:shadow-zinc-900 dark:shadow-zinc-800 sm:rounded-2xl dark:hover:shadow-zinc-700 dark:hover:shadow-xl transition">
                <!-- Cards Section -->
                <div class="bg-white dark:bg-zinc-900 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                    
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
</x-app-layout>
