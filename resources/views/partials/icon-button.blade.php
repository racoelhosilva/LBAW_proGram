@props(['iconName', 'id', 'label', 'type' => 'primary'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  
@endphp

<button {{ isset($id) ? "id=$id" : "" }} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }}">
    @include('partials.icon', ['name' => $iconName])
</button>