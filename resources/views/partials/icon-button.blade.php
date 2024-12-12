@props(['iconName', 'id', 'label', 'type' => 'primary', 'anchorUrl', 'submit' => false, 'class' => '', 'dataAttributes' => []])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  

    $dataAttributesString = collect($dataAttributes)
        ->map(fn($value, $key) => "data-$key=$value")
        ->join(' ');
@endphp

@if (!isset($anchorUrl))
    <button {{ isset($id) ? "id=$id" : "" }} {{ $submit ? 'type="submit"' : ''}} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }} {{ $class }}" {{ $dataAttributesString }}>
        @include('partials.icon', ['name' => $iconName])
    </button>
@else
    <a href={{ $anchorUrl }} {{ isset($id) ? "id=$id" : "" }} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }} {{ $class }}" {{ $dataAttributesString }}>
        @include('partials.icon', ['name' => $iconName])
    </a>
@endif