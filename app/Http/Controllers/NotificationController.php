<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckIfBlocked;
use App\Services\AuthReadNotificationService;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth','verified',CheckIfBlocked::class]);
  }
  
    public function markasread($id,AuthReadNotificationService $read){
  
      return $read->readNotications($id);
  }
  public function markallasread()
  {
      $notifications = auth()->user()->unreadNotifications;
      $notifications->markAsRead();
      toastr()->success('Notifications marked all as read',['timeOut'=>1000]);
      return back();
  }
    public function delete($id){
      $notification = auth()->user()->notifications()->findOrFail($id);
      $notification->delete();
      toastr()->success('Notification deleted successfully',['timeOut'=>1000]);
      return back();
    }

    public function deleteAll(){
      auth()->user()->notifications()->delete();
      toastr()->success('All notifications deleted successfully',['timeOut'=>1000]);
      return back();
    }

    public function render(DatabaseNotification $notification)
    {
       return response()->json([
        'html' => view('components.notification', compact('notification'))->render(),
    ]);
    }
}
