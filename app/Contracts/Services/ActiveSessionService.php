<?php

namespace App\Contracts\Services;

use App\Models\ActiveSession;
use App\Models\User;
use Illuminate\Http\Request;

interface ActiveSessionService
{
    public function record(User $user, Request $request, string $browserToken): void;

    public function isBrowserRevoked(string $browserToken): bool;

    public function isRevoked(User $user, string $sessionId): bool;

    public function logout(ActiveSession $session, Request $request): void;
}
