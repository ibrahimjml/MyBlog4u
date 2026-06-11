<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activation extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id',
        'completed',
        'completed_at',
    ];
    protected $casts = [
      'completed_at' => 'datetime',
      'completed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
