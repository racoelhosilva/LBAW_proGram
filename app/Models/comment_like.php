<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Model;

class CommentLike extends Model
{


      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'liker_id',
        'comment_id',
        'timestamp',
    ];


    public $timestamps = false;


    // Relationship with the User model (liker).
    public function user()
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    // Relationship with the Comment model.
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
