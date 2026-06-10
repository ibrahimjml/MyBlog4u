<?php

namespace App\DTOs\Admin;

use App\Enums\UserRole;
use App\Http\Requests\App\Admin\CreateUserRequest;


class CreateUserDTO
{
    public function __construct(
      public readonly string $name,
      public readonly string $email,
      public readonly string $username,
      public readonly string $password,
      public readonly int $age,
      public readonly ?string $phone,
      public readonly UserRole $roles,
      public readonly ?array $permissions =[],
    ){}

    public static function fromRequest(CreateUserRequest $request):self{
     return new self(
            name: $request->validated('name'),
            username: $request->validated('username'),
            age: $request->validated('age'),
            phone: $request->validated('phone'),
            email: $request->validated('email'),
            password: $request->validated('password'),
            roles: UserRole::from($request->validated('roles')),
            permissions: $request->validated('permissions')
     );
    }
}
