<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Card
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Card query()
 */
	class Card extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Item
 *
 * @property-read \App\Models\Card|null $card
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 */
	class Item extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property int $user_stats_id
 * @property string $name
 * @property string $email
 * @property mixed $password
 * @property string $register_timestamp
 * @property string $handle
 * @property bool $is_public
 * @property string|null $description
 * @property string|null $profile_picture_url
 * @property string|null $banner_image_url
 * @property int $num_followers
 * @property int $num_following
 * @property string|null $tsvectors
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Card> $cards
 * @property-read int|null $cards_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBannerImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHandle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumFollowers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumFollowing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePictureUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegisterTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTsvectors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserStatsId($value)
 */
	class User extends \Eloquent {}
}

