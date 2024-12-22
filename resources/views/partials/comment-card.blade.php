@props(['comment'])

@php
    $authorUrl = route('user.show', $comment->author->id);
@endphp

<article class="comment-card card px-6 w-full grid grid-cols-[1fr_auto] gap-x-1 items-center content-start" data-comment-id="{{ $comment->id }}">
    <div class="flex flex-col">
        <p class="text-base/4 font-medium"><a href="{{ $authorUrl }}">{{ $comment->author->name }}</a></p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400"><a href="{{ $authorUrl }}">{{ '@' . $comment->author->handle }}</a>{{ ' • ' . $comment->timestamp->diffForHumans() }}</p>
    </div>
    <div class="flex">
        @if(Auth::check() && Auth::id() === $comment->author->id)
            <div class="dropdown">
                @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                <div class="hidden">
                    <div class= "comment-actions">
                        @include('partials.dropdown-item', ['icon' => 'pencil', 'text' => 'Edit Comment', 'class' => 'edit-comment'])
                        @include('partials.confirmation-modal', [
                            'icon' => 'trash',
                            'label' => 'Delete Comment',
                            'type' => 'dropdown',
                            'message' => 'Are you sure you want to delete this comment?',
                            'class' => 'delete-comment',
                        ])
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="mt-4 content-container max-w-full overflow-hidden">
        <p class="whitespace-pre-wrap text-pretty break-words text-ellipsis">{{str_replace("\\n", "\n", $comment->content) }}</p>
    </div>
      <div class="edit-content-container hidden">
        <form class="edit-comment-form" action="{{ route('api.comment.update', ['id' => $comment->id]) }}">
            @csrf
            <textarea name="content" class="edit-textarea">{{ str_replace("\\n", "\n", $comment->content) }}</textarea>
            @include('partials.text-button', ['text' => 'Save', 'class' => 'w-full mt-2 mb-2', 'submit' => true])
        </form>
    </div>
    <div class="flex flex-col items-center">
        @include('partials.like-button', ['model' => $comment])
        <p class="font-medium">{{ $comment->likes }}</p>
    </div>
</article>