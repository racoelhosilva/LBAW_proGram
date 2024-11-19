@props(['text', 'transparent', 'type' => 'primary'])

@php
    $buttonColorClasses = match($type) {
        'primary' => 'text-white bg-blue-600 hover:bg-blue-500 shadow',
        'secondary' => 'text-black bg-white dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 border border-slate-300',
    };  
@endphp

<button class="px-4 py-3 rounded-full {{ $buttonColorClasses }} transition-colors font-medium">
    {{ $text }}
</button>