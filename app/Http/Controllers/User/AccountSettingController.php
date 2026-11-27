<?php

namespace App\Http\Controllers\User;

use App\DTOs\User\UpdateAccountDTO;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\UpdateAccountRequest;
use App\Rules\DeletionSentenceRule;
use App\Services\User\UpdateAccountService;
use App\Support\BrowserTokenSupport;
use Illuminate\Http\Request;

class AccountSettingController extends Controller
{
    public function __construct(
        protected UpdateAccountService $update
    ) {
        $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
    }

    /**
     * profile account management page
     **/
    public function profile_account()
    {
        return view('profile-settings.account-management', [
          'user' => request()->user()
        ]);
    }

    /**
     * update User Account management
     **/
    public function update_account(UpdateAccountRequest $request)
    {
        $user = $request->user();
        $dto = UpdateAccountDTO::fromRequest($request);
        $result = $this->update->update($user, $dto);
        if ($result['status'] === false) {
            toastr()->info($result['message'], ['timeOut' => 1000]);
        } else {
            toastr()->success($result['message'], ['timeOut' => 1000]);
        }

        return back();
    }

    /**
     * delete user account with hard clean all data
     **/
    public function deleteaccount(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'current_password' => 'required|current_password',
            'deletion_sentence' => ['required', new DeletionSentenceRule()],
        ]);
        $user->delete();
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        toastr()->success('Account deleted successfuly', ['timeOut' => 1000]);

        return redirect()->route('login')->withCookie(BrowserTokenSupport::forgetCookie());
    }
}
