@props(['name', 'option', 'form', 'selected' => false])

<label class="multiselect-option">
    <input type="checkbox" name="{{ $name }}" value="{{ $option->id }}" form="{{ $form }}" @checked($selected)>
    <span>{{ $option->name }}</span>
</label>