@props(['class' => '', 'routeName' => 'search', 'routeParams' => []])


<form id="search-field" action="{{ route($routeName, $routeParams) }}" method="get"
    class="h-12 w-full  px-4 rounded-full bg-white dark:bg-slate-700 text-gray-500 dark:text-white border border-slate-300 dark:border-slate-600 focus-within:border-blue-600 font-medium inline-flex row-start-2 col-span-4 lg:row-start-1 lg:col-start-2 lg:col-span-1 {{ $class }}">
    <input type="hidden" name="search_type" value="{{ request('search_type') ?? 'posts' }}">
    <button type="submit" aria-label="Search" class="me-2">
        @include('partials.icon', ['name' => 'search'])
    </button>
    <input type="search" name="query" placeholder="Search..." value="{{ request('query') }}"
        class="w-full bg-transparent placeholder-gray-500 dark:placeholder-white outline-none">
</form>
