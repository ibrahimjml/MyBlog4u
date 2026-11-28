<?php

namespace App\Http\Controllers\User;

use App\Actions\User\UpdateProfileInfoAction;
use App\DTOs\User\UpdateUserProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\UpdateUserProfileRequest as UserInfoRequest;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Storage;

class InfoSettingController extends Controller
{
    public function __construct(
        protected UpdateProfileInfoAction $action
    ) {
        $this->middleware(['auth', 'verified', CheckIfBlocked::class]);
    }

    /**
     * profile info page
     **/
    public function profile_info()
    {
        return view('profile-settings.profile-info', [
          'user' => request()->user()
        ]);
    }

    /**
     * update profile info
     **/
    public function update_info(UserInfoRequest $request)
    {
        $user = $request->user();
        $dto = UpdateUserProfileDTO::fromRequest($request);
        $userupdated = $this->action->execute($user, $dto);
        if ($userupdated) {
            toastr()->success('user info updated successfuly', ['timeOut' => 1000]);
        } else {
            toastr()->info('Nothing chnaged to update', ['timeOut' => 1000]);
        }

        return back();
    }

    /**
     * delete avatar photo
     **/
    public function delete_avatar()
    {
        $user = request()->user();
        if ($user->avatar !== 'default.jpg' && Storage::disk(media_driver())->exists("avatars/{$user->avatar}")) {
            Storage::disk(media_driver())->delete("avatars/{$user->avatar}");
        }
        $user->avatar = 'default.jpg';
        $user->save();
        toastr()->success('Avatar deleted', ['timeOut' => 1000]);

        return back();
    }

    /**
     * delete cover photo
     **/
    public function delete_cover()
    {
        $user = request()->user();
        if ($user->cover_photo !== 'sunset.jpg' && Storage::disk(media_driver())->exists("covers/{$user->cover_photo}")) {
            Storage::disk(media_driver())->delete("covers/{$user->cover_photo}");
        }
        $user->cover_photo = 'sunset.jpg';
        $user->save();
        toastr()->success('Cover deleted', ['timeOut' => 1000]);

        return back();
    }

    /**
     * delete custom link
     **/
    public function destroy_link($id)
    {
        $link = SocialLink::findOrFail($id);
        $this->authorize('deleteSocial', $link);

        $link->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
