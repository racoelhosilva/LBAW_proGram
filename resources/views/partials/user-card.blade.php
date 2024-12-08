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
            <form action="{{ route('api.follower.remove', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')
                @include('partials.icon-button', [
                    'iconName' => 'remove', 
                    'label' => 'Remove',
                    'type' => 'secondary',
                    'submit' => true,
                    ])
            </form>
        @else
            @if(auth()->user()->follows($user))
                <form action="{{ route('api.user.unfollow', $user->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    @include('partials.icon-button', [
                        'iconName' => 'unfollow', 
                        'label' => 'Unfollow',
                        'type' => 'secondary',
                        'submit' => true,
                        ])
                </form>
            @else
                <form action="{{ route('api.user.follow', $user->id) }}" method="POST">
                    @csrf
                    @include('partials.icon-button', [
                        'iconName' => 'follow', 
                        'label' => 'Follow',
                        'type' => 'secondary',
                        'submit' => true,
                        ])
                </form>
            @endif
        @endif
    @endif

</article>
