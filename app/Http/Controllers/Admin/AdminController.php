<?php

namespace App\Http\Controllers\Admin;


use App\Services\Admin\DashboardStatsService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class AdminController extends Controller
{
  public function __construct(protected DashboardStatsService $stats){}
  public function admin()
  {

     $data = [
        'user' => DB::table('users')->count(),
        'post' => DB::table('posts')->whereStatus(\App\Enums\PostStatus::PUBLISHED)->count(),
        'likes' => DB::table('likes')->count(),
        'hashtags' => DB::table('hashtags')->count(),
        'categories' => DB::table('categories')->count(),
        'comments' => DB::table('comments')->count(),
        'blocked' => DB::table('users')->where('is_blocked', 1)->count(),
        'postreports' => DB::table('post_reports')->count(),
        'profilereports' => DB::table('profile_reports')->count(),
        'commentreports' => DB::table('comment_reports')->count(),
        'data' => $this->stats->getStats(request('year', date('Y'))),
    ];


 if ( config('analytics.analytics_enabled')) {

        $analytics = $this->stats->getGoogleAnalytics();

        $data['site_analytics'] = $analytics['site_analytics'];
        $data['visitors_by_country'] = $analytics['visitors_by_country'];
        $data['most_visited_pages'] = $analytics['most_visited_pages'];
        $data['top_browsers'] = $analytics['top_browsers'];
        $data['top_referrers'] = $analytics['top_referres'];
    }


    return view('admin.adminpanel', $data);
  }

}
