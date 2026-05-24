<?php

namespace App\Http\Requests\App;

use App\Enums\ReportReason;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
         /** @var Post $post */
        $post = $this->route('post');

        return $post && $this->user()->can('report', $post);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
              'report_reason' => ['required', new Enum(ReportReason::class)],
              'other' => [
                 'nullable',
                 'string',
                 'required_if:report_reason,' . ReportReason::Other->value
                ],
        ];
    }
}
