<?php

namespace App\DTOs;

use App\Enums\PostStatus;
use App\Http\Requests\App\UpdatePostRequest as AppUpdateRequest;
use App\Models\Post;
use Illuminate\Http\UploadedFile;

class UpdatePostDTO
{
  public function __construct(
    public readonly string $title,
    public readonly string $shortExcerpt,
    public readonly string $description,
    public readonly ?UploadedFile $image,
    public readonly ?bool $allowComments,
    public readonly ?string $hashtags,
    public readonly ?array $categories,
    public readonly ?bool $isFeatured = false,
    public readonly PostStatus $status,
  ) {}

  public static function fromAppRequest(AppUpdateRequest $request, Post $post): self
  {
    $title = htmlspecialchars(strip_tags($request->validated('title')));
    $allowComments =  $request->boolean('enabled');
    $featured = $post->is_featured;
    $status = PostStatus::from($request->validated('status'));
    if (
      $request->exists('featured') &&
      $request->user()->can('feature', $post)
    ) {
      $featured = $request->boolean('featured');
    }

    return new self(
      title: $title,
      shortExcerpt: $request->validated('short_excerpt'),
      status: $status,
      image: $request->file('image'),
      description: $request->validated('description'),
      allowComments: $allowComments,
      hashtags: $request->filled('hashtag') ? $request->validated('hashtag') : null,
      categories: $request->validated('categories') ?? [],
      isFeatured: $featured
    );
  }
  public function toArray()
  {
    return [
        'title'          => $this->title,
        'short_excerpt'  => $this->shortExcerpt,
        'description'    => $this->description,
        'allow_comments' => $this->allowComments,
        'is_featured'    => $this->isFeatured,
        'status'          => $this->status,
    ];
  }
}
