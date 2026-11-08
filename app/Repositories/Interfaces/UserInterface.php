<?php

namespace App\Repositories\Interfaces;

use App\DTOs\PostFilterDTO;
use App\Enums\UserActivityType;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\User;

interface UserInterface
{
   public function getBySearch(PostFilterDTO $dto, int $limit): Collection;

   public function getPaginatedArticles(User $user, int $perPage, int $page): LengthAwarePaginator;

   public function getUserActivities(User $user, ?UserActivityType $type, int $perPage, int $page): LengthAwarePaginator;
}