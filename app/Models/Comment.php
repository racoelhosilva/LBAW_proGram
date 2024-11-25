<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'likes',
        'timestamp',
        'post_id',
        'author_id',
    ];

    // The attributes that should be cast
    protected $casts = [
        'timestamp' => 'datetime',
    ];

    /**
     * Get the post that the comment belongs to.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Get the user (author) who made the comment.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function allLikes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    public function likedBy(User $user): bool
    {
        return $this->allLikes()->where('liker_id', $user->id)->exists();
    }
}
