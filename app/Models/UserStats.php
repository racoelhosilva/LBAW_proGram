<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class UserStats extends Model
{
    use HasFactory;

    protected $table = 'user_stats';

    protected $fillable = [
        'github_url',
        'gitlab_url',
        'linkedin_url',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class, 'user_stats_technology', 'technology_id', 'user_stats_id');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'user_stats_language', 'language_id', 'user_stats_id');
    }
}
