<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Administrator
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $register_timestamp
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereRegisterTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Administrator whereRememberToken($value)
 */
	class Administrator extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ban
 *
 * @property int $id
 * @property int $user_id
 * @property int $administrator_id
 * @property string $start
 * @property string $reason
 * @property string $duration
 * @property bool $is_active
 * @property-read \App\Models\Administrator $administrator
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Ban active()
 * @method static \Illuminate\Database\Eloquent\Builder|Ban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ban query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereAdministratorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ban whereUserId($value)
 */
	class Ban extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $post_id
 * @property int $author_id
 * @property string $content
 * @property int $likes
 * @property \Illuminate\Support\Carbon $timestamp
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CommentLike> $allLikes
 * @property-read int|null $all_likes_count
 * @property-read \App\Models\User $author
 * @property-read \App\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereTimestamp($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CommentLike
 *
 * @property int $id
 * @property int $liker_id
 * @property int $comment_id
 * @property string $timestamp
 * @property-read \App\Models\Comment $comment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereLikerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CommentLike whereTimestamp($value)
 */
	class CommentLike extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Follow
 *
 * @property int $id
 * @property int $follower_id
 * @property int $followed_id
 * @property \Illuminate\Support\Carbon $timestamp
 * @property-read \App\Models\User $followed
 * @property-read \App\Models\User $follower
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow query()
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereFollowedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereFollowerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Follow whereTimestamp($value)
 */
	class Follow extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Group
 *
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string|null $description
 * @property string $creation_timestamp
 * @property bool $is_public
 * @property int $member_count
 * @property string|null $tsvectors
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $invitedUsers
 * @property-read int|null $invited_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $joinRequests
 * @property-read int|null $join_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
 * @property-read int|null $members_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreationTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereMemberCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereTsvectors($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GroupMember
 *
 * @property int $user_id
 * @property int $group_id
 * @property string $join_timestamp
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereJoinTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereUserId($value)
 */
	class GroupMember extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GroupPost
 *
 * @property int $post_id
 * @property int $group_id
 * @method static \Illuminate\Database\Eloquent\Builder|GroupPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupPost query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupPost whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupPost wherePostId($value)
 */
	class GroupPost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Language whereName($value)
 */
	class Language extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Post
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string|null $text
 * @property \Illuminate\Support\Carbon $creation_timestamp
 * @property bool $is_announcement
 * @property bool $is_public
 * @property int $likes
 * @property int $comments
 * @property string|null $tsvectors
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $allComments
 * @property-read int|null $all_comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostLike> $allLikes
 * @property-read int|null $all_likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PostAttachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $group
 * @property-read int|null $group_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tag> $tags
 * @property-read int|null $tags_count
 * @method static \Illuminate\Database\Eloquent\Builder|Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Post query()
 * @method static \Illuminate\Database\Eloquent\Builder|Post visibleTo(?\App\Models\User $user)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereCreationTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsAnnouncement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereLikes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Post whereTsvectors($value)
 */
	class Post extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PostLike
 *
 * @property int $id
 * @property int $liker_id
 * @property int $post_id
 * @property \Illuminate\Support\Carbon $timestamp
 * @property-read \App\Models\User $liker
 * @property-read \App\Models\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereLikerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PostLike whereTimestamp($value)
 */
	class PostLike extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tag whereName($value)
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Technology
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Technology newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Technology newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Technology query()
 * @method static \Illuminate\Database\Eloquent\Builder|Technology whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Technology whereName($value)
 */
	class Technology extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Token
 *
 * @property int $id
 * @property string $value
 * @property int|null $user_id
 * @property int|null $administrator_id
 * @property \Illuminate\Support\Carbon $creation_timestamp
 * @property \Illuminate\Support\Carbon $validity_timestamp
 * @property-read \App\Models\User|null $account
 * @method static \Illuminate\Database\Eloquent\Builder|Token newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Token query()
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereAdministratorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereCreationTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereValidityTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Token whereValue($value)
 */
	class Token extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TopProject
 *
 * @property int $id
 * @property int $user_stats_id
 * @property string $name
 * @property string $url
 * @property-read \App\Models\UserStats $owner
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopProject whereUserStatsId($value)
 */
	class TopProject extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property mixed|null $password
 * @property string|null $google_id
 * @property string|null $github_id
 * @property string|null $gitlab_id
 * @property \Illuminate\Support\Carbon $register_timestamp
 * @property string $handle
 * @property bool $is_public
 * @property bool $is_deleted
 * @property string|null $description
 * @property string|null $profile_picture_url
 * @property string|null $banner_image_url
 * @property int $num_followers
 * @property int $num_following
 * @property string|null $remember_token
 * @property string|null $tsvectors
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $allNotifications
 * @property-read int|null $all_notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ban> $bans
 * @property-read int|null $bans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowRequest> $followRequests
 * @property-read int|null $follow_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $following
 * @property-read int|null $following_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FollowRequest> $followingRequests
 * @property-read int|null $following_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupInvitation> $groupInvitations
 * @property-read int|null $group_invitations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupJoinRequest> $groupJoinRequests
 * @property-read int|null $group_join_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groupsInvitedTo
 * @property-read int|null $groups_invited_to_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
 * @property-read int|null $posts_count
 * @property-read \App\Models\UserStats|null $stats
 * @property-read \App\Models\Token|null $token
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBannerImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGithubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGitlabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGoogleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegisterTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTsvectors($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserStats
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $github_url
 * @property string|null $gitlab_url
 * @property string|null $linkedin_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Language> $languages
 * @property-read int|null $languages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Technology> $technologies
 * @property-read int|null $technologies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TopProject> $topProjects
 * @property-read int|null $top_projects_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereGithubUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereGitlabUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereLinkedinUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserStats whereUserId($value)
 */
	class UserStats extends \Eloquent {}
}

