<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table = 'groups';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'creation_timestamp',
        'is_public',
        'member_count',
        'tsvectors'
    ];

    // A group belongs to an owner (user)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_member')->withPivot('join_timestamp');
    }

    public function joinRequests(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_join_request', 'group_id', 'requester_id')->withPivot('creation_timestamp', 'status');
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'group_post', 'group_id', 'post_id');
    }
}
