<?php

namespace App\Http\Requests\App;

use App\Builders\UserBuilder;
use App\Models\User;
use App\Rules\MaxImageUpload;
use App\Rules\NotInBuiltinLinks;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateUserProfileRequest extends FormRequest
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
      $imageRule = ['sometimes','nullable','image','mimes:png,jpeg,jpg,webp', new MaxImageUpload()];

        return [
            'avatar'   => $imageRule,
            'cover'    => $imageRule,
            "name"     => ["sometimes","nullable", "min:3", "max:50", "alpha"],
            "phone"    => ["sometimes","nullable", 'regex:/^\+\d{8,15}$/', Rule::unique(User::class)->ignore($user->id)],
            'bio'      =>'sometimes|nullable|min:5|string',
            'aboutme'    =>'sometimes|nullable|string|min:10',
            'github'   => 'sometimes|nullable|url|starts_with:https',
            'linkedin' => 'sometimes|nullable|url|starts_with:https',
            'twitter'     => 'sometimes|nullable|url|starts_with:https',
            'social_links' => 'sometimes|nullable|array|max:' . UserBuilder::MAX_CUSTOM_SOCIAL_LINKS,
            'social_links.*.platform' => [
                'required',
                'string',
                'max:50',
                'distinct',
                new NotInBuiltinLinks($user),
            ],
            'social_links.*.url' => [
                'required',
                'url',
                'starts_with:https',
                new NotInBuiltinLinks($user),
            ],

        ];
    }
    public function messages()
    {
        return [
            'phone.regex' => 'The phone number must include a valid country code.',
            'avatar.image' => 'The avatar must be an image file.',
            'avatar.mimes' => 'The avatar must be a PNG, JPG, JPEG, or WEBP.',
            'cover.image' => 'The cover must be an image file.',
            'cover.mimes' => 'The cover must be a PNG, JPG, JPEG, or WEBP.',
            'social_links.*.platform.required' => 'the platform is required',
            'social_links.*.platform.distinct' => 'each platform can only be added once',
            'social_links.max' => 'you can add up to ' . UserBuilder::MAX_CUSTOM_SOCIAL_LINKS . ' custom links',
            'social_links.*.url.required' => 'the url is required',
            'social_links.*.url.url' => 'the url is not valid',
        ];
    }
}
