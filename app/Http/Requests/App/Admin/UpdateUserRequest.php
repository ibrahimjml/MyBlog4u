<?php

namespace App\Http\Requests\App\Admin;

use App\Enums\UserRole;
use App\Rules\EmailProviders;
use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var \App\Models\User $user */
        $user = $this->route('user');

        return [
              "email" => ["nullable", "email", Rule::unique("users", "email")->ignore($user->id),new EmailProviders()],
              "name" => ["nullable", "min:5", "max:50", "alpha"],
              "username" => ["nullable", "min:5", "max:15", "alpha_num", Rule::unique('users', 'username')->ignore($user->id)],
              "phone" => ["nullable",'regex:/^\+\d{8,15}$/',Rule::unique("users", "phone")->ignore($user->id)],
              "password" => ["nullable","confirmed",new PasswordRule()],
              "age" => ["nullable", "numeric", "between:18,64"],
              "roles" => ["nullable", new Enum(UserRole::class)],
              "permissions" => ["nullable", "array"],
              "permissions.*" => ["integer","exists:permissions,id"],
        ];
    }
}
