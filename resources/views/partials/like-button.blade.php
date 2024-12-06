@props(['liked', 'enabled'])

<button aria-label="Like" class="p-3 transparent-btn like-button {{ $liked ? 'liked' : '' }} {{ $enabled ? 'enabled' : '' }}">
    @include('partials.icon', ['name' => 'heart'])
    @include('partials.icon', ['name' => 'filled-heart'])
</button>