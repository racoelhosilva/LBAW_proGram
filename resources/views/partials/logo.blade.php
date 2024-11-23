@props(['size' => 'medium'])

@php
    $heightClass = match($size) {
        'medium' => 'h-12',
        'large' => 'h-16',
    };
@endphp

<a href="{{ route('home') }}">
    <img src="{{ url('svg/logo-large-black.svg') }}" alt="Logo" class="{{ $heightClass }} dark:hidden">
    <img src="{{ url('svg/logo-large-white.svg') }}" alt="Logo" class="{{ $heightClass }} hidden dark:block">
</a>