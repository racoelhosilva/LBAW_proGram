<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
