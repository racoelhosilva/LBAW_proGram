@props(['model'])

@php
    if (!auth()->check()) {
        $disabledReason = 'not-logged-in';
        $liked = false;
        $enabled = false;
    } else {
        if (auth()->id() === $model->author->id)
            $disabledReason = 'is-owner';
        $liked = $model->likedBy(auth()->user());
        $enabled = auth()->user()->can('like', $model);      
    }
@endphp

<button
    aria-label="Like"
    class="p-3 transparent-btn like-button {{ $liked ? 'liked' : '' }} {{ $enabled ? 'enabled' : '' }}"
    {{ isset($disabledReason) ? "data-disabled-reason=$disabledReason" : "" }}
>
    @include('partials.icon', ['name' => 'heart'])
    @include('partials.icon', ['name' => 'filled-heart'])
</button>