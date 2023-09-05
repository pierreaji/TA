<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class OnlyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = Auth::user();
        if (!in_array($auth->role, ['Admin', 'Sales'])) {
            Session::flash('alert', 'error');
            Session::flash('title', 'Failed!');
            Session::flash('message', 'You not have access for this feature!');
            return to_route('dashboard');
        }
        return $next($request);
    }
}
