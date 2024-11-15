<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostLike extends Model
{
    use HasFactory;

    protected $table = 'post_like';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function liker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
