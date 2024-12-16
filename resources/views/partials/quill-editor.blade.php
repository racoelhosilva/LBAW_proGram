@props(['name', 'label', 'value' => '', 'required' => false])

<div>
    <label for="{{ $name }}" class="font-medium" @required($required)>{{ $label }}</label>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    <div id="quill-editor"></div>
</div>