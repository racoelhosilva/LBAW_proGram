<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TopProject extends Model
{
    use HasFactory;

    protected $table = 'top_project';

    protected $fillable = [
        'name',
        'url',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(UserStats::class, 'user_stats_id')->with('user');
    }
}