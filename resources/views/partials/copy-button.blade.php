@props(['value'])

<button class="copy-button secondary-btn px-4 py-3 flex gap-2">
    <span>{{ $value }}</span>
    |
    @include('partials.icon', ['name' => 'copy'])
</button>
