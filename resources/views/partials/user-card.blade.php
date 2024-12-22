@props(['user', 'remove' => false, 'responsive' => false])

@php
    $userUrl = route('user.show', $user->id);
    $canFollow = auth()->check() && auth()->user()->can('follow', $user);

    if ($canFollow && !$remove) {
        if (auth()->user()->follows($user)) {
            $followClass = "following";
        } elseif (auth()->user()->getFollowRequestStatus($user)) {
            $followClass = "pending";
        } else {
            $followClass = "unfollowing";
        }
    }
@endphp

<article class="card px-6 grid grid-cols-[auto_1fr_auto] items-center gap-2">
    @if (!$user->is_deleted)
        <a href="{{ $userUrl }}">
            <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }}"
                 class="w-12 h-12 rounded-full object-cover">
        </a>
    @else
        <img src="{{ $user->getProfilePicture() }}" alt="Deleted Account"
             class="w-12 h-12 rounded-full object-cover">
    @endif

    <div class="{{ $responsive ? 'row-start-2 col-span-3 2xl:row-start-1 2xl:col-span-1 2xl:col-start-2' : '' }}">
        @if (!$user->is_deleted)
            <h2 class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $user->name }}</a></h2>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none"><a
                        href="{{ $userUrl }}">{{ '@' . $user->handle }}</a>{{ ' • ' . $user->num_followers . ' followers' }}
            </p>
        @else
            <h2 class="text-base/4 font-medium">[deleted]</h2>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none">[deleted]</p>
        @endif
    </div>

    @if($canFollow)
        @if($remove)
            <button aria-label="Remove Follower" class="p-3 secondary-btn remove-follower-button col-start-3" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'remove'])
            </button>
        @else
            <button aria-label="Follow/Unfollow/Remove Pending" class="p-3 secondary-btn follow-card-button col-start-3 {{ $followClass }}" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'follow'])
                @include('partials.icon', ['name' => 'pending'])
                @include('partials.icon', ['name' => 'unfollow'])
            </button>
        @endif
    @endif
</article>

