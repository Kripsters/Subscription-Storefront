<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Providers\RouteServiceProvider;
use Filament\Models\Contracts\FilamentUser;
use Filament\Facades\Filament;
use Illuminate\Support\Str;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $request->authenticate();
        $request->session()->regenerate();
    
        $user  = $request->user();
        $panel = Filament::getPanel('admin'); // <- your panel id
        $path  = '/'.trim($panel->getPath(), '/'); // e.g. '/admin'
    
        // Pull and validate the "intended" URL from the session.
        $intended = session()->pull('url.intended'); // remove it so it doesn't leak to future logins
        $intendedPath = $intended ? parse_url($intended, PHP_URL_PATH) : null;
    
        $intendedPointsToPanel = $intendedPath && Str::startsWith($intendedPath, $path);
        $userCanAccessPanel = $user->is_admin;
    
        // If intended points to the panel but the user can't access it -> drop it.
        if ($intendedPointsToPanel && $userCanAccessPanel==false) {
            // dd('user cant access panel');
            $intended = null;
        }
    
        if ($user->is_admin == 'true') {
            // dd('user can access panel');
            // Admins: honor intended (if present), else go to panel home.
            return redirect()->to($intended ?: $panel->getUrl());
            // or: return redirect()->intended($panel->getUrl());
        }
    
        // Non-admins: go to app home (and *not* to the panel)
        // dd('non admin');
        return redirect()->to($intended ?: RouteServiceProvider::HOME);
    
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
