<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetUserTimezone
{
    public function handle(Request $request, Closure $next): Response
    {
        $timezone = 'UTC';

        if (auth()->check()) {
            $timezone = auth()->user()->timezone ?: 'UTC';
        }

        View::share('userTimezone', $timezone);

        return $next($request);
    }
}
