<?php
namespace App\Repositories\Interfaces;

use App\DTOs\PostFilterDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Models\Post;

interface PostInterface
{
    public function getPaginatedPosts(int $perPage, string $sort, int $page): LengthAwarePaginator;
    
    public function getBySearch(PostFilterDTO $dto, int $limit): Collection;
    public function getPopularTags() : Collection;
    public function getCategories() :Collection;
    public function getWhoToFollow(int $userId) : Collection;
    
    public function getSavedPosts(array $ids, int $perPage): LengthAwarePaginator;
    
    public function getRelatedArticles(Post $post);

    public function getLatestBlogs(Post $post);
    public function getLatestForHomeFeed();
    public function getPostLikes(Post $post);
    public function getFeaturedPosts();
    
    public function getTrendingTagPosts();
    public function getPaginatedComments(Post $post, int $page , int $perPage ): LengthAwarePaginator;
    

}