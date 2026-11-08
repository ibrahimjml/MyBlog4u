<?php

namespace App\Http\Requests\App;

use App\Enums\ReportReason;
use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Post|Comment|User|null $reportable */
        $reportable = $this->route('post')
            ?? $this->route('comment')
            ?? $this->route('user');

        if (! $reportable || ! $this->user()) {
            return false;
        }

        if ($reportable instanceof Post || $reportable instanceof Comment) {
            if ((int) $reportable->user_id === (int) $this->user()->id) {
                return false;
            }
        }

        if ($reportable instanceof User) {
            if ($this->user()->is($reportable)) {
                return false;
            }

            if ($reportable->is_admin || $reportable->hasRole(UserRole::ADMIN->value)) {
                return false;
            }

            return true;
        }

        return $this->user()->can('report', $reportable);
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
