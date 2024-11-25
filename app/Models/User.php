<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Added to define Eloquent relationships.
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'register_timestamp',
        'handle',
        'is_public',
        'is_deleted',
        'description',
        'profile_picture_url',
        'banner_image_url',
        'num_followers',
        'num_following',
        'tsvectors',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'register_timestamp' => 'datetime',
    ];

    public function stats(): HasOne
    {
        return $this->hasOne(UserStats::class);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_member')->withPivot('join_timestamp');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    public function groupInvitations(): HasMany
    {
        return $this->hasMany(GroupInvitation::class, 'invitee_id');
    }

    public function followRequests(): HasMany
    {
        return $this->hasMany(FollowRequest::class, 'followed_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'receiver_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'followed_id', 'follower_id')->withPivot('timestamp');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'follower_id', 'followed_id')->withPivot('timestamp');
    }

    public function getProfilePicture()
    {
        return FileController::get('/profile', $this->id);
    }
}
