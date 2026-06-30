<?php

namespace App\Services;

use App\Actions\CreateCommentAction;
use App\Actions\DeleteCommentNotificationAction;
use App\Actions\DeleteMentionNotificationAction;
use App\Actions\SyncCommentMentionAction;
use App\Actions\UpdateCommentAction;
use App\DTOs\CreateCommentDTO;
use App\Events\CommentCreatedEvent;
use App\Events\MentionedUserEvent;
use App\Events\ReplyCommentEvent;
use App\Exceptions\CommentException;
use App\Exceptions\CommentReplyException;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentService
{
    private const MAX_REPLY_ALLOW = 3;
    public function __construct(
      protected CreateCommentAction $createCommentAction,
      protected UpdateCommentAction $updateCommentAction,
      protected SyncCommentMentionAction $syncMentioned,
      protected ClearCacheService $clearCache,
      protected DeleteCommentNotificationAction $deleteCommentNotificationAction,
      protected DeleteMentionNotificationAction $deleteMentionNotificationAction
      ){}
    public function create(Post $post, CreateCommentDTO $dto): ?Comment
    {
      try{
           if(!$post->allow_comments){
            throw CommentException::commentDisabled();
       }
         $comment = DB::transaction(function() use ($dto){
           $comment = $this->createCommentAction->execute($dto);
           $isMentionedSynced = $this->syncMentioned->execute($comment);
           $this->clearCache->clearPostCaches($comment->post);
            
            Comment::whereKey( $comment->parent_id)->increment('replies_count');

            
           DB::afterCommit(function() use ($comment, $isMentionedSynced){
             event(new CommentCreatedEvent($comment));
             if($isMentionedSynced){
              event(new MentionedUserEvent($comment));
             }
           });
           return $comment;
        
           });
         return $comment;
      } catch(\Throwable $e){
         Log::error('Error creating comment: ' . $e->getMessage());
         throw $e;
      }
      
    }
    public function reply(Comment $parent, CreateCommentDTO $dto): ?Comment
{
    try {
        if ( $parent->user_id === $dto->userId &&  $parent->parent_id !== null
        ) {
            throw CommentReplyException::ownSelfReply();
        }

        if ( Comment::where('parent_id', $parent->id)->count() >= self::MAX_REPLY_ALLOW
        ) {
            throw CommentReplyException::maxRepliesAllowed(self::MAX_REPLY_ALLOW);
        }

        if ( Comment::where('user_id', $dto->userId)
                ->where('parent_id', $parent->id)
                ->exists()
        ) {
             throw CommentReplyException::replyOnlyOnce();
        }

        $reply = DB::transaction(function () use ($parent, $dto) {

            $reply = $this->createCommentAction->execute($dto);

            $hasMentions = $this->syncMentioned->execute($reply);

            Comment::whereKey($parent->id)
                ->increment('replies_count');

            $this->clearCache->clearPostCaches($parent->post);

            DB::afterCommit(function () use ($parent, $reply, $hasMentions) {

                event(new ReplyCommentEvent(
                    $parent,
                    $reply,
                    $reply->user
                ));

                if ($hasMentions) {
                    event(new MentionedUserEvent($reply));
                }
            });

            return $reply;
        });

        return $reply;

    } catch (\Throwable $e) {
      Log::error('Error creating reply: '.$e->getMessage());
      throw $e;
    }
}
    public function update(Comment $comment, CreateCommentDTO $dto)
    {
      try{
       $this->updateCommentAction->update($comment,$dto);
       $this->clearCache->clearPostCaches($comment->post);
       $hasMentions = $this->syncMentioned->execute($comment);
         if ($hasMentions) {
                    event(new MentionedUserEvent($comment));
                }
      } catch(\Throwable $e){
      Log::error('Error updating comment: '.$e->getMessage());
      throw $e;

      }
    }
    public function delete(Comment $comment)
    {
       try{
       $comment->delete();
       $this->clearCache->clearPostCaches($comment->post);
       Comment::whereKey($comment->parent_id)->decrement('replies_count');
       $this->deleteCommentNotificationAction->execute($comment);
      if($comment->mentions()->exists()){
        $comment->mentions()->detach();
        $this->deleteMentionNotificationAction->execute($comment);
      }
      } catch(\Throwable $e){
      Log::error('Error deleting comment: '.$e->getMessage());
      throw $e;

      }
    }
}
