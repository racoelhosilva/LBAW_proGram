@props(['notification'])

@php($userUrl = route('user.show', $notification->user->id))

<article class="card px-6 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ $userUrl }}">
            <img src="{{ $notification->user->getProfilePicture() }}" alt="{{ $notification->user->name }}"
                class="w-12 h-12 me-4 rounded-full object-cover">
        </a>
        <div>
            <p class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $notification->user->name }}</a> 
            @switch($notification->type)
                @case('post_like')
                    liked your post "<a href="{{ route('post.show', $notification->post->id) }}">{{ $notification->post->title }}"</a>
                    @break
                @case('comment_like')
                    liked your comment on post "<a href="{{ route('post.show', $notification->post->id) }}">{{ $notification->post->title }}"</a>
                    @break
                @case('comment')
                    commented on your post "<a href="{{ route('post.show', $notification->post->id) }}">{{ $notification->post->title }}"</a>
                    @break
                @case('follow')
                    started following you
                    @break
                @default
                    @break
            @endswitch
            </p>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none">
                {{ $notification->timestamp->diffForHumans() }}
            </p>
        </div>
    </div>
    @include('partials.text-button', [
        'text' => 'Mark as read',
        'id' => 'read-notification-button',
        'type' => 'secondary',
        'data' => [
            'notificationId' => $notification->id,
        ],
    ])
</article>
