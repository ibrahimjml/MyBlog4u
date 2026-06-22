<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
trait ImageUploadTrait
{   
    // user upload image
    public function uploadImage(UploadedFile $imageFile, string $slug)
    {
      
        $newimage = uniqid() . '-' . $slug . '.' . $imageFile->extension();
          $image = Image::read($imageFile)
          ->cover(1300, 600)
          ->toWebp(90);
          Storage::disk(media_driver())->put("uploads/{$newimage}", $image);
          return $newimage;
    }
    // user avatar image
    public function uploadAvatarImage(UploadedFile $imageFile, string $username)
    {
        $newavatar = uniqid() . '-avatar-'.$username . '.' . $imageFile->extension();
          $image = Image::read($imageFile)
          ->scale(150, 150)
          ->toWebp(90);
          Storage::disk(media_driver())->put("avatars/{$newavatar}", $image);
          return $newavatar;
    }
    // user cover image
    public function uploadCoverImage(UploadedFile $imageFile, string $username)
    {
        $newcover = uniqid() . '-cover-'.$username . '.' . $imageFile->extension();
          $image = Image::read($imageFile)
          ->cover(1500, 500)
          ->toWebp(75);
          Storage::disk(media_driver())->put("covers/{$newcover}", $image);
          return $newcover;
    }
    // slide image
    public function uploadImageSlide(UploadedFile $imageFile, string $username)
    {
         $newslide = uniqid() . '-slide-'.$username . '.' . $imageFile->extension();
         $slide = Image::read($imageFile)
              ->scaleDown(1500, 600) 
              ->toWebp(75);
              Storage::disk(media_driver())->put("slides/{$newslide}",$slide);
          
          return $newslide;
    }
    // meta og graph image
    public function uploadMetaOgImage(UploadedFile $imageFile, string $appName)
    {
        $newMetaOgImage = 'meta-og-'.$appName . '.' . $imageFile->extension();
        $og_image = Image::read($imageFile)
              ->cover(1200, 630) 
              ->toWebp(90);
          Storage::disk(media_driver())->put( "img/{$newMetaOgImage}",$og_image);

          return $newMetaOgImage;
    }
    // favicon 
    public function uploadFavicon(UploadedFile $imageFile)
    {
        $newFavicon = 'favicon.' . $imageFile->extension();
        $favicon = Image::read($imageFile)
              ->scaleDown(64, 64) 
              ->toWebp(90);
          Storage::disk(media_driver())->put("img/{$newFavicon}",$favicon);
          
          return $newFavicon;
    }
}
