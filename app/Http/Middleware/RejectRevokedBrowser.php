<?php

namespace App\Http\Middleware;

use App\Actions\ActiveSession\KickRevokedBrowserAction;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectRevokedBrowser
{
    public function __construct(
        private readonly KickRevokedBrowserAction $kickRevoked
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($response = $this->kickRevoked->handle($request)) {
            return $response;
        }

        return $next($request);
    }
}
