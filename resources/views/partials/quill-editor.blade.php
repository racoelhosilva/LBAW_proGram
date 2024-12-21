@props(['name', 'label', 'value' => '', 'placeholder' => '', 'required' => false])

<div>
    <label class="font-medium" @required($required)>
        <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        {{ $label }}
    </label>
    <div>
        <div id="quill-editor"></div>  {{-- Double div to prevent Quill editor container overflow. --}}
    </div>
</div>