@props(['optionType', 'searchType', 'icon', 'text'])

<a href="{{ route('search', ['search_type' => $optionType, 'query' => request('query')]) }}" id="see-posts-button" class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors {{ $searchType === $optionType ? 'font-bold' : 'font-medium' }} flex">
    @include('partials.icon', ['name' => $icon])
    <span class="ps-2">{{ $text }}</span>
</a>