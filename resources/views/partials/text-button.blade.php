@props(['text', 'id', 'transparent', 'type' => 'primary', 'anchorUrl'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
    };  
@endphp

@if (!isset($anchorUrl))
    <button @if (isset($id)) id={{ $id }} @endif class="px-4 py-3 {{ $buttonClass }} font-medium">
        {{ $text }}
    </button>
@else
    <a href={{ $anchorUrl }} @if (isset($id)) id={{ $id }} @endif class="px-4 py-3 {{ $buttonClass }} font-medium">
        {{ $text }}
    </a>
@endif