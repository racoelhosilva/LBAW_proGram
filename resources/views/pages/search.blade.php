@extends('layouts.app')
@section('title') {{'Search | Program'}} @endsection
@section('content')
    <main id="search-page" class="px-8 grid grid-cols-4 gap-6">
        <section class="card h-min">
            <h1 class="pb-2 text-xl font-semibold">Search Options</h1>
            <div class="border-t border-slate-300 dark:border-slate-600 grid justify-stretch">
                <button id="see-posts-button" class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors font-bold flex">
                    @include('partials.icon', ['name' => 'message-circle'])
                    <span class="ps-2">Posts</span>
                </button>
                <button id="see-users-button" class="px-4 py-2 hover:bg-slate-300 hover:dark:bg-slate-600 transition-colors font-medium flex">
                    @include('partials.icon', ['name' => 'user-round'])
                    <span class="ps-2">Users</span>
                </button>
            </div>
        </section>
        <section id="post-results" class="flex flex-col col-span-3 gap-3">
            @if (count($posts) > 0)
                <h1 class="text-xl font-semibold">Found {{ count($posts) . (count($posts) === 1 ? ' post' : ' posts') }}</h1>
                @foreach ($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            @else
                <h1 class="text-xl font-semibold">No posts found</h1>
            @endif
        </section>
        <section id="user-results" class="hidden flex-col col-span-3 gap-3">
            @if (count($users) > 0)
                <h1 class="text-xl font-semibold">Found {{ count($users) . (count($users) === 1 ? ' user' : ' users') }}</h1>
                @foreach ($users as $user)
                    @include('partials.user-card', ['user' => $user])
                @endforeach
            @else
                <h1 class="text-xl font-semibold">No users found</h1>
            @endif
        </section>
    </main>
@endsection