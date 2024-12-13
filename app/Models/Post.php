<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    public function allLikes(): HasMany
    {
        return $this->hasMany(PostLike::class, 'post_id');
    }

    public function likedBy(User $user): bool
    {
        return $this->allLikes->contains('liker_id', $user->id);
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->orderBy('timestamp', 'asc');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class, 'post_id');
    }

    public function hasTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        $query = $query->where('is_public', true);

        // Check if the post is a group post
        $isGroupPost = $query->getQuery()->joins && $query->getQuery()->joins[0]->table === 'group_posts';

        if ($user && ! $isGroupPost) {
            $query = $query->orWhere('author_id', $user->id)
                ->orWhereIn('author_id', $user->following->pluck('id'));
        }

        if ($user && $isGroupPost) {
            $query = $query->orWhere(function ($subQuery) use ($user) {
                $subQuery->join('groups', 'groups.id', '=', 'group_posts.group_id')
                    ->join('group_members', 'group_members.group_id', '=', 'groups.id')
                    ->where('group_members.user_id', $user->id);
            });
        }

        return $query;
    }
}
