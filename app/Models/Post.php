<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'author_id',
        'text',
        'creation_timestamp',
        'is_announcement',
        'is_public',
        'likes',
        'comments',
        'tsvectors',
    ];

    protected $casts = [
        'creation_timestamp' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PostTag::class, 'post_id');
    }

    public function allLikes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class, 'post_id');
    }
}
