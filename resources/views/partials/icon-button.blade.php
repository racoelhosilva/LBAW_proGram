@props(['iconName', 'label', 'type' => 'primary'])

@php
    $buttonColorClasses = match($type) {
        'primary' => 'bg-blue-600 hover:bg-blue-500 shadow',
        'secondary' => 'bg-slate-700 hover:bg-slate-600 shadow',
        'transparent' => 'bg-slate-800 hover:bg-slate-700',
    };  
@endphp

<button aria-label="{{ $label }}" class="p-3 rounded-full {{ $buttonColorClasses }} transition-colors">
    @include('partials.icon', ['name' => $iconName])
</button>