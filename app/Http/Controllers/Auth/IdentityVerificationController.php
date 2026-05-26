<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Models\IdentityVerification;
use App\Rules\Recaptcha;
use Illuminate\Http\Request;

class IdentityVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified',CheckIfBlocked::class]);
    }
    public function showVerification()
{
    return view('auth.identity-verification');
}

public function verifyCode(Request $request)
{
    $request->validate([
      'code' => 'required|string',
      "g-recaptcha-response" => [new Recaptcha]
      ]);

    $verification = IdentityVerification::where('user_id', auth()->id())
        ->where('code', $request->code)
        ->where('expires_at', '>', now())
        ->first();

    if (!$verification) {
        toastr()->error('Invalid or expired code.', ['timeOut'=>1000]);
        return back();
    }

    $verification->delete();
    toastr()->success('Identity verified successfully', ['timeOut'=>1000]);
    return redirect()->route('blog');
}
}
