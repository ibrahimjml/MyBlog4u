<?php

namespace App\Http\Requests\App;

use App\Enums\PostStatus;
use App\Rules\ValidHashtag;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
        return [

        'title' => 'nullable|string|regex:/^[A-Za-z0-9\s]+$/|max:50|min:6',
        'short_excerpt' => 'required|string|max:200|min:10',
        'description' => 'required|string',
        'categories' => 'sometimes|nullable|array|min:1|max:4', 
        'categories.*' => 'integer|exists:categories,id',
        'hashtag' => ['nullable', 'string', new ValidHashtag(5)],
        'image' => 'sometimes|image|mimes:jpg,png,jpeg|max:5120',
        'status' => ['required', Rule::in(array_keys(PostStatus::forUserCreation()))],
        'enabled' => 'sometimes|nullable|boolean',
        'featured' => 'sometimes|nullable|boolean'
        ];
    }
    public function messages()
    {
        return [
            'title.regex' => 'The title may only contain letters, numbers, and spaces.',
            'categories.max' => 'Categories are greater than 4'
        ];
    }
}
