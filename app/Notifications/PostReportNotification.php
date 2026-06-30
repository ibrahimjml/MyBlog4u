<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostReportNotification extends Notification
{
    use Queueable;

    protected $post;
    protected $user;
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
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
          'user_id' => $this->user->id,
          'user_username'=>$this->user->username,
          'post_id' => $this->post->id,
          'post_link'=>$this->post->slug,
          'type'=> NotificationType::REPORT->value,
          'message'=>"{$this->user->username} reported this post <b>{$this->post->title}</b>"
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
          'user_id' => $this->user->id,
          'user_username'=>$this->user->username,
          'post_id' => $this->post->id,
          'post_link'=>$this->post->slug,
          'type'=> NotificationType::REPORT->value,
          'message'=>"{$this->user->username} reported this post <b>{$this->post->title}</b>"
        ];
    }
}
