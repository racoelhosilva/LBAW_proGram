<article class="card flex items-center">
    {{-- TODO: Use real profile picture --}}
    {{-- TODO: Find better place for placeholder --}}
    <img src="{{ url('img/placeholder.png') }}" alt="{{ $user->name }}" class="w-12 h-12 me-3 rounded-full object-cover">
    <div>
        <p class="text-base/4 font-medium">{{ $user->name }}</p>
        <a href="{{ url('user/' . $user->id) }}" class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400">{{ '@' . $user->handle }}</a>
    </div>
</article>