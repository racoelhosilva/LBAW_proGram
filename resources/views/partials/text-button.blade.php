@props(['text', 'id', 'transparent', 'type' => 'primary', 'anchorUrl', 'submit' => false, 'class' => ''])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
    };  
@endphp

@if (!isset($anchorUrl))
    <button {{ isset($id) ? "id=$id" : "" }} class="px-4 py-3 {{ $buttonClass }} text-center font-medium {{ $class }}">
        {{ $text }}
    </button>
@else
    <a href={{ $anchorUrl }} {{ isset($id) ? "id=$id" : "" }} class="px-4 py-3 {{ $buttonClass }} text-center font-medium {{ $class }}">
        {{ $text }}
    </a>
@endif