@php($userUrl = url('user/' . $user->id))

<article class="card flex items-center">
    {{-- TODO: Use real profile picture --}}
    {{-- TODO: Find better place for placeholder --}}
    <img src="{{ url('img/placeholder.png') }}" alt="{{ $user->name }}" class="w-12 h-12 me-4 rounded-full object-cover">
    <div>
        <p class="text-base/4 font-medium"><a href="{{ $userUrl }}">{{ $user->name }}</a></p>
        <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400"><a href="{{ $userUrl }}">{{ '@' . $user->handle }}</a></p>
    </div>
</article>