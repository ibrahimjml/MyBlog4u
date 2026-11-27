<?php

namespace App\Actions\ActiveSession;

use App\Contracts\Services\ActiveSessionService as ActiveSessionContract;
use App\DTOs\Auth\LoginContext;
use App\Enums\Auth\LoginResults;
use App\Events\ActiveSessionActivityEvent;
use App\Models\ActiveSession;
use App\Models\User;
use App\Support\BrowserTokenSupport;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class CreateActiveSessionAction
{
    private const HANDLED_FLAG = 'active_session_handled';

    public function __construct(
        private readonly ActiveSessionContract $sessions
    ){}

    public function handle(LoginContext $context, Closure $next): LoginContext
    {
        if ($context->result === LoginResults::SUCCESS) {
            $this->create(request());
        }

        return $next($context);
    }

    public function create(Request $request): void
    {
        if ($request->attributes->get(self::HANDLED_FLAG)) {
            return;
        }

        $request->attributes->set(self::HANDLED_FLAG, true);

        $user = Auth::user();

        if ($user === null) {
            return;
        }

        $browserToken = BrowserTokenSupport::fromRequest($request);

        if ($browserToken !== null && $this->hasSession($user, $browserToken)) {
            event(new ActiveSessionActivityEvent($user));

            return;
        }

        if ($browserToken === null) {
            $browserToken = BrowserTokenSupport::generate();

            $request->cookies->set(BrowserTokenSupport::COOKIE, $browserToken);

            Cookie::queue(BrowserTokenSupport::cookie($browserToken));
        }

        $this->sessions->record($user, $request, $browserToken);
    }

    private function hasSession(User $user, string $browserToken): bool
    {
        return ActiveSession::query()
            ->currentSession($user, BrowserTokenSupport::hash($browserToken))
            ->exists();
    }
}
