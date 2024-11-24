@props(['id', 'icon', 'text', 'anchorUrl'])

@if (isset($anchorUrl))
    <a href="{{ $anchorUrl }}" {{ isset($id) ? "id=$id" : '' }} class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors font-medium flex">
        @include('partials.icon', ['name' => $icon])
        <span class="ps-2">{{ $text }}</span>
    </a>
@else
    <button {{ isset($id) ? "id=$id" : '' }} class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors font-medium">
        @include('partials.icon', ['name' => $icon])
        <span class="ps-2">{{ $text }}</span>
    </button>
@endif
