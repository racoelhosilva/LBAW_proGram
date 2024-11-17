<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentLike extends Model
{
    use HasFactory;

    protected $table = 'comment_like';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
