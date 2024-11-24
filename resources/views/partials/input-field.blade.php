@props(['type' => 'text', 'name', 'label', 'value', 'placeholder', 'required' => false])

<div>
    <label for="{{ $name }}" class="font-medium">{{ $label }}</label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ isset($value) ? $value : '' }}" {{ $required ? 'required' : '' }} class="h-12 w-full px-5 rounded-lg font-medium bg-white dark:bg-slate-700 text-gray-600 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none">
    @if ($errors->has($name))
        <p class="text-red-600 dark:text-red-400 text-sm font-medium">{{ $errors->first($name) }}</p>
    @endif
</div>