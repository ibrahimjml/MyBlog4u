<?php

namespace App\Listeners;

use App\Enums\NotificationType;
use App\Events\PostReportEvent;
use App\Models\User;
use App\Notifications\PostReportNotification;
use App\Notifications\ReportStatusNotification;
use App\Traits\AdminNotificationGate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendPostReportNotification implements ShouldQueue
{
    use AdminNotificationGate;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostReportEvent $event): void
    {
        $post = $event->post;
        $reporter = $event->user;
        $report = $event->report;
        // Notify all admins
         User::where('is_admin', true)->get()->each(function ($admin) use ( $post,$reporter) {
         if($admin && $this->allow($admin,NotificationType::REPORT)){
         $admin->notify(new PostReportNotification(  $post,$reporter));
         }
       });
      // Notify  reporter that report is pending
      $reporter->notify(new ReportStatusNotification($report));
    }
}
