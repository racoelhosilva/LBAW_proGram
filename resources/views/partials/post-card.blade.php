@props(['post'])

@php
    $authorUrl = url('user/' . $post->author->id);
    $postUrl = url('post/' . $post->id);
@endphp

<article class="card px-6 w-full grid grid-cols-[auto_1fr_auto] items-center content-start">
    <a href="{{ $authorUrl }}">
        <img src="{{ url('img/placeholder.png') }}" alt="{{ $post->author->name }}" class="w-12 h-12 rounded-full object-cover">
    </a>
    <div class="ms-4 flex flex-col">
        <p class="text-base/4 font-medium select-none"><a href="{{ $authorUrl }}">{{ $post->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400 select-none"><a href="{{ $authorUrl }}">{{ '@' . $post->author->handle }}</a>{{ ' • ' . $post->creation_timestamp->diffForHumans() }}</p>
    </div>
    <div class="ms-4 -me-3 flex">
        <div class="dropdown">
            @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', ['icon' => 'message-circle', 'text' => 'See Post', 'anchorUrl' => url('post/' . $post->id)])
                    @if ($post->author->id === auth()->id())
                        @include('partials.dropdown-item', ['icon' => 'pencil', 'text' => 'Edit Post', 'anchorUrl' => route('post.edit', $post->id)])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 col-span-3">
        <h1 class="font-bold text-xl"><a href="{{ $postUrl }}">{{ $post->title }}</a></h1>
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $post->text) }}</p>
    </div>
    <div class="-ms-3 col-span-3 grid grid-cols-[auto_auto_auto_1fr_50%] items-center">
        @include('partials.icon-button', ['iconName' => 'heart', 'label' => 'Like', 'type' => 'transparent'])
        <p class="me-3 font-medium select-none">{{ $post->likes }}</p>
        @include('partials.icon-button', ['iconName' => 'message-square-text', 'label' => 'Comments', 'type' => 'transparent', 'anchorUrl' => $postUrl])
        <p class="font-medium select-none">{{ $post->comments }}</p>
        <div class="select-none text-end break-keep">
            @foreach($post->tags as $tag)
                {{-- TODO: Add tag search results link --}}
                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ '#' . $tag->name }}</span>
            @endforeach
        </div>
    </div>
</article>