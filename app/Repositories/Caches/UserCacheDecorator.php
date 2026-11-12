<?php

namespace App\Repositories\Caches;

use App\Enums\UserActivityType;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;
use Illuminate\Support\Fluent;

class UserCacheDecorator implements UserInterface
{
    public function __construct(private UserInterface $repo){}

    public function getBySearch($dto, int $limit): Collection
    {
        $key = sprintf('users:search:%s:%d', md5(strtolower(trim($dto->search))), $limit);
        return Cache::tags(['users_results'])->remember($key, now()->addMinutes(10),fn () => $this->repo->getBySearch($dto, $limit));
    }

    public function getPaginatedArticles(User $user, int $perPage, int $page): LengthAwarePaginator
    {
        $key = sprintf('profile_home_posts:user:%d:page:%d:perpage:%d', $user->id, $page, $perPage);
        return Cache::tags(['users_profile_posts', "profile_home:{$user->id}"])->remember($key, now()->addDay(),fn () => $this->repo->getPaginatedArticles($user,$perPage,$page));
    }

      public function getUserActivities(
        User $user,
        ?UserActivityType $type,
        int $perPage,
        int $page
    ): LengthAwarePaginator {
        $key = sprintf(
            'user_activities:user:%d:type:%s:page:%d:perpage:%d',
            $user->id,
            $type?->value ?? 'all',
            $page,
            $perPage
        );

        return Cache::tags(['user_activities', "user_activities:{$user->id}"])
            ->remember($key, now()->endOfDay(), fn () =>
                $this->repo->getUserActivities($user, $type, $perPage, $page)
            );
    }
    public function getAllPosts(User $user, Fluent $sort): LengthAwarePaginator
    {
         $key = sprintf('user_dashboard_posts:user:%d:sort:%s', $user->id, md5(json_encode($sort)));

         return Cache::tags(['user_dashboard_posts', "user_posts:{$user->id}"])
            ->remember($key, now()->endOfDay(), fn () => $this->repo->getAllPosts($user, $sort));
    }
}