<?php

namespace App\DTOs;

use App\Http\Requests\App\UpdatePostRequest as AppUpdateRequest;
use App\Models\Post;

class UpdatePostDTO
{
  public function __construct(
    public readonly string $title,
    public readonly string $description,
    public readonly ?bool $allowComments,
    public readonly ?string $hashtags,
    public readonly ?array $categories,
    public readonly ?bool $isFeatured = false,
  ) {}

  public static function fromAppRequest(AppUpdateRequest $request, Post $post): self
  {
    $title = htmlspecialchars(strip_tags($request->validated('title')));
    $allowComments =  $request->boolean('enabled');
    $featured = $post->is_featured;
    if (
      $request->exists('featured') &&
      $request->user()->can('feature', $post)
    ) {
      $featured = $request->boolean('featured');
    }

    return new self(
      title: $title,
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
      'description'    => $this->description,
      'allow_comments' => $this->allowComments,
      'is_featured'    => $this->isFeatured,
    ];
  }
}
