<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class DynamicRateLimitMiddleware
{

  public function handle(Request $request, Closure $next): Response
  {
    $routeName = $request->route()?->getName();

    if (!$routeName) {
      return $next($request);
    }

    $limits = Cache::rememberForever('api_rate_limits.active',
      fn() => \App\Models\ApiRateLimit::where('status', \App\Enums\ApiLimits\ApiLimitStatus::ACTIVE->value)
        ->get()
        ->keyBy('route_name'));

    $limit = $limits->get($routeName);

    if (!$limit) {
      return $next($request);
    }

    $isMethodMatch = strtoupper($request->method()) === strtoupper($limit->method->value);


    if (!$isMethodMatch) {
      return $next($request);
    }

    $key = $routeName . '|' . ($request->user()?->id ?? $request->ip());

    if (RateLimiter::tooManyAttempts($key, $limit->max_attempts)) {
      $message = "Too many attempts. Try again in {$limit->time_window} minute(s).";

      if ($request->expectsJson()) {
        return response()->json(['message' => $message], 429);
      }

      toastr()->warning($message);
      return redirect()->back();
    }

    RateLimiter::hit($key, $limit->time_window * 60);

    return $next($request);
  }
}
