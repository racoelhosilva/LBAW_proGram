@props(['user', 'class' => ''])

@php($userUrl = route('user.show', $user->id))

<article class="card px-6 flex flex-col sm:flex-row justify-between w-full {{ $class }}">
    <div class="flex items-center gap-2">
        @if (!$user->is_deleted)
            <a href="{{ $userUrl }}">
                <img src="{{ $user->getProfilePicture() }}" alt="{{ $user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
            </a>
        @else
            <img src="{{ $user->getProfilePicture() }}" alt="Deleted Account"
                 class="w-12 h-12 rounded-full object-cover">
        @endif

        <div class="row-start-2 col-span-3 sm:row-start-1 sm:col-span-1 sm:col-start-2">
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
    </div>
    <div class="flex gap-x-4 justify-end">
        @if($group->isUserInvited($user))
            @include('partials.text-button', [
                'text' => 'Send',
                'type' => 'secondary',
                'class' => 'invite-button hidden',
            ])
            @include('partials.text-button', [
                'text' => 'Unsend',
                'type' => 'secondary',
                'class' => 'uninvite-button',
            ])
        @else
            @include('partials.text-button', [
                'text' => 'Send',
                'type' => 'secondary',
                'class' => 'invite-button',
            ])
            @include('partials.text-button', [
                'text' => 'Unsend',
                'type' => 'secondary',
                'class' => 'uninvite-button hidden',
            ])
        @endif
    </div>
</article>

