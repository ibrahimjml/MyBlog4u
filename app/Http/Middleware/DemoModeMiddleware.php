<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoModeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (! config('demo.enabled')) {
            return $next($request);
        }

        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'demo_mode' => true,
                'type' => 'error',
                'message' => 'Demo mode: this action is disabled.',
            ], 403);
        }
        return back();
    }
}
