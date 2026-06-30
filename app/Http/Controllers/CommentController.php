<?php

namespace App\Http\Controllers;

use App\Exceptions\CommentException;
use App\Exceptions\CommentReplyException;
use App\Http\Requests\App\CreateCommentRequest;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckIfBlocked;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Interfaces\PostInterface;

class CommentController extends Controller
{
  public function __construct(protected CommentService $commentService)
  {
    $this->middleware(['auth','verified',CheckIfBlocked::class]);
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

    public function createComment(Post $post,CreateCommentRequest $request){
      try{
      $dto = $request->toDTO($post->id);

      $comment = $this->commentService->create($post,$dto);

      return response()->json([
        'commented' => true,
        'html'      => view('comments.comments', ['comments' => [$comment]])->render()
      ]);
      } catch (CommentException $e){
        return response()->json([
          'error' => $e->getMessage(),
        ]);
      }
      
    }

public function reply(Comment $comment, CreateCommentRequest $request){
  
      try{
        $dto = $request->toDTO($comment->post_id);
        $reply = $this->commentService->reply($comment,$dto);

      return response()->json([
        'replied' => true,
        'html' => view('comments.replies',['comments'=>[$reply]])->render()
      ]);
    }catch (CommentReplyException $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ], 422);
    }
}
    public function editComment(CreateCommentRequest $request,Comment $comment){
      
      $this->authorize('edit',$comment);
      $dto = $request->toDTO($comment->post_id);
      $this->commentService->update($comment,$dto);

      return response()->json([
        'Edited'=>true
      ]);

    }

    public function deleteComment(Comment $comment){
      $this->authorize('delete',$comment);

      $this->commentService->delete($comment);
      return response()->json([
        'deleted'=>true
      ]);
    }
}
