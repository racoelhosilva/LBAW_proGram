@props(['iconName', 'id', 'class' => '', 'label', 'type' => 'primary', 'anchorUrl'])

@php
    $buttonClass = match($type) {
        'primary' => 'primary-btn',
        'secondary' => 'secondary-btn',
        'transparent' => 'transparent-btn',
    };  
@endphp

<{{ isset($anchorUrl) ? 'a href=' . $anchorUrl : 'button'}} {{ isset($id) ? 'id=' . $id : '' }} aria-label="{{ $label }}" class="p-3 {{ $buttonClass }} {{ $class }}">
    @include('partials.icon', ['name' => $iconName])
</{{ isset($anchorUrl) ? 'a' : 'button'}}>