<?php

namespace App\Http\Requests\App;

use App\Enums\Blog\BlogSort;
use App\Enums\Blog\BlogTab;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
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
            'tab' => ['nullable', Rule::enum(BlogTab::class)],
            'sort' => ['nullable', Rule::enum(BlogSort::class)],
            'from' => ['nullable', 'date'],
            'to'   => ['nullable', 'date', 'after_or_equal:from'],
            'perpage' => ['nullable', 'integer', 'min:1', 'max:50'],
            'blog_page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
         return [
            'to.after_or_equal' => 'Please pick a date after start date.',
        ];
    }

     public function resolvedTab(): BlogTab
    {
        return BlogTab::tryFrom((string) $this->input('tab')) ?? BlogTab::FOR_YOU;
    }
}
