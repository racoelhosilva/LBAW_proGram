@props(['results', 'numResults', 'tags'])

@extends('layouts.app')
@section('title')
    {{'Search | Program'}}
@endsection
@section('content')
    <main id="search-page" class="px-8 grid grid-cols-4 grid-rows gap-6">
        @include('partials.search-menu', ['tags' => $tags])

        <section id="search-results" class="flex flex-col col-span-4 lg:col-span-3 gap-3">
            @switch(request('search_type'))
                @case('posts')
                    @if ($numResults > 0)
                        <h1 class="text-xl font-semibold">
                            Found {{ $numResults . ($numResults === 1 ? ' post' : ' posts') }}</h1>
                        <div id="search-posts" class="space-y-3">
                            @include('partials.post-list', ['posts' => $results])
                        </div>
                    @else
                        <h1 class="text-xl font-semibold">No posts found</h1>
                    @endif
                    @break
                @case('users')
                    @if ($numResults > 0)
                        <h1 class="text-xl font-semibold">
                            Found {{ $numResults . ($numResults === 1 ? ' user' : ' users') }}</h1>
                        <div id="search-users" class="space-y-3">
                            @include('partials.user-list', ['users' => $results])
                        </div>
                    @else
                        <h1 class="text-xl font-semibold">No users found</h1>
                    @endif
                    @break
                @case('groups')
                    @if ($numResults > 0)
                        <h1 class="text-xl font-semibold">
                            Found {{ $numResults . ($numResults === 1 ? ' group' : ' groups') }}</h1>
                        <div id="search-groups" class="space-y-3">
                            @include('partials.group-list', ['groups' => $results])
                        </div>
                    @else
                        <h1 class="text-xl font-semibold">No groups found</h1>
                    @endif
                    @break
            @endswitch
            <div class="flex flex-col items-center">
                @include('partials.loading-spinner')
            </div>
        </section>
    </main>
@endsection