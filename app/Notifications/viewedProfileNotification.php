<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class viewedProfileNotification extends Notification
{
    use Queueable;
    
    protected $user;
    protected $viewer;
    public function __construct(User $user,User $viewer)
    {
        $this->user = $user;
        $this->viewer = $viewer;
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
            'viewer_id' => $this->viewer->id,
            'profile_id' => $this->user->id,
            'user_name' => $this->user->name,
            'viewer_name' =>$this->viewer->name,
            'viewer_username' => $this->viewer->username,
            'message' => $this->buildMessage($notifiable),
            'type'=> NotificationType::VIEWEDPROFILE->value
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'viewer_id' => $this->viewer->id,
            'profile_id' => $this->user->id,
            'user_name' => $this->user->name,
            'viewer_name' =>$this->viewer->name,
            'viewer_username' => $this->viewer->username,
            'message' => $this->buildMessage($notifiable),
            'type'=> NotificationType::VIEWEDPROFILE->value
        ];
    }
    private function buildMessage(object $notifiable): string
    {
        return $notifiable->is_admin
               ? "{$this->viewer->name} viewed profile {$this->user->name}"
               : "{$this->viewer->name} viewed your profile";
    }
}
