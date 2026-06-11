<?php

namespace App\DTOs\User;

use App\Http\Requests\App\UpdateUserProfileRequest as UserInfoRequest;
use Illuminate\Http\UploadedFile;

class UpdateUserProfileDTO
{
     public function __construct(
        public readonly ?string $name,
        public readonly ?string $phone,
        public readonly ?string $bio,
        public readonly ?string $about,
        public readonly ?string $github,
        public readonly ?string $linkedin,
        public readonly ?string $twitter,
        public readonly ?array $social_links = [],
        public readonly ?UploadedFile $avatar = null,
        public readonly ?UploadedFile $cover = null,
    ) {}

    public static function fromRequest(UserInfoRequest $request): self
    {
       $fields = $request->validated();

        return new self(
             name: $fields['name'],
             phone: $fields['phone'],
             bio: $fields['bio'] ?? null,
             about: $fields['aboutme'] ?? null,
             github: $fields['github'] ?? null,
             linkedin: $fields['linkedin'] ?? null,
             twitter: $fields['twitter'] ?? null,
             social_links: $fields['social_links'] ?? [],
             avatar: $request->file('avatar'),
             cover: $request->file('cover'),
        );
    }
}
