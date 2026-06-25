<?php

namespace App\Services\Admin;

use App\Models\Post;
use App\Models\User;
use Google\Analytics\Data\V1beta\OrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Illuminate\Support\Facades\DB;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;
class DashboardStatsService
{
  public function getStats($year)
  {
    $registeredusers = User::select(DB::raw('COUNT(*) as count'), DB::raw('MONTHNAME(created_at) as month'))
      ->whereYear('created_at', $year)
      ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
      ->pluck('count', 'month')
      ->toArray();
    $numberofposts = Post::select(DB::raw('COUNT(*) as count'), DB::raw('MONTHNAME(created_at) as month'))
      ->whereYear('created_at', $year)
      ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
      ->pluck('count', 'month')
      ->toArray();
    return [
      'registeredusers' => $registeredusers,
      'numberofposts' => $numberofposts
    ];
  }
  public function getGoogleAnalytics()
  {
    return [
      'site_analytics' => $this->getSiteAnalytics( ),
      'visitors_by_country' => $this->getVisitorsByCountry(),
      'most_visited_pages' => $this->getMostVisitedPages(),
      'top_browsers' => $this->getTopBrowsers(),
      'top_referres' => $this->getTopReferres(),
    ];
  }

private function getSiteAnalytics()
{
  if(! config('analytics.analytics_enabled')) return;
    $analytics = Analytics::fetchTotalVisitorsAndPageViews(Period::days(30));

    return [
        'visitors'    => $analytics->sum('activeUsers'),      
        'page_views'  => $analytics->sum('screenPageViews'),  
        'bounce_rate' => $this->calculateBounceRate(),
        'days'        => $analytics->count(),                
    ];
}
  private function getMostVisitedPages()
  {
    if(! config('analytics.analytics_enabled')) return;
     $analytics = Analytics::fetchMostVisitedPages(Period::days(30));
     return $analytics
      ->filter(fn($item) =>
            !str_contains($item['fullPageUrl'], 'gtm_latency') &&
            !str_contains($item['fullPageUrl'], 'gtm_debug') &&
            !str_contains($item['fullPageUrl'], 'gtm_preview') 
        )
     ->map(fn($item) => [
        'url'        => $item['fullPageUrl'],
        'title'      => $item['pageTitle'],
        'page_views' => (int) $item['screenPageViews'],
    ])->values()->toArray();


  }
  private function getTopBrowsers()
  {
    if(! config('analytics.analytics_enabled')) return;
      $analytics = Analytics::fetchTopBrowsers(Period::days(30));
       return $analytics->map(fn($item) => [
        'browser'        => $item['browser'],
        'page_views' => (int) $item['screenPageViews'],
    ])->values()->toArray();
  }
  private function getTopReferres()
  {
    if(! config('analytics.analytics_enabled')) return;
     $response = Analytics::get(
        period: Period::days(30),
        metrics: ['screenPageViews'],
        dimensions: ['sessionSource'],
        orderBy: [
            new OrderBy([
                'metric' => new MetricOrderBy(['metric_name' => 'screenPageViews']),
                'desc'   => true,
            ]),
        ],
        maxResults: 10,
    );

    return $response
        ->filter(fn($row) =>
            !empty($row['sessionSource']) &&
            $row['sessionSource'] !== '(not set)'
        )
        ->map(fn($row) => [
            'source'   => $row['sessionSource'],  
            'page_views' => (int) $row['screenPageViews'],
        ])
        ->values()
        ->toArray();
  }
  private function getVisitorsByCountry(): array|null
{
  if(! config('analytics.analytics_enabled')) return null;
    $response = Analytics::get(
        period: Period::days(30),
        metrics: ['activeUsers'],
        dimensions: ['countryId', 'country'], 
      orderBy: [
            new OrderBy([
                'metric' => new MetricOrderBy(['metric_name' => 'activeUsers']),
                'desc'   => true,
            ]),
        ],

    );

 return $response
        ->filter(fn($row) =>
            !empty($row['countryId']) &&
            $row['countryId'] !== 'ZZ' &&
            $row['country'] !== '(not set)' &&
            $row['countryId'] !== '(not set)'
        )
        ->map(fn($row) => [
            'code'     => strtolower($row['countryId']),
            'name'     => $row['country'],
            'visitors' => (int) $row['activeUsers'],
        ])
        ->values()
        ->toArray();
}
  private function calculateBounceRate(): float
    {
        $period = Period::days(30);
        $data = Analytics::get($period, ['bounceRate']);
        return isset($data[0]['bounceRate']) ? round((float) $data[0]['bounceRate'] * 100, 2) : 0.0;
    }
}
