<x-guest-layout>
    <div class="w-full flex justify-center">
        
        <div class="w-full max-w-md">
            <a href="{{ route('welcome') }}" class="shrink-0 flex items-center mb-8">
                <x-application-logo class="block h-9 w-auto fill-current text-zinc-800 dark:text-zinc-200 animate-pulse" />
                <span class="text-xl font-black tracking-tight text-zinc-900 dark:text-white">
                    {{ __('stockedup.p1') }}<span class="text-amber-400">{{ __('stockedup.p2') }}</span>
                </span>
            </a>
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('welcome.forgot_message') }}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('welcome.email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button>
                        {{ __('welcome.email_link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
