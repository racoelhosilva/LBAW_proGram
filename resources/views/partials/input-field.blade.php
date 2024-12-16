@props(['type' => 'text', 'name', 'label', 'value', 'placeholder', 'required' => false])

<div class="relative group">
    <label for="{{ $name }}" class="font-medium">{{ $label }}</label>
    <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" placeholder="{{ $placeholder }}" value="{{ isset($value) ? $value : '' }}" {{ $required ? 'required' : '' }} class="h-12 w-full px-5 rounded-lg font-medium disabled:text-gray-500 disabled:dark:text-gray-400 bg-white dark:bg-slate-700 text-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none">
    @if ($errors->has($name))
        <p class="text-red-600 dark:text-red-400 text-sm font-medium">{{ $errors->first($name) }}</p>
    @endif
    <div class="tooltip px-4 py-2 rounded-md bg-gray-900 absolute top-3/4 left-1/2 z-10 mt-3 -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 group-hover:top-full transition-all group-hover:delay-[2000ms] pointer-events-none">
        Tooltip
    </div>
</div>