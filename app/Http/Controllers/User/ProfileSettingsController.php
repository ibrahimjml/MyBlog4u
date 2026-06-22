<?php

namespace App\Http\Controllers\User;

use App\DTOs\User\UpdateAccountDTO;
use App\DTOs\User\UpdateUserProfileDTO;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\UpdateAccountRequest;
use App\Http\Requests\App\UpdateUserProfileRequest as UserInfoRequest;
use App\Models\SocialLink;
use App\Rules\DeletionSentenceRule;
use App\Services\User\UpdateAccountService;
use App\Services\User\UpdateProfileInfoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileSettingsController extends Controller
{
    public function __construct(
      protected UpdateProfileInfoService $service,
      protected UpdateAccountService $update
      )
    {
      $this->middleware(['auth','verified',CheckIfBlocked::class]);
    }
    /**
      * profile info page
      **/
    public function profile_info()
    {
      return view('profile-settings.profile',
      $this->data('profile-info'));
    }
    /**
      * update profile info 
      **/
    public function update_info(UserInfoRequest $request)
    {  
       $user = $request->user();
       $dto = UpdateUserProfileDTO::fromRequest($request);
      $userupdated =  $this->service->update($user,$dto);
      if($userupdated){
      toastr()->success('user info updated successfuly',['timeOut'=>1000]);
      }else{
      toastr()->info('Nothing chnaged to update',['timeOut'=>1000]);
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
    $user->avatar="default.jpg";
    $user->save();
    toastr()->success('Avatar deleted',['timeOut'=>1000]);
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
      toastr()->success('Cover deleted',['timeOut'=>1000]);
      return back();
    }
    /**
      * delete custom link
      **/
    public function destroy_link( $id)
     {
      $link = SocialLink::findOrFail($id);
      $this->authorize('deleteSocial',$link);

     $link->delete();

    return response()->json(['message' => 'Deleted']);
    }
    /**
      * profile account management page 
      **/
    public function profile_account()
    {
      return view('profile-settings.profile',
       $this->data( 'profile-account'));
    }
    /**
      * account privacy page 
      **/
    public function account_privacy()
    {
      return view('profile-settings.profile',
       $this->data( 'profile-privacy'));
    }
    public function profile_visibility()
    {
      
      $visibility = request()->boolean('visibility',true);
      $user = request()->user();
      $user->profile()->update(['is_public' => $visibility]);
       if($user->profile->is_public === false){
      toastr()->info('profile is now private',['timeOut'=>1000]);
     }else{
       toastr()->success('profile is now public',['timeOut'=>1000]);
     }
      return back();
    }
    public function two_factor_view()
    {
      return view('profile-settings.profile',$this->data('security'));
    
    }
    /**
      * update User Account management
      **/
    public function update_account(UpdateAccountRequest $request)
    {
      $user = $request->user();
      $dto = UpdateAccountDTO::fromRequest($request);
      $result =  $this->update->update($user,$dto);
     if($result['status'] === false){
      toastr()->info($result['message'],['timeOut'=>1000]);
     }else{
       toastr()->success($result['message'],['timeOut'=>1000]);
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
        'deletion_sentence' => ['required',new DeletionSentenceRule()]
       ]);
         $user->delete();
         auth()->logout();
         session()->invalidate();
         session()->regenerateToken();
         toastr()->success('Account deleted successfuly',['timeOut'=>1000]);
         return redirect()->route('login');
    }
    /**
      * return  user + section
      **/
    private function data(string $section)
    {
      return [
        'user' => request()->user(),
        'section' => $section,
      ];
    }
}
