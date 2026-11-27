<?php

namespace App\Models;

use App\Support\BrowserTokenSupport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiveSession extends Model
{
    protected $fillable = [
        'user_id', 'session_id', 'browser_token', 'ip_address', 'user_agent', 'browser', 'platform', 'last_active_at', 'logged_out_at',
    ];

    protected $hidden = ['browser_token', 'user_agent'];

    protected $casts = [
        'last_active_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('logged_out_at');
    }

    public function scopeCurrentSession(Builder $query, User $user, ?string $hashedToken): Builder
    {
        return $query->where('user_id', $user->id)
            ->where('browser_token', $hashedToken);
    }

    public function scopeCurrentBrowser(Builder $query, string $sessionId, ?string $browserToken): Builder
    {
        $hashedToken = BrowserTokenSupport::hash($browserToken);

        return $hashedToken
            ? $query->where('browser_token', $hashedToken)
            : $query->where('session_id', $sessionId);
    }

    public function isCurrentBrowser(string $sessionId, ?string $browserToken): bool
    {
        return static::query()
            ->whereKey($this->getKey())
            ->currentBrowser($sessionId, $browserToken)
            ->exists();
    }
}
