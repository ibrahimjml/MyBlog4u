<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryRepository implements CategoryInterface
{
     public function getBySearch($dto, int $limit): Collection
    {
     return Category::query()
               ->search($dto->search)
               ->limit($limit)
               ->get();
    }

    public function getPostsByCategory(Category $category, $sorts, $perpage, $page): LengthAwarePaginator
    {
      return $category->posts()
                      ->with([
                          'user' => fn ($query) => $query
                              ->select('id', 'name', 'username', 'avatar', 'bio')
                              ->withCardCounts(),
                          'categories:id,name',
                      ])
                      ->withCount(['totalcomments','likes'])
                      ->blogSort($sorts)
                      ->orderBy('created_at','desc')
                      ->paginate($perpage,['*'],'categories_page',$page)
                      ->withQueryString();
    }
    public function getSliderImages(Category $category)
    {
        return $category->posts()
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
