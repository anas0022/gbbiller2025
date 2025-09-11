<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminIP
{
    public function handle(Request $request, Closure $next)
    {
        $allowedIp = '127.0.0.1'; // Your machine IP

        // Check if user is logged in
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Check biller_code and IP
        if (Auth::user()->biller_code !== 'BILL000' || $request->ip() !== $allowedIp) {
            abort(403, 'This device is not authorized.');
        }

        return $next($request);
    }
}
