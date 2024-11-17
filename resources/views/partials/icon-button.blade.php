@props(['iconName', 'transparent' => false])

<button class="p-3 rounded-full {{!$transparent ? 'bg-blue-500 hover:bg-blue-400' : 'bg-slate-700 hover:bg-slate-600'}} transition-colors">
    @include('partials.icon', ['name' => $iconName])
</button>