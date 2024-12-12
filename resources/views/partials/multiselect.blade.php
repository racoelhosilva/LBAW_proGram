@props(['name', 'label', 'options', 'selected' => [], 'form'])

<div class="multiselect closed">
    <button>
        {{ $label }}
        <div>@include('partials.icon', ['name' => 'chevron-down'])</div>
    </button>
    <div>
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