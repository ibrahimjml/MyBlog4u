<?php

namespace App\Http\Requests\App\Admin;

use App\Enums\MediaDriver;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\RequiredIf;



class MediaSettingRequest extends FormRequest
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
       'media_driver' => ['required', new Enum(MediaDriver::class)],

       'media_aws_access_key_id' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::AWS->value],
       'media_aws_secret_key' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::AWS->value],
       'media_aws_default_region' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::AWS->value],
       'media_aws_bucket' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::AWS->value],
       'media_aws_url' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::AWS->value],
       'media_aws_endpoint' => ['nullable', 'string'],
       'media_aws_use_path_style_url' => ['nullable','boolean'],

       'media_do_access_key_id' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::DIGITAL_OCEAN->value],
       'media_do_secret_key' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::DIGITAL_OCEAN->value],
       'media_do_default_region' => ['nullable', 'string', 'size:4', 'required_if:media_driver,' . MediaDriver::DIGITAL_OCEAN->value],
       'media_do_bucket' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::DIGITAL_OCEAN->value],
       'media_do_endpoint' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::DIGITAL_OCEAN->value],
       'media_do_use_path_style_url' => ['nullable','boolean'],

       'media_r2_access_key_id' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::CLOUDFLARE_R2->value],
       'media_r2_secret_key' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::CLOUDFLARE_R2->value],
       'media_r2_bucket' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::CLOUDFLARE_R2->value],
       'media_r2_endpoint' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::CLOUDFLARE_R2->value],
       'media_r2_url' => ['nullable', 'string', 'required_if:media_driver,' . MediaDriver::CLOUDFLARE_R2->value],
       'media_r2_use_path_style_url' => ['nullable','boolean']

    ];
  }

}
