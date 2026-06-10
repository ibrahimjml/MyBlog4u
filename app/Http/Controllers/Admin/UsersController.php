<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\Admin\CreateUserDTO;
use App\DTOs\Admin\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\Admin\{CreateUserRequest, UpdateUserRequest};
use App\Mail\ActivationSuccess;
use App\Models\{Activation, Permission, Role, User};
use App\Services\Admin\UsersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
     public function __construct(private UsersService $service){
        $this->middleware('permission:user.view')->only('users');
        $this->middleware('permission:user.create')->only('createUser');
     }
      public function users(Request $request)
  {
    $users = $this->service->getUsers($request->only('search','blocked','sort','activate'));
    $roles = Role::all();
    $pendingActivations = Activation::where('completed', false)->count();
    return view('admin.users.users',[
      'users'=>$users,
      'filter'=>$request->only(['search','blocked','sort','activate']),
      'activationCount' => $pendingActivations,
      'permissions' => Permission::all()->groupBy('module'),
      'roles'=>$roles
    ]);
  }
public function createUser(CreateUserRequest $request)
{
    try{

    $dto = CreateUserDTO::fromRequest($request);
    $this->service->createUser($dto);
    // clear cached roles and permissions
    Cache::tags(['user_permissions','has_any_role'])->flush();
    toastr()->success('user created',['timeOut'=>1000]);
    return back();
    }catch (\Exception $e) {
        Log::error('User creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        toastr()->error('Failed to create user', ['timeOut' => 3000]);
        return back()->withInput();
    }
}
public function updateUser(UpdateUserRequest $request, User $user)
{
      $this->authorize('updateAny',$user);
      $dto = UpdateUserDTO::fromRequest($request);
      $this->service->updateUser($user,$dto);

      Cache::tags(['user_permissions','has_any_role'])->flush();

      if ($request->wantsJson()) {
          return response()->json(['message' => 'User updated successfully']);
      }

      // toastr()->success('user updated',['timeOut'=>1000]);
      // return back();
}
  
  public function toggle(User $user){
    
    $this->authorize('block',$user);
    $this->service->toggleStatus($user);
    
    $message = $user->fresh()->is_blocked ? 'User blocked successfully' : 'User unblocked successfully';
    
    toastr()->success($message,['timeOut'=>1000]);
    return redirect()->back();
  }
public function activateUser(User $user)
{
  $this->authorize('activate',$user);
     $user->activation->update([
       'completed' => true,
       'completed_at' => now(),
   ]);
   toastr()->success('user activated',['timeOut'=>1000]);
   // send approve mail to user
   Mail::to($user->email)->queue(new ActivationSuccess($user));
   return back();
}
  public function destroy(User $user){

    $this->authorize('deleteAny',$user);
     $this->service->deleteUser($user);
    Cache::tags(['user_permissions','has_any_role'])->flush();
    toastr()->success('user deleted',['timeOut'=>1000]);
    return back();
  }
  public function role(Request $request,User $user)
  {
    $this->authorize('role',$user);

    $fields= $request->validate([
      'role'=>['required','exists:roles,name']
    ]);
    
    $this->service->changeRole($user, $fields['role']);
    Cache::tags(['user_permissions','has_any_role'])->flush();
    toastr()->success("user role '{$fields['role']}' updated",['timeOut'=>1000]);
    return back();
  }
}
