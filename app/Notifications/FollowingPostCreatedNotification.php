<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FollowingPostCreatedNotification extends Notification
{
    use Queueable;


    protected $PostedBy;
    protected $post;
    public function __construct(User $PostedBy,Post $post)
    {
  
        $this->PostedBy = $PostedBy;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

       if (config('broadcasting.enabled') === true) $channels[] = 'broadcast';
    
       return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line("{$this->PostedBy->name}  created a new post : **{$this->post->title}**")
                    ->action('View', url(env('APP_URL').'/post/'.$this->post->slug))
                    ->line('Thank you for using our application BlogPost!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'postedby_id' => $this->PostedBy->id,
            'postedby_username' => $this->PostedBy->username,
            'post_id' => $this->post->id,
            'post_link' => $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::POSTCREATED->value,
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'postedby_id' => $this->PostedBy->id,
            'postedby_username' => $this->PostedBy->username,
            'post_id' => $this->post->id,
            'post_link' => $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::POSTCREATED->value,
        ];
    }
    private function buildMessage(object $notifiable): string
    {
        return $notifiable->is_admin
        ? "{$this->PostedBy->name} created a new post '{$this->post->title}'"
        : "{$this->PostedBy->name} (whom you followed) created a new post '{$this->post->title}'";
    }
}
