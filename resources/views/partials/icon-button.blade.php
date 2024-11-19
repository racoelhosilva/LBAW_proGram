@props(['iconName', 'label', 'type' => 'primary'])

@php
    $buttonColorClasses = match($type) {
        'primary' => 'text-white bg-blue-600 hover:bg-blue-500 shadow',
        'secondary' => 'text-black bg-white dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 border border-slate-300',
        'transparent' => 'text-black bg-white dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700',
    };  
@endphp

<button aria-label="{{ $label }}" class="p-3 rounded-full {{ $buttonColorClasses }} transition-colors">
    @include('partials.icon', ['name' => $iconName])
</button>