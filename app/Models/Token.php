<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    protected $table = 'token';

    protected $fillable = [
        'value',
        'creation_timestamp',
        'validity_timestamp',
    ];

    protected $casts = [
        'creation_timestamp' => 'datetime',
        'validity_timestamp' => 'datetime',
    ];

    // TODO: get user or admin?
}
