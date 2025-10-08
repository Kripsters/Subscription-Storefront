<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Address;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $address = Address::where('user_id', auth()->id())->first();
        if (isset($address->billing)) {
        $billing_address = json_decode($address->billing);
        }
        
        if (isset($address->shipping)) {
        $shipping_address = json_decode($address->shipping);
        }
        
        return view('profile.edit', [
            'user' => $request->user(),
            'billing_address' => $billing_address,
            'shipping_address' => $shipping_address
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function billingUpdate(Request $request): RedirectResponse
    {
        $addressJson = json_encode(array_diff_key($request->all(), array_flip(['_token', '_method'])));
        $address = Address::where('user_id', auth()->id())->first();
        if ($address) {
            $address->billing = $addressJson;
            $address->save();
        } else {
            $newAddress = new Address();
            $newAddress->user_id = auth()->id();
            $newAddress->billing = $addressJson;
            $newAddress->shipping = '{}'; // Initialize shipping as empty JSON
            $newAddress->save();
        }
        return Redirect::route('profile.edit')->with('status', 'billing-updated');
    }

    public function shippingUpdate(Request $request)
    {
        $addressJson = json_encode(array_diff_key($request->all(), array_flip(['_token', '_method'])));
        $address = Address::where('user_id', auth()->id())->first();
        if ($address) {
            $address->shipping = $addressJson;
            $address->save();
        } else {
            $newAddress = new Address();
            $newAddress->user_id = auth()->id();
            $newAddress->shipping = $addressJson;
            $newAddress->billing = '{}'; // Initialize billing as empty JSON
            $newAddress->save();
        }
        return Redirect::route('profile.edit')->with('status', 'shipping-updated'); 
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
