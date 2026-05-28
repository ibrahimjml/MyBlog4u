<?php 
namespace App\Helpers;

use Illuminate\Support\Str;

class MetaHelpers{

  public static function generateMetaForPosts($post){

    $hashtags = $post->hashtags->pluck('name')->implode(', ');
    $metaKeywords = $hashtags ?? '';
    
    $metaDescription = $post->short_excerpt;

    $author = $post->user->username ?? config('app.name', 'Blog-Post');

    return [
      'meta_title' => $post->title . ' | Blog-Post',
      'meta_description' => $metaDescription,
      'meta_keywords' => $metaKeywords,
      'author' => $author,
      'og_image' => url('storage/uploads/' . $post->image_path),
      'og_type' => 'article',
    ];
  }
  public static function generateDefault($title = null, $description = null, $hashtags = [],$user = null)
  {
      return [
          'meta_title' => $title,
          'meta_description' => $description ,
          'meta_keywords' => !empty($hashtags) ? implode(', ', $hashtags) : 'blog, post, article',
          'author' => $user?->username ,
          'og_image' => $user?->avatar_url ? url($user->avatar_url) : url('/img/logo.png'),
          'og_type' => $user ? 'profile':'website',
      ];
  }
  public static function setSection(array $meta): void
   {
    foreach ($meta as $key => $value) {
        view()->share($key, $value);
        app('view')->startSection($key, $value);
    }
   }
}