<?php

namespace App\Services\User;

use App\DTOs\User\UpdateAccountDTO;
use App\Models\User;
use App\Services\IdentityCheckService;
use Illuminate\Support\Facades\Hash;

class UpdateAccountService
{
  public function __construct(private IdentityCheckService $check)
  {
  }
  public function update(User $user, UpdateAccountDTO $dto): array
  {
    $data = [];

    if (!empty($dto->username)) {
      $data['username'] = $dto->username;
    }

    if (!empty($dto->email)) {
      $data['email'] = $dto->email;
    }

    if (!empty($dto->password)) {
      $data['password'] = Hash::make($dto->password);
    }

    if (empty($data)) {
      return [
        'status' => false,
        'message' => 'Nothing changed to update',
      ];
    }

    $user->fill($data);

    if (!$user->isDirty()) {
      return [
        'status' => false,
        'message' => 'Nothing changed to update',
      ];
    }

    if ($user->isDirty('username') && $user->username_changed_at === null) {
      $user->username_changed_at = now();
    }

    if ($user->isDirty('email')) {
      $user->email_verified_at = null;
    }

    $user->save();

    if ($user->wasChanged('email')) {
      $user->sendEmailVerificationNotification();
    }

    if ($user->wasChanged('password')) {
      $this->check->IdentityCheck($user);
    }

    return [
      'status' => true,
      'message' => 'Account updated successfully.',
    ];
  }
}
