<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

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
