<?php

namespace App\Actions\ActiveSession;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutCurrentDeviceAction
{
    public function handle(Request $request): void
    {
        Auth::logoutCurrentDevice();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
