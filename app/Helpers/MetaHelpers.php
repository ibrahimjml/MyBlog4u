<?php
namespace App\Helpers;

use App\Models\SeoSetting;
use Illuminate\Support\Facades\Storage;

class MetaHelpers
{
  private const DEFAULT_KEYWORDS = 'laravel, blogpost, myblog, links, link, cv, portfolio, aggregation, platform, social, media, profile';
  private const DEFAULT_DESCRIPTION = 'Myblog4u a social network that connect creators, writes, publishers';
  private const DEFAULT_TITLE = 'Myblog4u Platform';
  public static function generateMetaForPosts($post)
  {

    $hashtags = $post->hashtags->pluck('name')->implode(', ');
    $metaKeywords = $hashtags ?? self::DEFAULT_KEYWORDS;

    $metaDescription = $post->short_excerpt;

    $author = $post->user->username ?? config('app.name');
    $seoSettings = SeoSetting::select('header_scripts', 'footer_scripts','favicon_path')->first();
    return [
      'author'           => $author,
      'meta_title'       => $post->title ?? self::DEFAULT_TITLE,
      'meta_description' => $metaDescription ?? self::DEFAULT_DESCRIPTION,
      'meta_keywords'    => $metaKeywords ,
      'og_image'         => Storage::disk(media_driver())->url('uploads/' . $post->image_path) ?: url('img/logo2.png'),
      'favicon_url'      => $seoSettings?->favicon_url ?? url('img/icon.png'),
      'header_scripts'   => $seoSettings?->header_scripts ?? '',
      'footer_scripts'   => $seoSettings?->footer_scripts ?? '',
      'og_type'          => 'article',
    ];
  }
  public static function generateDefault($title = null, $description = null, $hashtags = [], $user = null)
  {
    $favicon = SeoSetting::first()?->favicon_url;

    return [
      'author'           => $user?->username ?? config('app.name'),
      'meta_title'       => $title ?? self::DEFAULT_TITLE,
      'meta_description' => $description ?? self::DEFAULT_DESCRIPTION,
      'meta_keywords'    => !empty($hashtags) ? implode(', ', $hashtags) : self::DEFAULT_KEYWORDS,
      'og_image'         =>  $user?->avatar_url ?: url('img/logo2.png'),
      'favicon_url'      => url($favicon) ?? url('img/icon.png'),
      'og_type'          => $user ? 'profile' : 'website',
    ];
  }
  // custom seo admin management
  public static function generateCustomSeo($author, $favicon,$ogImage, $title = null, $description = null, $keywords = [], $headerScripts = null, $footerScripts = null)
  {
    return [
      'author'           => $author ?? config('app.name'),
      'favicon_url'      => url($favicon) ?: url('img/icon.png'),
      'og_image'         => url($ogImage) ?: url('img/logo2.png'),
      'meta_title'       => $title ?? self::DEFAULT_TITLE,
      'meta_description' => $description ?? self::DEFAULT_DESCRIPTION,
      'meta_keywords'    => !empty($keywords) ? implode(', ', $keywords) : self::DEFAULT_KEYWORDS,
      'og_type'          => 'website',
      'header_scripts'   => $headerScripts ?? '',
      'footer_scripts'   => $footerScripts ?? '',
    ];
  }
  public static function shareToView(array $meta): void
  {
    foreach ($meta as $key => $value) {
      view()->share($key, $value);
    }
  }
}