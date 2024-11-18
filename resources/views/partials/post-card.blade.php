@php($authorUrl = url('user/' . $post->author->id))
@php($postUrl = url('post/' . $post->id))

<article class="card w-full">
    <div class="flex items-center">
        <a href="{{ $authorUrl }}">
            <img src="{{ url('img/placeholder.png') }}" alt="{{ $post->author->name }}" class="w-12 h-12 me-3 rounded-full object-cover">
        </a>
        <div>
            <a href="{{ $authorUrl }}"><p class="text-base/4 font-medium">{{ $post->author->name }}</p></a>
            <a href="{{ $authorUrl }}"><p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400">{{ '@' . $user->handle . ' • ' . $post->creation_timestamp->diffForHumans() }}</p></a>
        </div>
    </div>
    <div class="pt-4">
        <a href="{{ $postUrl }}"><h1 class="font-bold text-xl">{{ $post->title }}</h1></a>
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $post->text) }}</p>
    </div>
</article>