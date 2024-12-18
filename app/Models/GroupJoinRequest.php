<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupJoinRequest extends Model
{
    use HasFactory;

    protected $table = 'group_join_request';

    public $timestamps = false;

    protected $fillable = [
        'status',
        'group_id',
        'requester_id',
    ];

    protected $casts = [
        'creation_timestamp' => 'datetime',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
}
