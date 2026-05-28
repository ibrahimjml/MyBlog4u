<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Events\ReplyCommentEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Http\Middleware\CheckIfBlocked;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\PostInterface;

class CommentController extends Controller
{
  public function __construct()
  {
    $this->middleware(['auth','demo','verified',CheckIfBlocked::class]);
  }
   public function loadMore(Post $post, PostInterface $repo)
{
    $page = request()->get('comments_page', 1);
    $comments = $repo->getPaginatedComments($post, $page, 5);
    
    if (request()->ajax()) {
        $html = view('comments.comments', ['comments' => $comments])->render();
        
        return response()->json([
            'html' => $html,
            'hasMore' => $comments->hasMorePages(),
            'nextPage' => $comments->currentPage() + 1
        ]);
    }
    
    abort(404);
}
public function search_mentioned(Request $request)
{
   $q = trim($request->get('q'));

    if ($q === '') {
        return response()->json([]);
    }

    return User::where('id','!=',auth()->id())
    ->where(function(Builder $query) use($q){
      $query->whereAny(['name', 'username'],'like',"%{$q}%");
    })
        ->take(8)
        ->get()
        ->map(fn ($user) => [
            'id'       => $user->id,
            'name'     => $user->name,
            'username' => $user->username,
            'avatar'   => $user->avatar_url,
        ])
        ->values();
}

    public function createComment(Post $post,Request $request){
      $fields = $request->validate([
        'content'=>'required|string|max:255',
        'parent_id'=>'nullable|exists:comments,id'
      ]);

    
      $fields['content']=strip_tags($fields['content']);
      $fields['user_id']=auth()->id();
      $fields['post_id']=$post->id;

       if(!$post->allow_comments){
        return response()->json([
          'error'=>'comments disabled on post'
        ]);
       }
      $comment = $post->comments()->create($fields);

      return response()->json([
        'commented'=>true,
        'html' => view('comments.comments', ['comments' => [$comment]])->render()
      ]);
    }

public function reply(Comment $comment, Request $request){
    $fields = $request->validate([
        'content'=>'required|string|max:255',
        'parent_id'=>'required|exists:comments,id'
      ]);
   try{
      // cannot reply to own reply
      if ($comment->user_id == auth()->id() && $comment->parent_id !== null) {
    
        return response()->json([
          'error' =>'Cannot reply to your own reply'
        ]);
    }
    // maximum 3 replies allowed
    $replyCount = Comment::where('parent_id',$fields['parent_id'])->count();
    if($replyCount >= 3){
      return response()->json([
        'error' =>'maximum 3 replies'
      ]);
    }
    // reply once to your parent comment
    $selfReplyCount = Comment::where('user_id', auth()->id()) 
    ->where('parent_id', $comment->id) 
    ->exists();
    
    if ($selfReplyCount) {
  
    return response()->json([
      'error' =>'You can only reply once on comment'
    ]);
}
      $fields['content']=strip_tags($fields['content']);
      $fields['user_id']=auth()->id();
      $fields['post_id']=$comment->post_id;
      
     $reply = Comment::create($fields);
     //clear comments cache 
     Cache::tags(["post:{$comment->post_id}:comments"])->flush();
     
     event(new ReplyCommentEvent($comment, $reply,auth()->user()));

      return response()->json([
        'replied' => true,
        'html' => view('comments.replies',['comments'=>[$reply]])->render()
      ]);
    }catch (\Exception $e) {
        Log::error('error applying reply :'.$e->getMessage());
    }
}
    public function editComment(Request $request,Comment $comment){
      
      $this->authorize('edit',$comment);
      
      
      $fields = $request->validate([
        'content'=>'required|string|max:255',
        'parent_id'=>'nullable|exists:comments,id'
      ]);

    
      $fields['content']=strip_tags($fields['content']);

      $comment->update($fields);

      return response()->json([
        'Edited'=>true
      ]);

    }

    public function deleteComment(Comment $comment){
      $this->authorize('delete',$comment);

      $comment->delete();
      return response()->json([
        'deleted'=>true
      ]);
    }
}
