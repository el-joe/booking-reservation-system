<?php

declare(strict_types=1);

namespace App\Http\Middleware\Central;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('central')->check()) {
            return redirect()->route('central.login');
        }

        return $next($request);
    }
}
