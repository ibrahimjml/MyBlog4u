<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SmtpController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:smtp.view')->only('smtp');
    $this->middleware('permission:smtp.update')->only(['smtp_config', 'testmail']);
  }
  public function smtp()
  {
    return view('admin.settings.smtp-settings', [
      'smtp' => SmtpSetting::first()
    ]);
  }
  public function smtp_config(Request $request)
  {
    $fields = $request->validate([
      'mail_transport' => ['sometimes'],
      'mail_host' => ['sometimes'],
      'mail_port' => ['sometimes'],
      'mail_username' => ['sometimes'],
      'mail_password' => ['sometimes'],
      'mail_encryption' => ['sometimes'],
      'mail_from' => ['sometimes'],

    ]);
    $smtp = SmtpSetting::firstOrNew();
    $smtp->fill($fields);
    $smtp->save();
    toastr()->success('smtp fields saved', ['timeOut' => 1000]);
    return redirect()->back();
  }
  public function testmail(Request $request)
  {
    $admin = User::where('is_admin', 1)->value('email');
    $message = 'this is a test mail';
    Mail::raw($message, function ($test) use ($admin) {
      $test->to($admin)->subject('Testing mail');
    });
    toastr()->success('mail sent', ['timeOut' => 1000]);
    return redirect()->back();
  }
}
