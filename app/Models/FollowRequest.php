<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Model;

class FollowRequest extends Model
{
    // Define the table name if it's different from Laravel's default naming convention.
    protected $table = 'follow_request';

    // Disable the default timestamps since you have a custom `creation_timestamp` field.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'follower_id',
        'followed_id',
        'creation_timestamp',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'creation_timestamp' => 'datetime',
    ];

    /**
     * Get the follower user who requested to follow another user.
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the followed user who received the follow request.
     */
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
