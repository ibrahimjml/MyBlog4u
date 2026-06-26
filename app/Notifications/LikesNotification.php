<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class LikesNotification extends Notification 
{
    use Queueable;
     

    protected $user;
    protected $post;
    public function __construct(User $user,Post $post)
    {
  
      $this->user = $user;
      $this->post = $post;

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
    public function toDatabase(object $notifiable): array
    {

        return [
          'user_id' => $this->user->id,
          'user_username'=>$this->user->username,
          'post_id' => $this->post->id,
          'post_link'=>$this->post->slug,
          'type'=> NotificationType::LIKE->value,
          'message'=>$this->buildMessage($notifiable),
        ];
    }
    public function toBroadcast(object $notifiable): array
    {

        return [
          'user_id' => $this->user->id,
          'user_username'=>$this->user->username,
          'post_id' => $this->post->id,
          'post_link'=>$this->post->slug,
          'type'=> NotificationType::LIKE->value,
          'message'=>$this->buildMessage($notifiable),
        ];
    }
     private function buildMessage(object $notifiable): string
    {
        return $notifiable->is_admin
            ? ($this->user->id === $this->post->user->id
                ? "{$this->user->name} liked his post"
                : "{$this->user->name} liked {$this->post->user->name}'s post")
            : "{$this->user->name} liked your post {$this->post->title}";
    }
}
