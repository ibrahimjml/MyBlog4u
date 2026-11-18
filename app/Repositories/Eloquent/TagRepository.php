<?php

namespace App\Repositories\Eloquent;

use App\Models\Hashtag;
use App\Repositories\Interfaces\TagInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TagRepository implements TagInterface
{
    public function getBySearch($dto, int $limit): Collection
    {
        return Hashtag::query()
            ->active()
            ->search($dto->search)
            ->limit($limit)
            ->get();
    }
    public function getPostsByTag(Hashtag $hashtag, $sorts, $perpage, $page): LengthAwarePaginator
    {
      return $hashtag->posts()
                     ->with([
                         'user' => fn ($query) => $query
                             ->select('id', 'name', 'username', 'avatar', 'bio')
                             ->withCardCounts(),
                         'hashtags:id,name,is_featured',
                         'categories:id,name,is_featured',
                     ])
                     ->withCount(['totalcomments','likes'])
                     ->blogSort($sorts)
                     ->orderBy('created_at','desc')
                     ->paginate($perpage,['*'],'hashtags_page',$page)
                     ->withQueryString();
    }

    public function getSliderImages(Hashtag $hashtag)
    {
        return $hashtag->posts()
                        ->select([
                            'posts.id',
                            'posts.title',
                            'posts.slug',
                            'posts.image_path',
                            'posts.updated_at',
                        ])
                        ->latest('posts.created_at')
                        ->take(5)
                        ->get();
    }
}
