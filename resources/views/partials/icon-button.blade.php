@props(['iconName', 'id', 'label', 'type' => 'primary', 'anchorUrl', 'submit' => false, 'class' => ''])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  
@endphp

@isset($anchorUrl)
    <a href="{{ $anchorUrl }}" {{ isset($id) ? "id=$id" : "" }} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }} {{ $class }}">
        @include('partials.icon', ['name' => $iconName])
    </a>
@else
    <button {{ isset($id) ? "id=$id" : "" }} {{ $submit ? 'type="submit"' : ''}} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }} {{ $class }}">
        @include('partials.icon', ['name' => $iconName])
    </button>
@endif