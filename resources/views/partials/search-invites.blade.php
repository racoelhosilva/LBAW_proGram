@props(['group'])

<form id="search-field" action="{{ route('group.invites', ['id' => $group->id]) }}" method="get"
    class="h-12 w-full px-4 rounded-full bg-white dark:bg-slate-700 text-gray-500 dark:text-white border border-slate-300 dark:border-slate-600 focus-within:border-blue-600 font-medium inline-flex">
    <button type="submit" aria-label="Search" class="me-2">
        @include('partials.icon', ['name' => 'search'])
    </button>
    <input type="search" name="invite_query" placeholder="Search for users to invite" value="{{ request('invite_query') }}"
        class="w-full bg-transparent placeholder-gray-500 dark:placeholder-white outline-none">
</form>
