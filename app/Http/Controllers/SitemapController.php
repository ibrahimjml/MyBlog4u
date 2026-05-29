<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function __invoke()
    {
        $posts = \App\Models\Post::query()->published()->latest()->get();
        $categories = \App\Models\Category::query()->latest()->get();
        $hashtags = \App\Models\Hashtag::active()->latest()->get();

        return response()->view('sitemap', compact('posts', 'categories', 'hashtags'))->header('Content-Type', 'text/xml');
    }
}
