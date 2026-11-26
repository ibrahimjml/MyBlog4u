<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevokedBrowserSession extends Model
{
    protected $fillable = ['user_id', 'browser_token', 'revoked_at'];
    protected $hidden = ['browser_token'];
    protected $casts = ['revoked_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
