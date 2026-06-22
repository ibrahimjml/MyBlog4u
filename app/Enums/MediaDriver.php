<?php

namespace App\Enums;

enum MediaDriver: string
{
  case LOCAL = 'public';
  case AWS = 's3';
  case DIGITAL_OCEAN = 'do_spaces';
  case CLOUDFLARE_R2 = 'r2';

  public function label(): string
  {
    return match($this) {
      self::LOCAL => 'Local Disk',
      self::AWS => 'Amazon S3',
      self::DIGITAL_OCEAN => 'DigitalOcean Spaces',
      self::CLOUDFLARE_R2 => 'Cloudflare R2'
    };
  }
  public function fields(): array
    {
        return match ($this) {

            self::LOCAL => [],

            self::AWS => [
                [
                    'name' => 'media_aws_access_key_id',
                    'label' => 'AWS Access Key ID',
                    'placeholder' => 'Ex: AKIASJDKDKD',
                ],
                [
                    'name' => 'media_aws_secret_key',
                    'label' => 'AWS Secret Key',
                    'placeholder' => 'Ex: +fdffKIASJDKDKD',
                ],
                [
                    'name' => 'media_aws_default_region',
                    'label' => 'AWS Default Region',
                    'placeholder' => 'us-east-1',
                ],
                [
                    'name' => 'media_aws_bucket',
                    'label' => 'AWS Bucket',
                    'placeholder' => 'Ex: s3-bucket',
                ],
                [
                    'name' => 'media_aws_url',
                    'label' => 'AWS URL',
                    'placeholder' => 'Ex: https://s3-bucket.amazon.com',
                ],
                [
                    'name' => 'media_aws_endpoint',
                    'label' => 'AWS Endpoint',
                ],
                [
                    'name' => 'media_aws_use_path_style_url',
                    'label' => 'Use Path Style URL',
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Yes',
                    ],
                ],
            ],

            self::DIGITAL_OCEAN => [
                [
                    'name' => 'media_do_access_key_id',
                    'label' => 'DO Spaces Access Key',
                    'placeholder' => 'Ex: AKIASJDKDKD',
                ],
                [
                    'name' => 'media_do_secret_key',
                    'label' => 'DO Spaces Secret Key',
                    'placeholder' => 'Ex: +fjkfjAKIASJD',
                ],
                [
                    'name' => 'media_do_default_region',
                    'label' => 'DO Spaces Default Region',
                    'placeholder' => 'Ex: SGP1',
                ],
                [
                    'name' => 'media_do_bucket',
                    'label' => 'DO Spaces Bucket',
                    'placeholder' => 'Ex: myproject',
                ],
                [
                    'name' => 'media_do_endpoint',
                    'label' => 'DO Spaces Endpoint',
                    'placeholder' => 'Ex: https://myproject.sfo2.digitalocean',
                ],
                [
                    'name' => 'media_do_use_path_style_url',
                    'label' => 'Use Path Style URL',
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Yes',
                    ],
                ],
            ],

            self::CLOUDFLARE_R2 => [
                [
                    'name' => 'media_r2_access_key_id',
                    'label' => 'R2 Access Key',
                    'placeholder' => 'Ex: AKIASJDKDKD',
                ],
                [
                    'name' => 'media_r2_secret_key',
                    'label' => 'R2 Secret Key',
                    'placeholder' => 'Ex: +fdffAKIKDKD',
                ],
                [
                    'name' => 'media_r2_bucket',
                    'label' => 'R2 Bucket',
                    'placeholder' => 'Ex: r2-bucket',
                ],
                [
                    'name' => 'media_r2_url',
                    'label' => 'R2 URL',
                    'placeholder' => 'Ex: https://pub-jfdkfjj6df5t',
                ],
                [
                    'name' => 'media_r2_endpoint',
                    'label' => 'R2 Endpoint',
                    'placeholder' => 'Ex: https://xxx.r2.cloudflarestorage.com',
                ],
                [
                    'name' => 'media_r2_use_path_style_url',
                    'label' => 'Use Path Style URL',
                    'type' => 'select',
                    'options' => [
                        0 => 'No',
                        1 => 'Yes',
                    ],
                ],
            ],
        };
    }
}