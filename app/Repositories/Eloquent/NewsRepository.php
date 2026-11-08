<?php

namespace App\Repositories\Eloquent;

use App\DTOs\PostFilterDTO;
use App\Models\Scraping\ScrapedPost;
use App\Models\Scraping\ScrapingSource;
use App\Repositories\Interfaces\NewsInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class NewsRepository implements NewsInterface
{
  public function getBySearch( $dto, int $limit): Collection
  {
    return ScrapedPost::query()
      ->with('source')
      ->search($dto->search)
      ->latest()
      ->limit($limit)
      ->get();
  }
  public function getPaginatedNews(string|null $sourceName, int $perpage, int $page): LengthAwarePaginator
  {
    return ScrapedPost::query()
      ->with('source')
      ->latest()
      ->when($sourceName, function ($query, $sourceName) {
        return $query->whereHas('source', function ($sub) use ($sourceName) {
          $sub->where('name', $sourceName);
        });
      })
      ->paginate($perpage, ['*'], 'news_page', $page)
      ->withQueryString();
  }
  public function getAllNewsWithSources()
{
    $sources = ScrapingSource::query()
        ->active()
        ->take(4)
        ->get();

    $sourceIds = $sources->pluck('id');

    $dataBySource = ScrapedPost::query()
        ->whereIn('scraping_source_id', $sourceIds)
        ->with('source')
        ->latest()
        ->get()
        ->groupBy('scraping_source_id')
        ->map(fn ($posts) => $posts->take(2));

    $moreNews = $sources
        ->flatMap(fn (ScrapingSource $source) => $dataBySource[$source->id] ?? collect())
        ->sortByDesc('created_at')
        ->values();

    return ['moreNews' => $moreNews];
}

  public function getLatestSources()
  {
    return ScrapingSource::query()
      ->withCount('scrapedData as posts_count')
      ->active()
      ->latest()
      ->take(5)
      ->get();

  }

}