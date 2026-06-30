<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowAcceptNotification extends Notification
{
    use Queueable;

    protected $auth;
    protected $follower;
    protected $status;

    public function __construct(User $auth,User $follower,string $status)
    {
        $this->auth = $auth;
        $this->follower = $follower;
        $this->status = $status;
    
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (config('broadcasting.enabled') === true) $channels[] = 'broadcast';
    
        return $channels;
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
    public function toDatabase(object $notifiable): array
    {
      return [
        'user_name' => $this->follower->name,
        'follower_id' => $this->auth->id,
        'follower_name' => $this->auth->name,
        'follower_username' => $this->auth->username,
        'type'=> NotificationType::FOLLOWACCEPT->value,
        'status' => $this->status,
        'message' => "{$this->auth->name} has accepted your follow request.",
        
    ];
    }
    public function toBroadcast(object $notifiable): array
    {
      return [
        'user_name' => $this->follower->name,
        'follower_id' => $this->auth->id,
        'follower_name' => $this->auth->name,
        'follower_username' => $this->auth->username,
        'type'=> NotificationType::FOLLOWACCEPT->value,
        'status' => $this->status,
        'message' => "{$this->auth->name} has accepted your follow request.",
        
    ];
    }
}
