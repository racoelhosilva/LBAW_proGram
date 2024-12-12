@props(['name', 'option', 'form', 'selected' => false])

<label>
    <input type="checkbox" name="{{ $name }}" value="{{ $option->id }}" form="{{ $form }}" @checked($selected)>
    {{ $option->name }}
</label>