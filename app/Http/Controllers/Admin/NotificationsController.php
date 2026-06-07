<?php

namespace App\Http\Controllers\Admin;

use App\Enums\NotificationType;
use App\Http\Controllers\Controller;
use App\Services\Admin\NotificationService;
use Illuminate\Notifications\DatabaseNotification;

class NotificationsController extends Controller
{
  public function __construct(){
    $this->middleware('permission:notifications.view')->only('notifications');
  }
    public function notifications(NotificationService $service)
    {
    $sortoption = request()->get('sort');
    $filtertype = request()->get('type');

    $data = $service->sortNotifications($sortoption,$filtertype);
    return view('admin.notifications.admin-notifications', [
      'notifiedUsers'=>$data['notifiedUsers'],
      'notifications'=>$data['notifications'],
      'unreadCount'=>$data['unreadCount'],
    ]);
    }

    public function markasread($id){

      $notification = DatabaseNotification::findOrFail($id);

    if (is_null($notification->read_at)) {
        $notification->markAsRead();
    }

    $data = $notification->data;
    $type = $data['type'] ?? null;
    
    
    $username = collect($data)
        ->first(fn($val, $key) => str_contains($key, 'username'));
  
    if (in_array($type, array_map(fn($e) => $e->value, NotificationType::postRelated()), true)) {
        return to_route('single.post', $data['post_link']);
    } elseif (in_array($type, array_map(fn($e) => $e->value, NotificationType::userRelated()), true)) {
        return to_route('profile.home', $username);
    }

    }
}
