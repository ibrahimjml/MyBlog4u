<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Auth\LoginResults;
use App\Exceptions\Auth\LoginException;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Auth\LoginRequest;
use App\Services\Auth\LoginUserService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
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
                      : ($user->is_admin || $user->hasPermission('Access')
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

  public function logout()
  {
    auth()->logoutCurrentDevice();

    session()->invalidate();
    session()->regenerateToken();
    toastr()->success('Logged out ', ['timeOut' => 1000]);
    return to_route('login');
  }
}
