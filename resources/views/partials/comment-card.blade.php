@php
    $authorUrl = url('user/' . $comment->author->id);
@endphp

<article class="card px-6 w-full grid grid-cols-[1fr_auto] items-center content-start">
    <div class="ms-4 flex flex-col">
        <p class="text-base/4 font-medium"><a href="{{ $authorUrl }}">{{ $comment->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400"><a href="{{ $authorUrl }}">{{ '@' . $comment->author->handle }}</a>{{ ' • ' . $comment->timestamp->diffForHumans() }}</p>
    </div>
    <div class="ms-4 -me-3">
        @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
    </div>
    <div class="mt-4">
        <p class="whitespace-pre-wrap">{{ str_replace("\\n", "\n", $comment->content) }}</p>
    </div>
    <div class="-ms-3 flex flex-col items-center">
        @include('partials.icon-button', ['iconName' => 'heart', 'label' => 'Like', 'type' => 'transparent'])
    <p class="font-medium">{{ $comment->likes }}</p>
</div>
</article>