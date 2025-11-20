<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminIp;

class IpWhitelistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            $userIp = $request->ip();
            $allowedIps = AdminIp::where('user_id', Auth::id())->pluck('ip')->toArray();

            if (!in_array($userIp, $allowedIps)) {
                return response()->json(['message' => 'IP not allowed'], 403);
            }
        }

        return $next($request);
    }
}
