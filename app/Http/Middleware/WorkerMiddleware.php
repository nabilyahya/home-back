<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->type == User::TYPE_WORKER) {
            return $next($request);
        }

        return redirect('/'); // Redirect to the home page if not a worker
    }
}
