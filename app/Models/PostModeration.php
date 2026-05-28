<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostModeration extends Model
{
    use HasFactory;

    protected $table = 'post_moderation';

    protected $fillable = [
        'enable_post_submission',
        'enable_auto_approve',
    ];

    protected $casts = [
        'enable_post_submission' => 'boolean',
        'enable_auto_approve' => 'boolean',
    ];

    public static function rules(): self
    {
        return self::query()->firstOrCreate([], [
            'enable_post_submission' => true,
            'enable_auto_approve' => false,
        ]);
    }
}
