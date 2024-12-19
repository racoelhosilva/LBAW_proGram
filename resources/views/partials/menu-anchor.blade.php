@props(['anchorUrl', 'icon', 'text', 'selected'])

<a href="{{ $anchorUrl }}" class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors {{ $selected ? 'font-bold' : 'font-medium' }} flex">
    @include('partials.icon', ['name' => $icon])
    <span class="ps-2">{{ $text }}</span>
</a>