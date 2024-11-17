@props(['iconName', 'label', 'transparent' => false])

<button aria-label="{{ $label }}" class="p-3 rounded-full {{ !$transparent ? 'bg-blue-600 hover:bg-blue-500' : 'bg-slate-700 hover:bg-slate-600' }} transition-colors shadow">
    @include('partials.icon', ['name' => $iconName])
</button>