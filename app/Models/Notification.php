<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    public $timestamps = false;

    protected $fillable = [
        'timestamp',
        'is_read',
        'type',
    ];
}
