<form action="{{ url('/') }}" method="get" class="h-12 px-4 rounded-full bg-white dark:bg-slate-700 text-gray-500 dark:text-white border border-gray-300 dark:border-slate-600 focus-within:border-gray-500 focus-within:dark:border-white font-medium inline-flex shadow">
    <button type="submit" aria-label="Search" class="pe-2">
        @include('partials.icon', ['name' => 'search'])
    </button>
    <input type="search" name="query" placeholder="Search..." value="{{ request('query') }}" class="bg-transparent placeholder-gray-500 dark:placeholder-white outline-none">
</form>