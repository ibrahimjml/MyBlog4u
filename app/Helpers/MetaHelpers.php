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
  // custom seo admin management
  public static function generateCustomSeo($title = null, $description = null, $keywords = [],$ogImage,$favicon,$author,$headerScripts = null,$footerScripts = null)
  {
      return [
          'meta_title' => $title ?? 'Myblog4u a social network that connect creators',
          'meta_description' => $description ?? 'Myblog4u a social network that connect creators Myblog4u a social network that connect creators',
          'meta_keywords' => !empty($keywords) ? implode(', ', $keywords) : 'laravel, blogpost, myblog, links, link, cv, portfolio, aggregation, platform, social, media, profile',
          'author' => $author ?? config('app.name') ,
          'og_image' =>  url($ogImage) ?? url('/img/logo.png'),
          'favicon_url' => url($favicon) ?? url('/img/icon.png'),
          'og_type' => 'website',
          'header_scripts' => $headerScripts ?? '',
          'footer_scripts' => $footerScripts ?? '',
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