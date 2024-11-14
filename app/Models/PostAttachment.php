<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;

    protected $table = 'post_attachment';

    public $timestamps = false;

    protected $fillable = [
        'url',
        'type',
    ];
}
