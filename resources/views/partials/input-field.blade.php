@props(['name', 'placeholder', 'value'])

<input type="text" placeholder="{{ $placeholder }}" @if (isset($value)) value="{{ $value }}" @endif class="h-12 px-5 rounded-full font-medium bg-white dark:bg-slate-700 text-gray-500 dark:text-white placeholder-gray-500 dark:placeholder-white border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none">