@props(['name', 'label', 'options', 'selected' => [], 'form'])

<div class="relative">
    <button type="button" class="p-3" aria-label="Open multiselect">
        {{ $label }}
        @include('partials.icon', ['name' => 'chevron-down'])
    </button>
    <div class="card absolute flex flex-col">
        @foreach ($options as $option)
            @include('partials.multiselect-option', [
                'name' => $name,
                'option' => $option,
                'form' => $form,
                'selected' => in_array($option->id, $selected),
            ])
        @endforeach
    </div>
</div>