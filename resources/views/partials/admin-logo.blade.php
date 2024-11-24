@props(['size' => 'medium'])

@php
    $heightClass = match($size) {
        'medium' => 'h-12',
        'large' => 'h-16',
    };
@endphp

<a href="{{ route('admin.dashboard') }}">
    <img src="{{ url('svg/logo-admin-black.svg') }}" alt="Logo" class="{{ $heightClass }} dark:hidden">
    <img src="{{ url('svg/logo-admin-white.svg') }}" alt="Logo" class="{{ $heightClass }} hidden dark:block">
</a>