<?php

namespace App\Services;

use App\Enums\MediaDriver;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class MediaDriverResolver
{
    protected array $settings = [];

    public function resolveAndApply(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $this->settings = $this->getSettings();

        $driver = $this->determineDriver();

        config([
            'filesystems.default' => $driver,
            "filesystems.disks.$driver" => $this->buildDiskConfig($driver),
        ]);
    }

    public function getSettings(): array
{
    $settings = Cache::rememberForever('app.settings.cache', fn () => Setting::pluck('value', 'key'));

    return collect($settings)->toArray();
}

    protected function determineDriver(): string
    {
        return match ($this->settings['media_driver'] ?? 'public') {
            MediaDriver::AWS->value => 's3',
            MediaDriver::DIGITAL_OCEAN->value => 'do_spaces',
            MediaDriver::CLOUDFLARE_R2->value => 'r2',
            default => 'public',
        };
    }

    protected function buildDiskConfig(string $driver): array
    {
        return match ($driver) {
            's3' => $this->s3Config(),
            'do_spaces' => $this->doSpacesConfig(),
            'r2' => $this->r2Config(),
            default => config('filesystems.disks.public'),
        };
    }

    protected function s3Config(): array
    {
        return array_merge(config('filesystems.disks.s3'), [
            'key' => $this->settings['media_aws_access_key_id'] ?? '',
            'secret' => $this->settings['media_aws_secret_key'] ?? '',
            'region' => $this->settings['media_aws_default_region'] ?? '',
            'bucket' => $this->settings['media_aws_bucket'] ?? '',
            'url' => $this->settings['media_aws_url'] ?? '',
            'endpoint' => $this->settings['media_aws_endpoint'] ?? '',
            'use_path_style_endpoint' => filter_var(
                $this->settings['media_aws_use_path_style_url'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }

    protected function doSpacesConfig(): array
    {
        return array_merge(config('filesystems.disks.do_spaces'), [
            'key' => $this->settings['media_do_access_key_id'] ?? '',
            'secret' => $this->settings['media_do_secret_key'] ?? '',
            'region' => $this->settings['media_do_default_region'] ?? '',
            'bucket' => $this->settings['media_do_bucket'] ?? '',
            'endpoint' => $this->settings['media_do_endpoint'] ?? '',
            'use_path_style_endpoint' => filter_var(
                $this->settings['media_do_use_path_style_url'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }

    protected function r2Config(): array
    {
        return array_merge(config('filesystems.disks.r2'), [
            'key' => $this->settings['media_r2_access_key_id'] ?? '',
            'secret' => $this->settings['media_r2_secret_key'] ?? '',
            'bucket' => $this->settings['media_r2_bucket'] ?? '',
            'url' => $this->settings['media_r2_url'] ?? '',
            'endpoint' => $this->settings['media_r2_endpoint'] ?? '',
            'use_path_style_endpoint' => filter_var(
                $this->settings['media_r2_use_path_style_url'] ?? false,
                FILTER_VALIDATE_BOOLEAN
            ),
        ]);
    }

}