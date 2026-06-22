<?php

namespace App\Services;

use App\Enums\MediaDriver;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
class BackupDiskResolver
{
    public function __construct(protected MediaDriverResolver $mediaDriverResolver)
    {
      $this->mediaDriverResolver = $mediaDriverResolver;
    }
    public function resolve(): Filesystem
    {
        $settings = collect($this->mediaDriverResolver->getSettings());
        $driver = $settings->get('media_driver', MediaDriver::LOCAL->value);

        return match ($driver) {
            MediaDriver::AWS->value           => $this->buildS3($settings, prefix: 'backups'),
            MediaDriver::DIGITAL_OCEAN->value => $this->buildDoSpaces($settings, prefix: 'backups'),
            MediaDriver::CLOUDFLARE_R2->value => $this->buildR2($settings, prefix: 'backups'),
            MediaDriver::LOCAL->value         => $this->buildLocal(),
            default                           => $this->buildLocal(),
        };
    }
    protected function buildLocal(): Filesystem
    {
        return Storage::build([
            'driver' => 'local',
            'root' => storage_path('app/private/backups'),
            'visibility' => 'private',
        ]);
    }
    protected function buildS3(mixed $settings, string $prefix): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => $settings->get('media_aws_access_key_id', ''),
            'secret' => $settings->get('media_aws_secret_key', ''),
            'region' => $settings->get('media_aws_default_region', ''),
            'bucket' => $settings->get('media_aws_bucket', ''),
            'url' => $settings->get('media_aws_url', ''),
            'endpoint' => $settings->get('media_aws_endpoint', ''),
            'use_path_style_endpoint' => filter_var(
                $settings->get('media_aws_use_path_style_url', false),
                FILTER_VALIDATE_BOOLEAN
            ),
            'root' => $prefix,    
            'visibility' => 'private',
            'throw' => true,
        ]);
    }
    protected function buildDoSpaces(mixed $settings, string $prefix): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => $settings->get('media_do_access_key_id', ''),
            'secret' => $settings->get('media_do_secret_key', ''),
            'region' => $settings->get('media_do_default_region', ''),
            'bucket' => $settings->get('media_do_bucket', ''),
            'endpoint' => $settings->get('media_do_endpoint', ''),
            'use_path_style_endpoint' => filter_var(
                $settings->get('media_do_use_path_style_url', false),
                FILTER_VALIDATE_BOOLEAN
            ),
            'root' => $prefix,
            'visibility' => 'private',
            'throw' => true,
        ]);
    }
    protected function buildR2(mixed $settings, string $prefix): Filesystem
    {
        return Storage::build([
            'driver' => 's3',
            'key' => $settings->get('media_r2_access_key_id', ''),
            'secret' => $settings->get('media_r2_secret_key', ''),
            'region' => 'auto',
            'bucket' => $settings->get('media_r2_bucket', ''),
            'url' => $settings->get('media_r2_url', ''),
            'endpoint' => $settings->get('media_r2_endpoint', ''),
            'use_path_style_endpoint' => false,
            'root' => $prefix,
            'visibility' => 'private',
            'throw' => true,
        ]);
    }
    
}
