<?php

namespace App\Notifications;

use App\Enums\NotificationType;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $commenter;
    protected $post;
    public function __construct(Comment $comment,User $commenter,Post $post)
    {
        $this->comment = $comment;
        $this->commenter = $commenter;
        $this->post = $post;
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
            'comment_id' => $this->comment->id,
            'commenter_username' => $this->commenter->username,
            'post_link'=> $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::COMMENTS->value
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'commenter_username' => $this->commenter->username,
            'post_link'=> $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::COMMENTS->value
        ];
    }
    private function buildMessage(object $notifiable): string
    {
        return $notifiable->is_admin
               ? "{$this->commenter->name} commented on {$this->post->user->name}'s post "
               : "{$this->commenter->name} commented on your post {$this->post->title}";
    }
}
