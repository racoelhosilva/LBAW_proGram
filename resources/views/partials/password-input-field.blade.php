@props(['type' => 'text', 'name', 'label', 'value', 'placeholder', 'required' => false])

<div>
    <label for="{{ $name }}" class="font-medium">{{ $label . ($required ? '*' : '') }}</label>
    <div class="relative">
        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}"
            placeholder="{{ $placeholder }}" value="{{ $value ?? '' }}" {{ $required ? 'required' : '' }}
            class="password-input h-12 w-full px-5 rounded-lg font-medium disabled:text-gray-500 disabled:dark:text-gray-400 bg-white dark:bg-slate-700 text-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none">
        <button type="button"
            class="toggle-password-visibility absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
            <span class="eye-closed">
                @include('partials.icon', [
                    'name' => 'eye-closed',
                ])
            </span>
            <span class="eye hidden">
                @include('partials.icon', [
                    'name' => 'eye',
                ])
            </span>
        </button>
    </div>
    @if ($errors->has($name))
        <p class="text-red-600 dark:text-red-400 text-sm font-medium">{{ $errors->first($name) }}</p>
    @endif
</div>
