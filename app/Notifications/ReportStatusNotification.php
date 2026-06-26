<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Enums\ReportStatus;
use App\Models\PostReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification
{
    use Queueable;

    protected PostReport $report;
    public function __construct(PostReport $report)
    {
        $this->report = $report;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'post_id' => $this->report->post->id,
            'post_link' => $this->report->post->slug,
            'status' => $this->report->status->value,
            'type'=> NotificationType::REPORT->value,
            'message' => match($this->report->status) {
                ReportStatus::Pending => "Your report on {$this->report->post->title} will be reviewed shortly status ".ReportStatus::Pending->value .".",
                ReportStatus::Reviewed => "Action has been taken on your report for '{$this->report->post->title}'.",
                ReportStatus::Rejected => "Your report didn't meet the criteria for '{$this->report->post->title}'.",
            },
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'post_id' => $this->report->post->id,
            'post_link' => $this->report->post->slug,
            'status' => $this->report->status->value,
            'type'=> NotificationType::REPORT->value,
            'message' => match($this->report->status) {
                ReportStatus::Pending => "Your report on {$this->report->post->title} will be reviewed shortly status ".ReportStatus::Pending->value .".",
                ReportStatus::Reviewed => "Action has been taken on your report for '{$this->report->post->title}'.",
                ReportStatus::Rejected => "Your report didn't meet the criteria for '{$this->report->post->title}'.",
            },
        ];
    }
}
