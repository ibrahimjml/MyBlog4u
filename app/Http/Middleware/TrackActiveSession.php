<?php

namespace App\Http\Middleware;

use App\Contracts\Services\ActiveSessionService as ActiveSessionContract;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackActiveSession
{
    private const BROWSER_TOKEN_COOKIE = 'active_session_browser';

    public function __construct(private readonly ActiveSessionContract $sessions)
    {}

    public function handle(Request $request, Closure $next): Response
    {
        $browserToken = $request->cookie(self::BROWSER_TOKEN_COOKIE);

        if (is_string($browserToken) && $this->sessions->isBrowserRevoked($browserToken)) {
            Auth::logoutCurrentDevice();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            toastr()->warning('This browser session has been logged out.', ['timeOut' => 2000]);

            return redirect()->route('login')
                ->withCookie(cookie()->forget(self::BROWSER_TOKEN_COOKIE));
        }

        $newBrowserToken = ! is_string($browserToken) || $browserToken === '';
        $browserToken = $newBrowserToken ? Str::random(64) : $browserToken;

        if (Auth::check() && $this->sessions->isRevoked(Auth::user(), $request->session()->getId())) {
            Auth::logoutCurrentDevice();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            toastr()->warning('This browser session has been logged out.', ['timeOut' => 2000]);
            return redirect()->route('login');
        }

        $response = $next($request);

        if (Auth::check()) {
            $this->sessions->record(Auth::user(), $request, $browserToken);
        }

        if ($newBrowserToken) {
            $response->withCookie(cookie(
                self::BROWSER_TOKEN_COOKIE,
                $browserToken,
                60 * 24 * 365,
                config('session.path'),
                config('session.domain'),
                config('session.secure'),
                true,
                false,
                config('session.same_site'),
            ));
        }

        return $response;
    }
}
