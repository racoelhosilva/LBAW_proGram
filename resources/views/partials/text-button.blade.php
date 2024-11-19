@props(['text', 'id', 'class' => '', 'transparent', 'type' => 'primary', 'anchorUrl'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
    };  
@endphp

<{{ isset($anchorUrl) ? 'a href=' . $anchorUrl : 'button'}} {{ isset($id) ? 'id=' . $id : ''}} class="px-4 py-3 {{ $buttonClass }} font-medium {{ $class }}">
    {{ $text }}
</{{ isset($anchorUrl) ? 'a' : 'button'}}>