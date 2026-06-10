<?php

namespace App\Services;

use App\Mail\VerificationCode;
use App\Models\IdentityVerification;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class IdentityCheckService
{
    public function IdentityCheck(User $user)
    {
         $code = Str::random(6);
         IdentityVerification::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
         ]);
          // mail to user verification code
          Mail::to($user->email)->queue(new VerificationCode($user, $code));

          return $code;

    }
}
