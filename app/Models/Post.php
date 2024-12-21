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
        return $this->hasMany(Comment::class, 'post_id')->orderBy('timestamp', 'desc');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PostAttachment::class, 'post_id');
    }

    public function hasTag(Tag $tag): bool
    {
        return $this->tags->contains($tag);
    }

    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_post', 'post_id', 'group_id');
    }

    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        return $query->where(function ($subQuery) use ($user) {
            $subQuery->where('post.is_public', true);

            if ($user) {
                // User's own posts
                $subQuery->orWhere('author_id', $user->id);

                // Posts from followed users (excluding group posts)
                $followedUserIds = $user->following->pluck('id');
                if ($followedUserIds->isNotEmpty()) {
                    $subQuery->orWhere(function ($followQuery) use ($followedUserIds) {
                        $followQuery->whereIn('author_id', $followedUserIds)
                            ->whereNotExists(function ($groupCheck) {
                                $groupCheck->selectRaw(1)
                                    ->from('group_post')
                                    ->whereColumn('group_post.post_id', 'post.id');
                            });
                    });
                }

                // Group posts where the user is a member
                $subQuery->orWhereExists(function ($groupQuery) use ($user) {
                    $groupQuery->selectRaw(1)
                        ->from('group_post')
                        ->join('group_member', 'group_member.group_id', '=', 'group_post.group_id')
                        ->whereColumn('group_post.post_id', 'post.id')
                        ->where('group_member.user_id', $user->id);
                });
            }
        });
    }
}
