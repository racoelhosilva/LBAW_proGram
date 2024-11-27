<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserStats extends Model
{
    use HasFactory;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

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
        return $this->belongsToMany(Technology::class, 'user_stats_technology', 'user_stats_id', 'technology_id');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'user_stats_language', 'user_stats_id', 'language_id');
    }

    public function topProjects(): HasMany
    {
        return $this->hasMany(TopProject::class, 'user_stats_id');
    }
}
