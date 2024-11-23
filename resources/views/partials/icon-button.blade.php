@props(['iconName', 'id', 'label', 'type' => 'primary', 'anchorUrl'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  
@endphp

@if (!isset($anchorUrl))
    <button @if (isset($id)) id={{ $id }} @endif aria-label="{{ $label }}" class="p-3 {{ $buttonClass }}">
        @include('partials.icon', ['name' => $iconName])
    </button>
@else
    <a href={{ $anchorUrl }} @if (isset($id)) id={{ $id }} @endif aria-label="{{ $label }}" class="p-3 {{ $buttonClass }}">
        @include('partials.icon', ['name' => $iconName])
    </a>
@endif