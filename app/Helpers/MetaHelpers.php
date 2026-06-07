<?php
namespace App\Helpers;

use App\Models\SeoSetting;
use Illuminate\Support\Str;

class MetaHelpers
{
  private const DEFAULT_FAVICON = '/img/icon.png';
  private const DEFAULT_LOGO = '/img/logo.png';
  private const STORAGE_PATH = 'storage/uploads/';
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
      'og_image'         => url(self::STORAGE_PATH . $post->image_path),
      'favicon_url'      => url($seoSettings?->favicon_url) ?? url(self::DEFAULT_FAVICON),
      'header_scripts'   => $seoSettings?->header_scripts ?? '',
      'footer_scripts'   => $seoSettings?->footer_scripts ?? '',
      'og_type'          => 'article',
    ];
  }
  public static function generateDefault($title = null, $description = null, $hashtags = [], $user = null)
  {
    $favicon = SeoSetting::first()?->favicon_url ?? self::DEFAULT_FAVICON;
    return [
      'author'           => $user?->username ?? config('app.name'),
      'meta_title'       => $title ?? self::DEFAULT_TITLE,
      'meta_description' => $description ?? self::DEFAULT_DESCRIPTION,
      'meta_keywords'    => !empty($hashtags) ? implode(', ', $hashtags) : self::DEFAULT_KEYWORDS,
      'og_image'         => $user?->avatar_url ? url($user->avatar_url) : url(self::DEFAULT_LOGO),
      'favicon_url'      => url($favicon) ?? url(self::DEFAULT_FAVICON),
      'og_type'          => $user ? 'profile' : 'website',
    ];
  }
  // custom seo admin management
  public static function generateCustomSeo($author, $favicon,$ogImage, $title = null, $description = null, $keywords = [], $headerScripts = null, $footerScripts = null)
  {
    return [
      'author'           => $author ?? config('app.name'),
      'favicon_url'      => url($favicon) ?? url(self::DEFAULT_FAVICON),
      'og_image'         => url($ogImage) ?? url(self::DEFAULT_LOGO),
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