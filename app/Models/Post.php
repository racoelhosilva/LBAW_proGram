<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'text',
        'creation_timestamp',
        'is_announcement',
        'is_public',
        'likes',
        'comments',
    ];
}
