@props(['tags', 'label', 'selected', 'form'])

@php
    $tagOptions = array_map(function ($tag) {
        return ['name' => $tag->name, 'value' => $tag->id];
    }, $tags->all());
    uasort($tagOptions, function ($option) {
        return $option['name'];
    });
@endphp

@include('partials.select', [
    'name' => 'tags[]',
    'label' => $label,
    'options' => $tagOptions,
    'multi' => true,
    'selected' => $selected,
    'form' => $form
])