<?php

namespace App\Http\Requests\App\Admin;

use App\Rules\AnalyticsCredentialRule;
use Illuminate\Foundation\Http\FormRequest;

class AnalyticsSettingRequest extends FormRequest
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
            'analytics_property_id' => ['nullable', 'string', 'size:9'],
            'analytics_service_account_credentials' => ['nullable', new AnalyticsCredentialRule()],
            'analytics_dashboard_widgets' => ['nullable','boolean'],
        ];
    }

    public function propertyId()
    {
      return $this->get('analytics_property_id');
    }
    public function serviceAccountCredentials()
    {
      return $this->get('analytics_service_account_credentials');
    }
    public function dashboardWidgets()
    {
      return $this->get('analytics_dashboard_widgets');
    }
}
