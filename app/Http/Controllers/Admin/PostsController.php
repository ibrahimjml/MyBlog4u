<?php

namespace App\Http\Controllers\Admin;

use App\DTOs\CreatePostDTO;
use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\App\CreatePostRequest;
use App\Models\{Category, Hashtag, Post};
use App\Services\PostService;
use App\Services\Admin\PostsService;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Illuminate\Validation\Rules\Enum;

class PostsController extends Controller
{
      public function __construct(private PostsService $getService)
       {
          $this->middleware('permission:post.view')->only(['posts','featuredPage']);
       }
    public function posts(Request $request)
  {
     $filter = new Fluent(request()->only('search','sort','featured','reported'));
     $posts = $this->getService->getPosts($filter);

    return view('admin.posts.posts',[
      'posts'=>$posts,
      'filter'=>$request->only('search','sort','featured')
    ]);
  }
public function featuredPage(){
  return view('admin.featuredposts',[
    'allhashtags' => Hashtag::query()->active()->pluck('name'),
    'categories' => Category::query()->select('id','name')->get(),
  ]);
}

  public function createFeature(CreatePostRequest $request,PostService $service){
  
 $dto = CreatePostDTO::fromAppRequest($request);
 $service->create($dto);  
toastr()->success('post feature created',['timeOut'=>1000]);
return to_route('admin.posts.page');
  }
  public function editStatus(Post $post,Request $request)
  {
    $this->authorize('updateAny',$post);
    $fields = $request->validate([
      'status' => ['required',new Enum(PostStatus::class)]
    ]);
    $status = PostStatus::from($fields['status']);
    $this->getService->updateStatus($post,$status);
     
    return response()->json([
      'updated' => true,
      'message' => "status updated {$post->status->value}"
     ],200);
  }

   public function toggleFeature(Post $post)
 {
  $this->authorize('feature',$post);
  $post->update(['is_featured'=>!$post->is_featured]);
  if($post->is_featured){
    toastr()->success('post featured success',['timeOut'=>1000]);
  }else{
      toastr()->success('post unfeatured success',['timeOut'=>1000]);
  }
  return redirect()->back();
 }
}
