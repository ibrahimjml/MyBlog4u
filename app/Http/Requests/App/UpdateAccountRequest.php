<?php

namespace App\Http\Requests\App;

use App\Models\User;
use App\Rules\EmailProviders;
use App\Rules\PasswordRule;
use App\Rules\Username;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateAccountRequest extends FormRequest
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
      $user = $this->user();
        return [
            'username' => [
            "required", "min:5", "max:15", "alpha_num",
             Rule::unique(User::class)->ignore($user->id),
             new Username($user)
            ],
            'email' => [
            "required", "email", "min:5", "max:50", 
            Rule::unique(User::class)->ignore($user->id),
            new EmailProviders()
          ],
            "password" => ["nullable", "confirmed", new PasswordRule()],
            "current_password" => ["required_with:password", "current_password"]
        ];
    }
}
