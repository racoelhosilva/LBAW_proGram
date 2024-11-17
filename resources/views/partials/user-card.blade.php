<article class="bg-slate-800 border border-slate-600 p-3 rounded-xl shadow">
    <div>
        <p class="text-base/4 font-medium">{{ $user->name }}</p>
        <a href="{{ url('user/' . $user->id) }}" class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400">{{ '@' . $user->handle }}</a>
    </div>
</article>