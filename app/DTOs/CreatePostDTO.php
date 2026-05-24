<?php

namespace App\DTOs;

use App\Http\Requests\App\CreatePostRequest as AppCreateRequest;
use Illuminate\Http\UploadedFile;

class CreatePostDTO
{
    public function __construct(
       public readonly string $title,
       public readonly string $description,
       public readonly UploadedFile $image,
       public readonly ?bool $allowComments,
       public readonly int $userId,
       public readonly ?string $hashtags, 
       public readonly array $categories,
       public readonly ?bool $isFeatured = false,
    ){}

    public static function fromAppRequest(AppCreateRequest $request): CreatePostDTO
    {
        $title = htmlspecialchars(strip_tags($request->validated('title')));
        $allowComments = $request->boolean('enabled');
        $featured = $request->boolean('featured');
       
        return new self(
           title: $title,
           description: $request->validated('description'),
           image: $request->file('image'),
           allowComments: $allowComments,
           userId: auth()->id(),
           hashtags: $request->filled('hashtag') ? $request->validated('hashtag') : null,
           categories: $request->validated('categories') ?? [],
           isFeatured: $featured,
       );
    }
    public function toArray()
    {
      return [
        'title'          => $this->title,
        'description'    => $this->description,
        'allow_comments' => $this->allowComments,
        'user_id'        => $this->userId,
        'is_featured'    => $this->isFeatured,
    ];
    }
}
