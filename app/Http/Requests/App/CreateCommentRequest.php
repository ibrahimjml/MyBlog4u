<?php

namespace App\Http\Requests\App;

use App\DTOs\CreateCommentDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCommentRequest extends FormRequest
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
protected function prepareForValidation(): void
{
    $this->merge([
        'content' => strip_tags((string) $this->input('content')),
    ]);
}
    public function rules(): array
    {
        return [
          'content'   => ['required','string','max:255'],
          'parent_id' => [Rule::requiredIf($this->routeIs('comment.reply')),'nullable','integer','exists:comments,id'] 
        ];
      
    }
    public function toDTO(int $postId): CreateCommentDTO
  {
    return new CreateCommentDTO(
        content: $this->input('content'),
        userId: $this->user()->id,
        postId: $postId,
        parentId: $this->input('parent_id')
    );
  }
}
