@props(['text', 'transparent' => false])

<button class="px-4 py-3 rounded-full {{!$transparent ? 'bg-blue-600 hover:bg-blue-500' : 'bg-slate-700 hover:bg-slate-600'}} transition-colors font-medium shadow">
    {{ $text }}
</button>