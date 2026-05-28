<?php

namespace App\Services;

use App\DTOs\CreatePostDTO;
use App\DTOs\PostFilterDTO;
use App\DTOs\UpdatePostDTO;
use App\Enums\PostStatus;
use App\Events\PostCreatedEvent;
use App\Helpers\DeleteFile;
use App\Models\PostModeration;
use App\Models\Post;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Interfaces\PostInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use App\Traits\ImageUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostService
{
  use ImageUploadTrait;
  public function __construct(
    protected PostHashtagsService $tagservice,
    private PostInterface $repo,
  ) {
  }
  public function handleBlogPage(Request $request)
  {
    $page = $request->get('blog_page', 1);
    $perPage = $request->get('perpage', 5);
    $sort = $request->get('sort', 'latest');

    $postList = $this->repo->getPaginatedPosts($perPage, $sort, $page);
    $tags = $this->repo->getPopularTags();
    $cats = $this->repo->getCategories();
    $whoToFollow = $this->repo->getWhoToFollow(auth()->id());

    if ($request->ajax()) {
      $html = view('blog.partials.posts', ['posts' => $postList])->render();

      return response()->json([
        'html' => $html,
        'hasMore' => $postList->hasMorePages(),
        'nextPage' => $postList->currentPage() + 1
      ]);
    }

    return view('blog.blog', [
      'tags' => $tags,
      'categories' => $cats,
      'users' => $whoToFollow,
      'posts' => $postList,
      'sorts' => $sort,
    ]);
  }
  public function handleSearch(Request $request)
  {
    $dto = PostFilterDTO::fromRequest($request);

    $page = request()->get('page', 1);
    $perPage = request('perpage', 5);
    $posts = $this->repo->getBySearch($dto, $page, $perPage);

    if (request()->ajax()) {
      $html = view('blog.partials.posts', ['posts' => $posts])->render();
      return response()->json([
        'html' => $html,
        'searchquery' => $dto->search,
        'hasMore' => $posts->hasMorePages(),
        'nextPage' => $posts->currentPage() + 1
      ]);
    }

    return view('search', [
      'posts' => $posts,
      'sorts' => $dto->sort,
      'searchquery' => $dto->search,
      'authFollowings' => auth()->user()->load('followings')->followings->pluck('id')->toArray()
    ]);
  }
  public function handleSaved()
  {
    $getposts = session('saved-to', []);
    $perpage = 5;
    $posts = $this->repo->getSavedPosts($getposts, $perpage);

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

        $newimage = $this->uploadImage($dto->image, $imageslug);
        $status = $this->statusForCreatedPost($dto->status);

        $post = Post::create(
          array_merge(
            $dto->toArray(),
            [
              'image_path' => $newimage,
              'status' => $status,
              'published_at' => $status === PostStatus::PUBLISHED ? now() : null,
            ]
          )
        );

        if ($dto->hashtags) {
          $this->tagservice->attachhashtags($post, $dto->hashtags);
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

  private function statusForCreatedPost($requestedStatus): PostStatus
  {
    return match ($requestedStatus) {
      PostStatus::DRAFT => PostStatus::DRAFT,

      PostStatus::PUBLISHED => (PostModeration::rules()->enable_auto_approve
        || Gate::allows('bypassModeration', Post::class))
      ? PostStatus::PUBLISHED
      : PostStatus::PENDING,

      default => PostStatus::PENDING,
    };
  }

  public function update(Post $post, UpdatePostDTO $dto): ?Post
  {
    $newImage = null;
    $oldImage = $post->image_path;

    try {

      DB::transaction(function () use ($post, $dto, &$newImage) {
        $status = $this->statusForCreatedPost($dto->status);
        $data = array_merge($dto->toArray(), ['status' => $status]);
        if ($dto->image) {

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

        $this->tagservice->syncHashtags(
          $post,
          $dto->hashtags
        );

        $post->categories()->sync(
          $dto->categories
        );
      });

      if ($newImage && $oldImage) {
        DeleteFile::existImage('uploads/' . $oldImage);
      }

      return $post->fresh();

    } catch (\Throwable $e) {

      if ($newImage) {
        DeleteFile::existImage('uploads/' . $newImage);
      }
      Log::error('Error updating post ID ' . $post->id .': ' . $e->getMessage()  );

      return null;
    }
  }
}
