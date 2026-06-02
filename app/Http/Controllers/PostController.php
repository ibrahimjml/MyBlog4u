<?php

namespace App\Http\Controllers;


use App\DTOs\{CreatePostDTO, UpdatePostDTO};
use App\Enums\Adplacements\AdPosition;
use App\Http\Middleware\CheckIfBlocked;
use App\Http\Requests\App\{CreatePostRequest, UpdatePostRequest};
use App\Models\{AdPlacement, Category, Hashtag, Post};
use App\Services\{PostService, PostViewsService, ViewPostService};
use Illuminate\Http\Request;


class PostController extends Controller
{
  public function __construct(
    private PostService $service,
    private ViewPostService $postservice,
    )
  {
    $this->postservice = $postservice;
    $this->service = $service;
    $this->middleware(CheckIfBlocked::class);
    $this->middleware(['auth', 'verified'])->except(['blogpost', 'viewPost','search']);
    $this->middleware('password.confirm')->only(['editpost', 'delete','update']);
  }

  public function blogpost(Request $request)
  {
     return $this->service->handleBlogPage($request);
  }
  
  public function viewPost(Post $post,PostViewsService $views)
  {
    $post = $this->postservice->getPost($post);
    // generate post view
    $views->getViews($post);
    // ads
    $ads = AdPlacement::active()->get();

    return view('post', [
       'post' => $post,
       'comments' => $post->comments,
       'latestblogs' => $post->latestblogs,
       'morearticles' => $post->morearticles,
       'viewwholiked' => $post->viewwholiked,
       'reasons' => $post->reasons,
       'ads' => $ads,
    ]);
  }
  public function createpage()
  {
    $this->authorize('create', Post::class);

    $allhashtags = Hashtag::active()->pluck('name');
    $categories = Category::select('id','name')->get();

    return view('create', [
      'allhashtags' => $allhashtags,
      'categories' => $categories
    ]);
  }

  public function search(Request $request)
  {
     return $this->service->handleSearch($request);
  }
  public function create(CreatePostRequest $request)
  {
    $this->authorize('create', Post::class);

    $dto = CreatePostDTO::fromAppRequest($request);
     $this->service->create($dto);
    toastr()->success('posted successfuly',['timeOut'=>1000]);

    return redirect()->route('dashboard.posts');
  }

  public function delete($slug)
  {
    
    $post = Post::whereSlug( $slug)->firstOrFail();
    $this->authorize('delete', $post);
    $post->delete();

      toastr()->success('Post deleted successfully',['timeOut'=>1000]);
      return redirect()->route('dashboard.posts');
    
  }
  public function editpost($slug)
  {
    $post = Post::whereSlug( $slug)->firstOrFail();
    $this->authorize('view', $post);

    $hashtags = $post->hashtags()->pluck('name')->implode(', ');
    $allhashtags = Hashtag::active()->pluck('name');
    $categories = Category::select('id','name')->get();
    
    return view('updatepost',[
      'post' => $post,
      'hashtags' => $hashtags,
      'allhashtags' => $allhashtags,
      'categories' => $categories
    ]);
  }

  public function update($slug, UpdatePostRequest $request)
  {
    $post = Post::whereSlug( $slug)->firstOrFail();
    $this->authorize('update', $post);

    $dto = UpdatePostDTO::fromAppRequest($request, $post);
    $this->service->update($post,$dto);

    toastr()->success('Post updated successfully',['timeOut'=>1000]);
    return redirect()->route('dashboard.posts');
  }
  
}
