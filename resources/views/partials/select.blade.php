@props(['name', 'label', 'options', 'multi' => false, 'selected' => [], 'form'])

<div class="select closed" tabindex="0">
    <button>
        {{ $label }}
        <div>@include('partials.icon', ['name' => 'chevron-down'])</div>
        <div>@include('partials.icon', ['name' => 'chevron-up'])</div>
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