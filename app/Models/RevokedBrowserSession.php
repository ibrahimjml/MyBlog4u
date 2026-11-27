<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevokedBrowserSession extends Model
{
    use MassPrunable;

    protected $fillable = ['user_id', 'browser_token', 'revoked_at'];
    protected $hidden = ['browser_token'];
    protected $casts = ['revoked_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prunable(): Builder
    {
        return static::query()->where('revoked_at', '<=', now()->subMonth());
    }
}
