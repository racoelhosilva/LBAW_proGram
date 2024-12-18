@props(['label'])

<div class="responsive-dropdown closed w-full h-auto col-span-4 lg:col-span-1">
    <button class="h-12 px-4 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors flex lg:hidden gap-1 items-center">
        {{ $label }}
        @include('partials.icon', ['name' => 'chevron-down'])
        @include('partials.icon', ['name' => 'chevron-up'])
    </button>
    <div class="max-lg:py-4 flex flex-col gap-4">
        @yield('dropdownContent')
    </div>
</div>
