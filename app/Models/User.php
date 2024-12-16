<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\FileController;
use Illuminate\Auth\Passwords\CanResetPassword;
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
    use CanResetPassword, HasApiTokens, HasFactory, Notifiable;

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
        'google_id',
        'github_id',
        'gitlab_id',
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

    public function groupJoinRequests(): HasMany
    {
        return $this->hasMany(GroupJoinRequest::class, 'requester_id');
    }

    public function groupsInvitedTo()
    {
        return $this->belongsToMany(Group::class, 'group_invitation', 'invitee_id', 'group_id')->withPivot('creation_timestamp')->where('status', 'pending');
    }

    public function followRequests(): HasMany
    {
        return $this->hasMany(FollowRequest::class, 'followed_id');
    }

    public function followingRequests(): HasMany
    {
        return $this->hasMany(FollowRequest::class, 'follower_id');
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
        return FileController::get('profile', $this->id);
    }

    public function getBannerImage()
    {
        return FileController::get('banner', $this->id);
    }

    public function updateBannerImage($file)
    {
        return FileController::updateImage('banner', $this->id, $file);
    }

    public function updateProfileImage($file)
    {

        return FileController::updateImage('profile', $this->id, $file);
    }

    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class, 'user_id');
    }

    public function isBanned(): bool
    {
        return $this->bans()->active()->exists();
    }

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function lastActiveBan(): ?Ban
    {
        return $this->bans()
            ->active()
            ->orderByRaw('CASE WHEN duration = \'00:00:00\' THEN 0 ELSE 1 END, start + duration DESC')
            ->first();
    }

    public function follows(User $user): bool
    {
        return $this->following()->where('followed_id', $user->id)->exists();
    }

    public function getFollowRequestStatus(User $followed)
    {
        $followRequest = $this->followingRequests()
            ->where('followed_id', $followed->id)->latest('creation_timestamp')->first();

        return $followRequest?->status;
    }
}
