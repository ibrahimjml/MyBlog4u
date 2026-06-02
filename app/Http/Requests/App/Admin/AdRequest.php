<?php

namespace App\Http\Requests\App\Admin;

use App\Enums\Adplacements\AdStatus;
use App\Enums\Adplacements\AdType;
use App\Enums\Adplacements\AdPosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class AdRequest extends FormRequest
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
    $type = $this->input('ad_type');

    return [
      'ad_name' => ['required', 'string', 'max:255'],
      'ad_type' => ['required', Rule::enum(AdType::class)],
      'ad_position' => ['required', Rule::enum(AdPosition::class)],
      'ad_dimension' => ['nullable', 'string', 'max:255'],
      'ad_code' => [
        Rule::requiredIf($type === AdType::GOOGLE_ADSENSE->value),
        'nullable',
        'string',
      ],
      'image' => ['nullable', 'image', 'max:2048'],
      'remove_image' => ['nullable', 'boolean'],
      'link_url' => ['nullable', 'url', 'max:2048'],
      'status' => ['required', Rule::enum(AdStatus::class)],

    ];
  }
  public function messages(): array
  {
    return [
      'ad_name.required' => 'Please enter an ad name',
      'ad_type.required' => 'Please select an ad type',
      'ad_position.required' => 'Please select an ad position',
      'status.required' => 'Please select a status for the ad',

    ];
  }
}
