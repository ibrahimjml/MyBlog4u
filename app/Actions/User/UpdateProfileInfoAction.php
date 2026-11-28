<?php

namespace App\Actions\User;

use App\Builders\UserBuilder;
use App\DTOs\User\UpdateUserProfileDTO;
use App\Helpers\DeleteFile;
use App\Models\User;
use App\Traits\ImageUploadTrait;

class UpdateProfileInfoAction
{
    use ImageUploadTrait;

    public function execute(User $user, UpdateUserProfileDTO $dto): ?User
    {
        $changed = false;

        $user->fill([
            'name'     => $dto->name,
            'phone'    => $dto->phone,
            'bio'      => $dto->bio,
            'aboutme'  => $dto->about,
            'github'   => $dto->github,
            'linkedin' => $dto->linkedin,
            'twitter'  => $dto->twitter,
        ]);

        if ($user->isDirty()) {
            $changed = true;
        }

        if ($dto->avatar) {
            DeleteFile::cleanImage("avatars/{$user->avatar}", 'default.jpg');
            $user->avatar = $this->uploadAvatarImage($dto->avatar, $user->name);
            $changed = true;
        }

        if ($dto->cover) {
            DeleteFile::cleanImage("covers/{$user->cover_photo}", 'sunset.jpg');
            $user->cover_photo = $this->uploadCoverImage($dto->cover, $user->name);
            $changed = true;
        }

        if ($this->syncSocialLinks($user, $dto->social_links ?? [])) {
            $changed = true;
        }

        if ($changed) {
            $user->save();

            return $user;
        }

        return null;
    }

    private function syncSocialLinks(User $user, array $links): bool
    {
        $builder = $user->newQuery();
        $builtinPlatforms = $builder->filledBuiltinSocialPlatforms();
        $builtinUrls = $builder->filledBuiltinSocialUrls();

        $changed = false;
        $seen    = [];

        foreach (array_slice($links, 0, UserBuilder::MAX_CUSTOM_SOCIAL_LINKS) as $link) {
            $platform = trim(strip_tags($link['platform'] ?? ''));
            $url      = trim(strip_tags($link['url'] ?? ''));

            if ($platform === '' || $url === '') {
                continue;
            }

            $normalizedPlatform = strtolower($platform);
            $normalizedUrl      = $builder->normalizeSocialUrl($url);

            if (in_array($normalizedPlatform, $builtinPlatforms, true)
                || in_array($normalizedUrl, $builtinUrls, true)
                || in_array($normalizedPlatform, $seen, true)) {
                continue;
            }

            $seen[] = $normalizedPlatform;

            $existing = $user->socialLinks()
                ->whereRaw('LOWER(platform) = ?', [$normalizedPlatform])
                ->first();

            if ($existing) {
                if ($existing->url !== $url) {
                    $existing->update(['url' => $url]);
                    $changed = true;
                }

                continue;
            }

            $user->socialLinks()->create([
                'platform' => $platform,
                'url'      => $url,
            ]);
            $changed = true;
        }

        return $changed;
    }
}
