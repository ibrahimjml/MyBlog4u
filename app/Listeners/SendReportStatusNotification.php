<?php

namespace App\Listeners;

use App\Events\ReportStatusUpdateEvent;
use App\Notifications\ReportStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendReportStatusNotification implements ShouldQueue
{
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
    public function handle(ReportStatusUpdateEvent $event): void
    {
       $reporter = $event->report->user;
          if($reporter){
            $reporter->notify(new ReportStatusNotification($event->report));
          }
        
    }
}
