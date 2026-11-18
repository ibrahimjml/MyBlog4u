<?php

namespace App\Services;

use App\Actions\AttachPostTagsAction;
use App\Actions\CreatePostAction;
use App\Actions\ResolvePostStatusAction;
use App\Actions\SyncPostTagsAction;
use App\DTOs\CreatePostDTO;
use App\DTOs\UpdatePostDTO;
use App\Enums\PostStatus;
use App\Events\PostCreatedEvent;
use App\Helpers\DeleteFile;
use App\Http\Resources\UserCardResource;
use App\Jobs\SendPostModerationWhatsappJob;
use App\Models\AdPlacement;
use App\Models\Post;
use App\Repositories\Interfaces\NewsInterface;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Support\Str;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{
  use ImageUploadTrait;
  public function __construct(
    private PostInterface $repo,
    private NewsInterface $news,
    private AttachPostTagsAction $attachPostTagsAction,
    private SyncPostTagsAction $syncPostTagsAction,
    private CreatePostAction $createPostAction,
    private ResolvePostStatusAction $resolveStatusAction,
  ) {
  }
  public function handleBlogPage(Request $request)
  {
    $page = $request->get('blog_page', 1);
    $perPage = $request->get('perpage', 5);
    $sort = $request->get('sort', 'latest');

    guest_not_allowed_filter_following($sort);

    $postList = $this->repo->getPaginatedPosts($perPage, $sort, $page);
    attach_author_cards($postList);
    
    $tags = $this->repo->getPopularTags();
    $cats = $this->repo->getCategories();
    $whoToFollow = auth()->check() ? $this->repo->getWhoToFollow(auth()->id()) : collect();
    $userCards = UserCardResource::collection($whoToFollow->keyBy('username'))->resolve();
    $latestNews = $this->news->getLatestSources();
    $inner_ads = AdPlacement::active()->where('ad_position', \App\Enums\Adplacements\AdPosition::INNER_FEED)->get();
    

    if ($request->ajax()) {
      $html = view('blog.partials.posts-ajax', [
        'posts' => $postList,
        'searchquery' => $request->get('search', null),
        'ads' => $inner_ads,
        'showAppliedFilter' => false
      ])->render();

      return infinite_scroll_response($html,$postList);
    }

    return view('blog.blog', [
      'tags' => $tags,
      'categories' => $cats,
      'users' => $whoToFollow,
      'userCards' => $userCards,
      'posts' => $postList,
      'news' => $latestNews,
      'sorts' => $sort,
      'ads' => $inner_ads,
      'searchquery' => null,
    ]);
  }
  
  public function handleSaved()
  {
    $getposts = session('saved-to', []);
    $perpage = 5;
    $posts = $this->repo->getSavedPosts($getposts, $perpage);
    attach_author_cards($posts);

    return view('getsavedposts', [
      'posts' => $posts,
    ]);
  }
  public function create(CreatePostDTO $dto): ?Post
  {
    $imageslug = Str::slug($dto->title);
    $newimage = null;
    $post = null;

    try {

      $post = DB::transaction(function () use ($dto, $imageslug, &$newimage) {
        if ($dto->image) {
          $newimage = $this->uploadImage($dto->image, $imageslug);
        }
        $status = $this->resolveStatusAction->handle($dto->status);

        $post = $this->createPostAction->execute($dto, $newimage, $status);

        if(n8n_webhook_enabled() && $status === PostStatus::PENDING){
          dispatch(new SendPostModerationWhatsappJob($post));
        } 

        if ($dto->hashtags) {
          $this->attachPostTagsAction->attachTag($post, $dto->hashtags);
        }
        if ($dto->categories) {
          $post->categories()->sync($dto->categories);
        }

        return $post;
      });

      DB::afterCommit(function () use ($post) {
        try {
          event(new PostCreatedEvent($post));
        } catch (\Throwable $e) {
          Log::error('PostCreatedEvent failed for post ID ' . $post->id . ': ' . $e->getMessage());
        }
      });

      return $post;

    } catch (\Throwable $e) {
      DeleteFile::existImage('uploads/' . $newimage);
      Log::error('Error creating post: ' . $e->getMessage() . '. Deleted image: ' . ($newimage ?? 'none'));
      return null;
    }
  }


  public function update(Post $post, UpdatePostDTO $dto): ?Post
  {
    $newImage = null;
    $oldImage = $post->getOriginal('image_path');

    try {

      DB::transaction(function () use ($post, $dto, &$newImage, &$oldImage) {
        $status = $this->resolveStatusAction->handle($dto->status);
        $data = array_merge($dto->toArray(), ['status' => $status]);
        if ($dto->removeImage) {
          DeleteFile::existImage('uploads/' . $oldImage);
          $data['image_path'] = $newImage;
        } elseif ($dto->image) {

          $imageSlug = Str::slug($dto->title);

          $newImage = $this->uploadImage(
            $dto->image,
            $imageSlug
          );

          $data['image_path'] = $newImage;
        }

        // handle published_at
        if (
          $dto->status === PostStatus::PUBLISHED &&
          !$post->published_at
        ) {
          $data['published_at'] = now();
        }

        if ($dto->status !== PostStatus::PUBLISHED) {
          $data['published_at'] = null;
        }

        $post->update($data);

        $this->syncPostTagsAction->syncTag($post,$dto->hashtags);

        $post->categories()->sync($dto->categories);
      });

      if ($newImage && $oldImage) {
        DeleteFile::existImage('uploads/' . $oldImage);
      }

      return $post->fresh();

    } catch (\Throwable $e) {

      if ($newImage) {
        DeleteFile::existImage('uploads/' . $newImage);
      }
      Log::error('Error updating post ID ' . $post->id . ': ' . $e->getMessage());

      return null;
    }
  }
}
