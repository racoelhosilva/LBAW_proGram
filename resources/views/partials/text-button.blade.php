@props(['text', 'id', 'transparent', 'type' => 'primary', 'anchorUrl', 'submit' => false])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
    };  
@endphp

@if (!isset($anchorUrl))
    <button {{ isset($id) ? "id=$id" : "" }} class="px-4 py-3 {{ $buttonClass }} font-medium">
        {{ $text }}
    </button>
@else
    <a href={{ $anchorUrl }} {{ isset($id) ? "id=$id" : "" }} class="px-4 py-3 {{ $buttonClass }} font-medium">
        {{ $text }}
    </a>
@endif