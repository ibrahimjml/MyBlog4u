<?php

namespace App\Actions;

use App\Models\Post;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteEmbeddedPostAction
{
    public function execute(Post $post)
    {
       if (empty($post->description)) {
            return;
        }

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($post->description);

        foreach ($dom->getElementsByTagName('img') as $img) {

            $src = $img->getAttribute('src');

            $path = ltrim(parse_url($src, PHP_URL_PATH), '/');

            if (media_driver() === 'public') {
                $path = str_replace('/storage/', 'public/', $path);
            }

            if (Storage::disk(media_driver())->exists($path)) {
                Storage::disk(media_driver())->delete($path);

                Log::info("Deleted TinyMCE image {$path}");
            } else {
                Log::warning("TinyMCE image not found {$path}");
            }
        }

        libxml_clear_errors();
    }
    }

