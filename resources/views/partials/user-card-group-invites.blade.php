@props(['user', 'class' => '', 'buttons' => null])

@php($userUrl = route('user.show', $user->id))

<article class="card px-6 flex flex-col sm:flex-row justify-between w-full {{ $class }}">
    <div class="flex items-center">
        <a href="{{ $userUrl }}">
            <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }}"
                class="w-12 h-12 me-4 rounded-full object-cover">
        </a>
        <div>
            <p class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $user->name }}</a></p>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none">
                <a href="{{ $userUrl }}">{{ '@' . $user->handle }}</a>{{ ' • ' . $user->num_followers . ' followers' }}
            </p>
        </div>
    </div>
    <div class="flex gap-x-4 justify-end">
        @if($group->isUserInvited($user))
            @include('partials.text-button', [
                'text' => 'Send',
                'type' => 'secondary',
                'class' => 'w-40    invite-button hidden',
            ])
            @include('partials.text-button', [
                'text' => 'Unsend',
                'type' => 'secondary',
                'class' => 'w-40  uninvite-button ',
            ])\
        @else
            @include('partials.text-button', [
                'text' => 'Send',
                'type' => 'secondary',
                'class' => 'w-40    invite-button',
            ])
            @include('partials.text-button', [
                'text' => 'Unsend',
                'type' => 'secondary',
                'class' => 'w-40  uninvite-button hidden',
            ])
        @endif
    </div>
</article>

