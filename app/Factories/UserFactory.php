<?php

namespace App\Factories;

use App\Models\User;

class UserFactory
{
    public function create(array $data): User
    {
        return new User([
            'email' => trim(strip_tags($data['email'])),
            'name' => trim(strip_tags($data['name'])),
            'username' => trim(strip_tags($data['username'])),
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
