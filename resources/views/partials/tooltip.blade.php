@props(['text'])

<div class="tooltip px-4 py-2 rounded-md bg-gray-900 absolute top-3/4 left-1/2 z-10 mt-3 -translate-x-1/2 text-white opacity-0 group-hover:opacity-100 group-hover:top-full transition-all group-hover:delay-1000 pointer-events-none">
    <div class="w-4 h-4 bg-gray-900 absolute -top-1 left-1/2 -translate-x-1/2 rotate-45"></div>
    {{ $text }}
</div>