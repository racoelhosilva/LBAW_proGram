@props(['text', 'left' => true])

<article class="help-icon group relative">
    <div class="rounded-md">
        @include('partials.icon', ['name' => 'info'])
    </div>
    <div class="w-80 px-4 py-2 rounded-md bg-gray-900 shadow-lg absolute top-8 {{ $left ? 'right-full translate-x-full' : 'left-full -translate-x-full' }} z-30 mt-3 text-white opacity-0 group-hover:opacity-100 group-hover:top-6 transition-all pointer-events-none">
        {{ $text }}
    </div>
</article>