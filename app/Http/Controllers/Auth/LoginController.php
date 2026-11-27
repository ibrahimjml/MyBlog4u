<?php

namespace App\Http\Controllers\Auth;

use App\Actions\ActiveSession\LogoutCurrentDeviceAction;
use App\Actions\ActiveSession\MarkActiveSessionLoggedOutAction;
use App\Enums\Auth\LoginResults;
use App\Exceptions\Auth\LoginException;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Auth\LoginRequest;
use App\Services\Auth\LoginUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __construct(
        private readonly MarkActiveSessionLoggedOutAction $markLoggedOut,
        private readonly LogoutCurrentDeviceAction $logoutCurrentDevice,
    ) {
    }

    public function login(LoginRequest $request, LoginUserService $loginService): JsonResponse
    {
        try {
            $result = $loginService->handleLogin($request->toDTO());
            $require_2fa = $result === LoginResults::TWO_FACTOR_REQUIRED;

            $user = auth()->user();

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully.',
                'require_2fa' => $require_2fa,
                'redirect' => $require_2fa
                              ? route('2fa.confirmation')
                              : ($user->is_admin
                                ? route('admin.panel')
                                : route('home')),
            ]);
        } catch (LoginException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $this->markLoggedOut->handle($user);
            
        $this->logoutCurrentDevice->handle($request);

        toastr()->success('Logged out ', ['timeOut' => 1000]);

        return to_route('login');
    }
}
