<?php 
return [
    'enabled' => env('IS_DEMO', false),
    'admin' => [
        'username' => env('ADMIN_USERNAME', false),
        'password' => env('ADMIN_PASS', false),
    ],
    'user' => [
        'username' => env('USER_USERNAME', false),
        'password' => env('USER_PASS', false),
    ],
];