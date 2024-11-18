@props(['text', 'transparent', 'type' => 'primary'])

@php
    $buttonColorClasses = match($type) {
        'primary' => 'bg-blue-600 hover:bg-blue-500',
        'secondary' => 'bg-slate-700 hover:bg-slate-600',
    };  
@endphp

<button class="px-4 py-3 rounded-full {{ $buttonColorClasses }} transition-colors font-medium shadow">
    {{ $text }}
</button>