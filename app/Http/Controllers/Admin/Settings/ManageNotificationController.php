<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\AdminNotificationSetting;
use Illuminate\Http\Request;

class ManageNotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:notifications.view')->only('notification_view');
    $this->middleware('permission:notifications.update')->only('toggle_notification');
  }
  public function notification_view()
  {
    $setting = AdminNotificationSetting::where('user_id', auth()->id())->pluck('is_enabled', 'type')->toArray();
    return view('admin.settings.notification-control', [
      'setting' => $setting,
    ]);
  }
  public function toggle_notification(Request $request)
  {
    $request->validate([
      'notifications' => ['nullable', 'array'],
    ]);

    $notifications = $request->input('notifications', []);


    foreach (\App\Enums\NotificationType::cases() as $type) {
      $setting = AdminNotificationSetting::where('user_id', auth()->id())->where('type', $type->value)->first();
      $enabled = isset($notifications[$type->value]) ? 1 : 0;
      $setting->update(
        [
          'is_enabled' => $enabled,
        ]
      );
    }

    toastr()->success('Notification settings updated', ['timeOut' => 1000]);
    return redirect()->back();
  }
}
