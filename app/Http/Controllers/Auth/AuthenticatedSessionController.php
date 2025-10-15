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
use Illuminate\Support\Facades\Log;


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

        // $request->authenticate();


        // $request->session()->regenerate();

        // $user = $request->user();


        // // If user can access the Filament admin panel, redirect there:
        // if ($user->isAdmin()) {
        //     return redirect()->intended(route('admin.dashboard'));

        // }


        // return redirect()->intended(route('dashboard', absolute: false));

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
            Log::warning('User '.$user->id.' tried to access '.$intended.' but cannot access the admin panel');
            $intended = null;
        }
    
        if ($user->is_admin == 'true') {
            // Admins: honor intended (if present), else go to panel home.
            Log::info('Admin user '.$user->id.' logged in', [
                'intended' => $intended,
                'panel_url' => $panel->getUrl(),
                'redirect_to' => $intended ?: $panel->getUrl(),
                'is_admin_value' => $user->is_admin,
                'is_admin_type' => gettype($user->is_admin),
                'can_access_panel' => method_exists($user, 'canAccessPanel') ? $user->canAccessPanel($panel) : 'method not found'
            ]);
            return redirect()->to($intended ?: $panel->getUrl());
            // or: return redirect()->intended($panel->getUrl());
        }
    
        // Non-admins: go to app home (and *not* to the panel)
        Log::info('Non-admin user '.$user->id.' logged in, redirecting to dashboard');
        return redirect()->intended(route('dashboard', absolute: false));
    
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
