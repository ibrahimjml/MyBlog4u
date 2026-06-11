<?php

namespace App\Http\Requests\App\Admin;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;



class CreateApiLimitRequest extends FormRequest
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
    $id = $this->isMethod('PUT') ? $this->route('limit')?->id : null;

    return [
          'route_name'   => ['required', 'string', 'max:255', Rule::unique('api_rate_limits', 'route_name')->ignore($id)],
          'max_attempts' => ['required', 'integer', 'min:1'],
          'time_window'  => ['required', 'integer', 'min:1'],
          'description'  => ['nullable', 'string', 'max:255'],
          'status'       => ['required', 'string', new Enum(\App\Enums\ApiLimits\ApiLimitStatus::class)],
          'method'       => ['required', 'string', new Enum(\App\Enums\ApiLimits\ApiLimitMethod::class)],
    ];
  }
  public function messages(): array
  {
    return [
      'route_name.required'    => 'Route name is required',
      'route_name.string'      => 'Route name must be a string',
      'route_name.max'         => 'Route name must not exceed 255 characters',
      'route_name.unique'      => 'An API limit for this route already exists',
      'max_attempts.required'  => 'Max attempts is required',
      'max_attempts.integer'   => 'Max attempts must be an integer',
      'max_attempts.min'       => 'Max attempts must be at least 1',
      'time_window.required'   => 'Time window is required',
      'time_window.integer'    => 'Time window must be an integer',
      'time_window.min'        => 'Time window must be at least 1 minute',
      'description.string'     => 'Description must be a string',
      'description.max'        => 'Description must not exceed 255 characters',
      'status.required'        => 'Status is required',
      'status.string'          => 'Status must be a string',
      'method.required'        => 'Method is required',
      'method.string'          => 'Method must be a string',
    ];
  }
}
