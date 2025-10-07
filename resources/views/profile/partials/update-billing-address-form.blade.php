<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('profile.billing_address') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.billing_address_subtext') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.billingUpdate') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="line1" :value="__('profile.address-line-1')" />
            <x-text-input id="line1" name="line1" type="text" class="mt-1 block w-full" :value="old('address', $billing_address->line1)" required autofocus autocomplete="address_line1" />
            <x-input-error class="mt-2" :messages="$errors->get('address1')" />
        </div>

        <div>
            <x-input-label for="line2" :value="__('profile.address-line-2')" />
            <x-text-input id="line2" name="line2" type="text" class="mt-1 block w-full" :value="old('address', $billing_address->line2)" required autofocus autocomplete="address_line2" />
            <x-input-error class="mt-2" :messages="$errors->get('address2')" />
        </div>

        <div>
            <x-input-label for="city" :value="__('profile.city')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $billing_address->city)" required autofocus autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <x-input-label for="state" :value="__('profile.state')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $billing_address->state)" required autofocus autocomplete="state" />
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div>
            <x-input-label for="zip" :value="__('profile.zip')" />
            <x-text-input id="zip" name="zip" type="text" class="mt-1 block w-full" :value="old('zip', $billing_address->postal_code)" required autofocus autocomplete="postal_code" />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <x-input-label for="country" :value="__('profile.country')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $billing_address->country)" required autofocus autocomplete="country" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('profile.save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('profile.saved') }}</p>
            @endif
        </div>
    </form>
</section>