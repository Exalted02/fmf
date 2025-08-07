<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'You do not have permission to access this page');
        }
        return $next($request);
    }
}
