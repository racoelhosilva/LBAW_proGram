@props(['name', 'label', 'options', 'multi' => false, 'selected' => [], 'form'])

@php
    $selectedNames = array_map(function ($option) {
        return $option['name'];
    }, array_filter($options, function ($option) use ($selected, $multi) {
        return $multi ? in_array($option['value'], $selected) : $option['value'] == $selected;
    }));
@endphp

<div class="select closed" tabindex="0" data-form="{{ $form }}">
    <button type="button">
        <span class="select-label">{{ $label }}:</span>
        <span class="selected-options">{{ implode(", ", $selectedNames) }}</span>

        <span>@include('partials.icon', ['name' => 'chevron-down'])</span>
        <span>@include('partials.icon', ['name' => 'chevron-up'])</span>
    </button>
    <div>
        @foreach ($options as $option)
            @include('partials.select-option', [
                'name' => $name,
                'option' => $option,
                'form' => $form,
                '$multi' => $multi,
                'selected' => $multi ? in_array($option['value'], $selected) : $option['value'] == $selected,
            ])
        @endforeach
    </div>
</div>