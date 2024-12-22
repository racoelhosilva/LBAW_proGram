@props(['post'])

@php
    $authorUrl = route('user.show', $post->author->id);
    $postUrl = route('post.show', $post->id);
@endphp

<article
    class="post-card card px-6 w-full grid grid-cols-[auto_1fr_auto] items-center content-start
    {{ $post->is_announcement ? 'shadow-[inset_0_0_4px_rgba(38,102,214,0.8)] dark:shadow-[inset_0_0_4px_rgba(38,102,214,0.8)]' : '' }}"
    data-post-id="{{ $post->id }}">
    @if (!$post->author->is_deleted)
        <a href="{{ $authorUrl }}">
            <img src="{{ $post->author->getProfilePicture() }}" alt="{{ $post->author->name }}"
                class="w-12 h-12 rounded-full object-cover">
        </a>
    @else
        <img src="{{ $post->author->getProfilePicture() }}" alt="Deleted Account"
            class="w-12 h-12 rounded-full object-cover">
    @endif

    <div class="ms-4 flex flex-col">
        <p class="text-base/4 font-medium select-none">
            @if (!$post->author->is_deleted)
                <a href="{{ $authorUrl }}">
                    {{ $post->author->name }}
                </a>
            @else
                [deleted]
            @endif
        </p>
        <p class="text-xs/3 pt-1 font-medium text-gray-500 dark:text-gray-400 select-none">
            @if (!$post->author->is_deleted)
                <a href="{{ $authorUrl }}">
                    {{ '@' . $post->author->handle }}
                </a>
                {{ ' • ' . $post->creation_timestamp->diffForHumans() }}
            @else
                {{ $post->creation_timestamp->diffForHumans() }}
            @endif
        </p>
    </div>

    <div class="ms-4 -me-3 flex">
        <div class="dropdown">
            @include('partials.icon-button', [
                'iconName' => 'ellipsis',
                'label' => 'Options',
                'type' => 'transparent',
            ])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', [
                        'icon' => 'message-circle',
                        'text' => 'See Post',
                        'anchorUrl' => route('post.show', $post->id),
                    ])
                    @can('update', $post)
                        @include('partials.dropdown-item', [
                            'icon' => 'pencil',
                            'text' => 'Edit Post',
                            'anchorUrl' => route('post.edit', $post->id),
                        ])
                    @endcan
                    @can('forceDelete', $post)
                        @include('partials.confirmation-modal', [
                            'icon' => 'trash',
                            'label' => 'Delete Post',
                            'message' => 'Are you sure you want to delete this post? It\'s data will be lost FOREVER (i.e. a very long time)!',
                            'action' => route('post.destroy', $post->id),
                            'type' => 'dropdown',
                            'method' => 'DELETE',
                        ])
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 col-span-3 flex flex-col gap-2">
        <h2 class="font-bold text-xl break-words"><a href="{{ $postUrl }}">{{ $post->title }}</a></h2>
        <div class="whitespace-pre-wrap text-pretty break-words post-content">{!! $post->text !!}</div>
    </div>

    <div class="-ms-3 col-span-3 grid grid-cols-[auto_auto_1fr_50%] items-end">
        <div class="flex items-center">
            @include('partials.like-button', ['model' => $post])
            <p class="me-3 font-medium select-none">{{ $post->likes }}</p>
        </div>
        <div class="flex items-center">
            @include('partials.icon-button', [
                'iconName' => 'message-square-text',
                'label' => 'Comments',
                'type' => 'transparent',
                'anchorUrl' => $postUrl,
            ])
            <p class="font-medium select-none">{{ $post->comments }}</p>
        </div>
        <div class="select-none text-end break-keep col-start-4">
            @foreach ($post->tags as $tag)
                @include('partials.tag', ['tag' => $tag, 'small' => true])
            @endforeach
        </div>
    </div>
</article>
