<article class="card w-full">
    <div class="flex items-center">
        <img src="{{ url('img/placeholder.png') }}" alt="{{ $post->author->name }}" class="w-12 h-12 me-3 rounded-full object-cover">
        <div>
            <p class="text-base/4 font-medium">{{ $post->author->name }}</p>
            <a href="{{ url('user/' . $post->author->id) }}" class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400">{{ '@' . $user->handle . ' • ' . $post->creation_timestamp->diffForHumans() }}</a>
        </div>
    </div>
    <div class="pt-4">
        <a href="{{ url('post/' . $post->id) }}" class="font-bold text-lg">{{ $post->title }}</a>
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $post->text) }}</p>
    </div>
</article>