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
        if ($address) {
            $billing_address = $address->billing;
            $shipping_address = $address->shipping;
        } else {
            Address::create([
                'user_id' => auth()->id(),
                'billing' => [],
                'shipping' => [],
            ]);
            $billing_address = null;
            $shipping_address = null;
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
        $addressData = array_diff_key($request->all(), array_flip(['_token', '_method']));
        $address = Address::where('user_id', auth()->id())->first();
        if ($address) {
            $address->billing = $addressData;
            $address->save();
        } else {
            Address::create([
                'user_id' => auth()->id(),
                'billing' => $addressData,
                'shipping' => [],
            ]);
        }
        return Redirect::route('profile.edit')->with('status', 'billing-updated');
    }

    public function shippingUpdate(Request $request)
    {
        $addressData = array_diff_key($request->all(), array_flip(['_token', '_method']));
        $address = Address::where('user_id', auth()->id())->first();
        if ($address) {
            $address->shipping = $addressData;
            $address->save();
        } else {
            Address::create([
                'user_id' => auth()->id(),
                'shipping' => $addressData,
                'billing' => [],
            ]);
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
