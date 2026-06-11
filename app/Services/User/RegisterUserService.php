<?php

namespace App\Services\User;

use App\Events\NewRegistered;
use App\Factories\UserFactory;
use App\Mail\WelcomeNewUser;
use App\Models\Activation;
use App\Models\Role;
use Illuminate\Support\Facades\Mail;
class RegisterUserService
{
    protected $factory;
    public function __construct(UserFactory $factory){
        $this->factory = $factory;
    }
    public function register(array $data) : array
    {
       $user = $this->factory->create($data);
       $user->save();

       $role = Role::firstOrCreate(['name' => 'User']);
       $user->roles()->syncWithoutDetaching([$role->id]);
   
       Activation::create([
       'user_id' => $user->id,
      ]);
      
    Mail::to($user->email)->queue(new WelcomeNewUser($user));
    // notify admin with new user
    event(new NewRegistered($user));

    return [
      'user' => $user,
      'success' => 'your account registered, we will email you once activated.'
    ];
    }
}
