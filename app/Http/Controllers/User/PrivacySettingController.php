<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;

class PrivacySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
    }

    /**
     * account privacy page
     **/
    public function account_privacy()
    {
        return view('profile-settings.account-privacy', [
          'user' => request()->user()
        ]);
    }

    public function profile_visibility()
    {
        $visibility = request()->boolean('visibility', true);
        $user = request()->user();
        $user->profile()->update(['is_public' => $visibility]);
        if ($user->profile->is_public === false) {
            toastr()->info('profile is now private', ['timeOut' => 1000]);
        } else {
            toastr()->success('profile is now public', ['timeOut' => 1000]);
        }

        return back();
    }
}
