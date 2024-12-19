@props(['optionType', 'icon', 'text'])

@include('partials.menu-anchor', [
    'anchorUrl' => route('search', ['search_type' => $optionType, 'query' => request('query')]),
    'icon' => $icon,
    'text' => $text,
    'selected' => request('search_type') == $optionType,
])