@props(['name', 'label', 'value' => '', 'placeholder' => '', 'required' => false])

<div>
    <label for="{{ $name }}" class="font-medium" @required($required)>{{ $label }}</label>
    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
    <div>
        <div id="quill-editor"></div>  {{-- Double div to prevent Quill editor container overflow. --}}
    </div>
</div>