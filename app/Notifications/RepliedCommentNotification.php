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

class RepliedCommentNotification extends Notification
{
    use Queueable;

    protected $ParentComment;
    protected $reply;
    protected $replier;
    protected $post;
    public function __construct(Comment $ParentComment,Comment $reply,User $replier,Post $post)
    {
        $this->ParentComment = $ParentComment;
        $this->reply = $reply;
        $this->replier = $replier;
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
            'reply_id' => $this->reply->id,
            'comment_id' => $this->ParentComment->id,
            'replier_username' => $this->replier->username,
            'post_link' => $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::COMMENTS->value
        ];
    }
    public function toBroadcast(object $notifiable): array
    {
        return [
            'reply_id' => $this->reply->id,
            'comment_id' => $this->ParentComment->id,
            'replier_username' => $this->replier->username,
            'post_link' => $this->post->slug,
            'message' => $this->buildMessage($notifiable),
            'type' => NotificationType::COMMENTS->value
        ];
    }
    private function buildMessage(object $notifiable): string
    {
        return $notifiable->is_admin
               ? ($this->replier->id === $this->ParentComment->user->id
               ? "{$this->replier->name} replied to their own comment '{$this->ParentComment->content}' on {$this->post->user->name}'s post"
               : "{$this->replier->name} replied '{$this->reply->content}' to {$this->ParentComment->user->name} on {$this->post->user->name}'s post")
               : "{$this->replier->name} replied to your comment '{$this->ParentComment->content}'";
      
    }
}
