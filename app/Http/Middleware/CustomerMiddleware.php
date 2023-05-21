<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type == User::TYPE_CUSTOMER) {
            return $next($request);
        }

        return redirect('/'); // Redirect to the home page if not a customer
    }
}
