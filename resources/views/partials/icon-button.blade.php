@props(['iconName', 'label', 'type' => 'primary'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  
@endphp

<button aria-label="{{ $label }}" class="p-3 {{ $buttonClass }}">
    @include('partials.icon', ['name' => $iconName])
</button>