<?php

namespace App\Services;

use App\DTOs\ReportsDTO;
use App\Enums\ReportStatus;
use App\Models\Comment;
use App\Models\CommentReport;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\ProfileReport;
use App\Models\User;

class ReportsService
{
    public function reportPost(Post $post,ReportsDTO $dto)
    {
        $exists = PostReport::where('user_id',$dto->userId)
                 ->where('post_id',$post->id)
                 ->exists();
    if ($exists) return false;
    
     PostReport::create(array_merge($dto->toArray(),[
            'post_id' => $post->id,
            'status'  => ReportStatus::Pending,
        ]));
        
        return true;
    }

    public function reportProfile(User $user,ReportsDTO $dto)
    {
        $exists = ProfileReport::where('reporter_id',$dto->userId)
                 ->where('profile_id',$user->id)
                 ->exists();
    if ($exists) return false;
    
     ProfileReport::create([
            'reporter_id' => $dto->userId,
            'profile_id' => $user->id,
            'reason' => $dto->reason,
            'status'  => ReportStatus::Pending,
            'other' => $dto->other
        ]);
        
        return true;
    }
    public function reportComment(Comment $comment,ReportsDTO $dto)
    {
        $exists = CommentReport::where('reporter_id',$dto->userId)
                 ->where('comment_id',$comment->id)
                 ->exists();
    if ($exists) return false;
    
     CommentReport::create([
            'reporter_id' => $dto->userId,
            'comment_id' => $comment->id,
            'reason' => $dto->reason,
            'status'  => ReportStatus::Pending,
            'other' => $dto->other
        ]);
        
        return true;
    }
}
