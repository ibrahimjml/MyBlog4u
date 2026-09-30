<?php

namespace App\Listeners;

use App\Events\AuditLogCreatedEvent;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogAuditActivity
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
    public function handle(AuditLogCreatedEvent $event): void
    {
         AuditLog::create([
                 'user_agent' => $event->userAgent ?? request()->userAgent(),
                 'ip_address' => $event->ipAddress ?? request()->ip(),
                 'module' => $event->module,
                 'action' => $event->action,
                 'user_id' => $event->userId ?? auth()->id(),
                 'reference_user' => $event->referenceUser,
                 'reference_id' => $event->referenceId,
                 'reference_name' => $event->referenceName,
                 'type' => $event->type,
                 'request' => $event->request,
             ]);
    }
}
