<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthSecurityController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:authsecurity.view')->only('auth_settings');
    $this->middleware('permission:authsecurity.update')->only('update_auth_settings');
  }

  public function auth_settings()
  {
    $settings = \App\Models\AuthSecurityRule::first();
    return view('admin.settings.auth-security-rules', [
      'settings' => $settings
    ]);
  }
  public function update_auth_settings(Request $request)
  {
    $fields = $request->validate([
      'require_captcha' => ['required', 'boolean'],
      'recaptcha_site_key' => ['nullable', 'string'],
      'recaptcha_secret_key' => ['nullable', 'string'],
      'enable_registration' => ['required', 'boolean'],
      'allowed_email_domains' => ['nullable', 'string']
    ]);

    $settings = \App\Models\AuthSecurityRule::firstOrNew();
    $settings->require_captcha = $fields['require_captcha'];
    $settings->recaptcha_sitekey = $fields['recaptcha_site_key'] ?? null;
    $settings->recaptcha_secretkey = $fields['recaptcha_secret_key'] ?? null;
    $settings->allow_registration = $fields['enable_registration'];
    $settings->allowed_email_domains = collect(explode(',', $fields['allowed_email_domains'] ?? ''))
      ->map(fn($domain) => trim($domain))
      ->filter()
      ->implode(', ');
    $settings->save();

    toastr()->success('Authentication settings updated', ['timeOut' => 1000]);
    return redirect()->back();
  }
}
