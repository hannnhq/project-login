<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Require2FAConfirmation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $confirmed = session()->get('2fa_sensitive_confirmed', false);
    Log::debug('[Middleware] 2FA sensitive confirmed?', ['2fa_sensitive_confirmed' => $confirmed]);

    if (!$confirmed) {
        Log::debug('[Middleware] Chưa xác thực 2FA sensitive, redirect đến form 2FA');
        session(['url.intended' => $request->fullUrl()]);
        return redirect()->route('2fa.form');
    }

    Log::debug('[Middleware] 2FA đã xác thực, tiếp tục request');
        return $next($request);
    }
}
