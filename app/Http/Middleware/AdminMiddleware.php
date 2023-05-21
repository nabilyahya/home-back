<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type == User::TYPE_ADMIN) {
            return $next($request);
        }

        return redirect('/'); // Redirect to the home page if not an admin
    }
}
