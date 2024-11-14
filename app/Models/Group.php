<?php

namespace App\Models;

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
        'owner_id',
        'name',
        'description',
        'creation_timestamp',
        'is_public',
        'member_count',
    ];

    // A group belongs to an owner (user)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members', 'group_id', 'user_id');
    }
}
