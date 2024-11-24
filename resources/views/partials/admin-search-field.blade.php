@props(['route'])

<form action="{{ route($route) }}" method="get" class="h-12 px-4 rounded-full bg-white dark:bg-slate-700 text-gray-500 dark:text-white border border-slate-300 dark:border-slate-600 focus-within:border-blue-600 font-medium inline-flex">
    <button type="submit" aria-label="Search" class="me-2">
        @include('partials.icon', ['name' => 'search'])
    </button>
    <input type="search" name="query" placeholder="Search..." value="{{ request('query') }}" class="bg-transparent placeholder-gray-500 dark:placeholder-white outline-none">
</form>