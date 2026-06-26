<?php

namespace App\Events;

use App\Enums\ReportStatus;
use App\Models\PostReport;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReportStatusUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $report;
    public function __construct(PostReport $report)
    {
        $this->report = $report;
    }


}
