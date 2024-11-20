@props(['text', 'transparent', 'type' => 'primary'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
    };  
@endphp

<button class="px-4 py-3 {{ $buttonClass }} font-medium">
    {{ $text }}
</button>