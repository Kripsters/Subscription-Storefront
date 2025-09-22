<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-zinc-800 dark:text-zinc-200 leading-tight">
            {{ __('stockedup.name') }}
        </h2>
    </x-slot>

    <x-about-card :title="__('about.about')" :subtext="__('about.about_subtext')"> </x-about-card>

    <x-about-card :title="__('about.contact')" :subtext="__('about.contact_subtext')"> </x-about-card>
    
</x-app-layout>
