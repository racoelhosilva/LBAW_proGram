@php($authorUrl = url('user/' . $post->author->id))
@php($postUrl = url('post/' . $post->id))

<article class="card w-full grid grid-cols-[auto_1fr_auto] gap-4 items-center content-start">
    <a href="{{ $authorUrl }}">
        <img src="{{ url('img/placeholder.png') }}" alt="{{ $post->author->name }}" class="w-12 h-12 rounded-full object-cover">
    </a>
    <div class="flex flex-col">
        <p class="text-base/4 font-medium"><a href="{{ $authorUrl }}">{{ $post->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400"><a href="{{ $authorUrl }}">{{ '@' . $user->handle }}</a>{{ ' • ' . $post->creation_timestamp->diffForHumans() }}</p>
    </div>
    <div>
        @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
    </div>
    <div class="col-span-3">
        <h1 class="font-bold text-xl"><a href="{{ $postUrl }}">{{ $post->title }}</a></h1>
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $post->text) }}</p>
    </div>
</article>