<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Model;

class Follow extends Model
{
    // If necessary, specify the table name (not required if the table name matches Laravel's convention)
    protected $table = 'follow';

    // Disable timestamps as the table already has a custom `timestamp` field.
    public $timestamps = false;


    /**
     * Get the user who is following (follower).
     */
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    /**
     * Get the user who is being followed.
     */
    public function followed()
    {
        return $this->belongsTo(User::class, 'followed_id');
    }
}
