<?php

namespace App\Http\Requests\App;

use App\Enums\ProfileArticleSort;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileHomeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sort'      => ['sometimes', 'nullable', Rule::enum(ProfileArticleSort::class)],
            'perpage'   => ['sometimes', 'integer', 'min:1', 'max:50'],
            'home_page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
