@props(['user', 'reverse' => false])

@php($userUrl = route('user.show', $user->id))

<article class="card px-6 grid grid-cols-[auto_1fr_auto] gap-2 items-center">
    <a href="{{ $userUrl }}">
        <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }}"
            class="w-12 h-12 rounded-full object-cover">
    </a>
    <div class="row-start-2 col-span-3 sm:row-start-1 sm:col-span-1 sm:col-start-2">
        <p class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $user->name }}</a></p>
        <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none"><a
                href="{{ $userUrl }}">{{ '@' . $user->handle }}</a>{{ ' • ' . $user->num_followers . ' followers' }}
        </p>
    </div>

    @if(auth()->check() && auth()->id() !== $user->id)
        <div class="flex gap-x-4 items-center col-start-3">
            <button aria-label="Accept" class="p-3 secondary-btn accept-request-button" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'accept'])
            </button>
            <button aria-label="Reject" class="p-3 secondary-btn reject-request-button" data-user-id="{{ $user->id }}">
                @include('partials.icon', ['name' => 'remove'])
            </button>
        </div>      
    @endif

</article>
