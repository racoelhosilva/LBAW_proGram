@props(['text', 'id', 'transparent', 'type' => 'primary', 'anchorUrl', 'submit' => false, 'class' => '', 'form'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'danger' => 'danger-btn',
    };  
@endphp

@isset($anchorUrl)
    <a href="{{ $anchorUrl }}" {{ isset($id) ? "id=$id" : "" }}  {{ isset($form) ? "form=$form" : "" }} class="px-4 py-3 {{ $buttonClass }} text-center font-medium {{ $class }}">
        {{ $text }}
    </a>
@else
    <button type="{{ $submit ? 'submit' : 'button'}}" {{ isset($id) ? "id=$id" : "" }} {{ isset($form) ? "form=$form" : "" }} class="px-4 py-3 {{ $buttonClass }} text-center font-medium {{ $class }}">
        {{ $text }}
    </button>
@endif