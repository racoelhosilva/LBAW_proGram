<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'text',
        'creation_timestamp',
        'is_announcement',
        'is_public',
        'likes',
        'comments',
        'tsvector',
    ];

    protected $casts = [
        'creation_timestamp' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function allLikes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_like', 'post_id', 'liker_id')->withPivot('timestamp');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->orderBy('timestamp', 'asc');
    }
}
