<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;


   
    public $timestamps = false;


    protected $fillable = [
        'liker_id',
        'comment_id',
        'timestamp',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'liker_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }
}
