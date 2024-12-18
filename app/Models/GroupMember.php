<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupMember extends Pivot
{
    protected $table = 'group_member';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'join_timestamp',
        'user_id',
        'group_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];
}
