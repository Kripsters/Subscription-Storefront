<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('profile.shipping_address') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.shipping_address_subtext') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.shippingUpdate') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="line1" :value="__('profile.address-line-1')" />
            <x-text-input id="line1" name="line1" type="text" class="mt-1 block w-full" :value="old('address1', ($shipping_address) ? $shipping_address->line1 : '')" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="line2" :value="__('profile.address-line-2')" />
            <x-text-input id="line2" name="line2" type="text" class="mt-1 block w-full" :value="old('addres2s', ($shipping_address) ? $shipping_address->line2 : '')" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div>
            <x-input-label for="city" :value="__('profile.city')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', ($shipping_address) ? $shipping_address->city : '')" required autofocus autocomplete="city" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <div>
            <x-input-label for="state" :value="__('profile.state')" />
            <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', ($shipping_address) ? $shipping_address->state : '')" required autofocus autocomplete="state" />
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div>
            <x-input-label for="postal_code" :value="__('profile.zip')" />
            <x-text-input id="postal_code" name="postal_code" type="text" class="mt-1 block w-full" :value="old('postal_code', ($shipping_address) ? $shipping_address->postal_code : '')" required autofocus autocomplete="postal_code" />
            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
        </div>

        <div>
            <x-input-label for="country" :value="__('profile.country')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', ($shipping_address) ? $shipping_address->country : '')" required autofocus autocomplete="country" />
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