@props(['text'])

<article class="help-icon group relative">
    <div class="rounded-md">
        @include('partials.icon', ['name' => 'info'])
    </div>
    <div class="w-80 px-4 py-2 rounded-md bg-gray-900 shadow-lg absolute top-3/4 left-full z-30 mt-3 -translate-x-full text-white opacity-0 group-hover:opacity-100 group-hover:top-full transition-all pointer-events-none">
        {{ $text }}
    </div>
</article>