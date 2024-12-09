@props(['user', 'remove' => false])

@php($userUrl = route('user.show', $user->id))

<article class="card px-6 flex justify-between">
    <div class="flex items-center">
        <a href="{{ $userUrl }}">
            <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }}"
                class="w-12 h-12 me-4 rounded-full object-cover">
        </a>
        <div>
            <p class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $user->name }}</a></p>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none"><a
                    href="{{ $userUrl }}">{{ '@' . $user->handle }}</a>{{ ' • ' . $user->num_followers . ' followers' }}
            </p>
        </div>
    </div>

    @if(auth()->check() && auth()->id() !== $user->id)
        @if($remove)
            <button aria-label="RemoveFollower" class="p-3 secondary-btn remove-follower-button" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'remove'])
            </button>
        @else
            <button aria-label="FollowCard" class="p-3 secondary-btn follow-card-button {{ Auth::user()->follows($user) ? "following" : (Auth::user()->getFollowRequestStatus($user) ? "pending" : "unfollowing") }}" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'follow'])
                @include('partials.icon', ['name' => 'pending'])
                @include('partials.icon', ['name' => 'unfollow'])
            </button>
        @endif
    @endif

</article>
