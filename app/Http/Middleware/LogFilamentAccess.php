<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogFilamentAccess
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin*')) {
            Log::info('Filament access attempt', [
                'url' => $request->fullUrl(),
                'user_id' => auth()->id(),
                'is_admin' => auth()->user()?->is_admin,
                'can_access' => auth()->user()?->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')),
                'session_id' => session()->getId(),
            ]);
        }
        
        return $next($request);
    }
}