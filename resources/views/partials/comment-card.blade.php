@php
    $authorUrl = route('user.show', $comment->author->id);
@endphp

<article class="comment-card card px-6 w-full grid grid-cols-[1fr_auto] gap-x-1 items-center content-start" data-comment-id="{{ $comment->id }}">
    <div class="flex flex-col col-span-2">
        <p class="text-base/4 font-medium"><a href="{{ $authorUrl }}">{{ $comment->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400"><a href="{{ $authorUrl }}">{{ '@' . $comment->author->handle }}</a>{{ ' • ' . $comment->timestamp->diffForHumans() }}</p>
    </div>
    <div class="mt-4">
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $comment->content) }}</p>
    </div>
    <div class="flex flex-col items-center">
        @if(Auth::check() && Auth::id() !== $comment->author->id)
            <button aria-label="Like" class="p-3 .btn-transparent like-button {{ $comment->likedBy(Auth::user()) ? 'liked' : '' }}">
                @include('partials.icon', ['name' => 'heart'])
                @include('partials.icon', ['name' => 'filled-heart'])
            </button>
        @else
            <button aria-label="Like" class="p-3 .btn-transparent like-button" disabled>
                @include('partials.icon', ['name' => 'heart'])
            </button>
        @endif
        <p class="font-medium">{{ $comment->likes }}</p>
    </div>
</article>