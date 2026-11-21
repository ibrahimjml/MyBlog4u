<?php
namespace App\Repositories\Interfaces;

use App\DTOs\BlogFilterDTO;
use App\DTOs\PostFilterDTO;
use App\Models\Hashtag;
use App\Models\Post;
use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PostInterface
{
    public function getPaginatedPosts(int $perPage, BlogFilterDTO $filter, int $page):  LengthAwarePaginator;
    
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
    
    public function getMostTrendingTag(?int $postsLimit = 3): array;
    public function getMostTrendingTagFor(?DateTimeInterface $from = null): ?Hashtag;
    public function getTrendingTagsAllTime(?array $excludeTagIds, int $limit): Collection;

    public function getPaginatedComments(Post $post, int $page , int $perPage ): LengthAwarePaginator;
    

}