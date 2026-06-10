<?php

namespace App\Services\Admin;

use App\DTOs\Admin\CreateUserDTO;
use App\DTOs\Admin\UpdateUserDTO;
use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Fluent;

class UsersService
{
    public function getUsers(array $filters)
    {
        $filter = new Fluent($filters);

  return User::with(['roles','roles.permissions','userPermissions','activation']) 
                ->withCount(['reportsSubmitted', 'reportsReceived','followings','followers','post'])
               ->latest()
               ->filter($filter)
               ->paginate(6)
               ->withQueryString();
    }

    public function createUser(CreateUserDTO $dto):User
    {

    $user = User::create([
         'name' => $dto->name,
         'username' => $dto->username,
         'email' => $dto->email,
         'phone' => $dto->phone,
         'age' => $dto->age,
        'password' => Hash::make($dto->password),
      ]);
      $this->syncRolesAndPermissions($user,$dto->roles,$dto->permissions ?? []);
      return $user;
    }

    public function updateUser(User $user,UpdateUserDTO $dto):User
    {
      $data =[
         'name' => $dto->name,
         'username' => $dto->username,
         'email' => $dto->email,
         'phone' => $dto->phone,
         'age' => $dto->age,
      ];
      if($dto->password){
        $data['password'] = Hash::make($dto->password);
      }

      $user->update($data);

       if ($dto->roles) {
            $this->syncRolesAndPermissions($user, $dto->roles, $dto->permissions ?? []);
        }

        return $user;
    }

    public function toggleStatus(User $user): bool
    {
      return $user->update(['is_blocked' => !$user->is_blocked]);
    }

    public function changeRole(User $user,string $roleName)
    {
       $role = Role::where('name', $roleName)->firstOrFail();
        $user->roles()->sync([$role->id]);
        
        if ($role->name !== UserRole::USER) {
            $user->userPermissions()->detach();
        }
        
    }

    public function deleteUser(User $user): bool|null
    {
       return $user->delete();
    }

    private function syncRolesAndPermissions(User $user,UserRole $role,array $permissions)
    {
       $roles = Role::where('name', $role->value)->first();

       $user->roles()->sync($roles->id);
        
        if ($role === UserRole::USER) {
            $user->userPermissions()->sync($permissions);
        } else {
            $user->userPermissions()->detach();
        }
    }
    }


