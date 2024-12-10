@props(['type', 'results'])

@extends('layouts.app')
@section('title') {{'Search | Program'}} @endsection
@section('content')
    <main id="search-page" class="px-8 grid grid-cols-4 gap-6">
        <section class="card h-min">
            <h1 class="pb-4 text-xl font-semibold">Search Options</h1>
            <div class="border-t border-slate-300 dark:border-slate-600 grid justify-stretch">
                @include('partials.search-type-button', ['optionType' => 'posts', 'searchType' => $type, 'icon' => 'message-circle', 'text' => 'Posts'])
                @include('partials.search-type-button', ['optionType' => 'users', 'searchType' => $type, 'icon' => 'user-round', 'text' => 'Users'])
            </div>
        </section>
        
        <section id="results" class="flex flex-col col-span-3 gap-3">
            @switch($type)
                @case('posts')
                    @if (count($results) > 0)
                        <h1 class="text-xl font-semibold">Found {{ count($results) . (count($results) === 1 ? ' post' : ' posts') }}</h1>
                        @foreach ($results as $post)
                            @include('partials.post-card', ['post' => $post])
                        @endforeach
                        @break
                    @else
                        <h1 class="text-xl font-semibold">No posts found</h1>
                    @endif
                    @break
                @case('users')
                    @if (count($results) > 0)
                        <h1 class="text-xl font-semibold">Found {{ count($results) . (count($results) === 1 ? ' user' : ' users') }}</h1>
                        @foreach ($results as $user)
                            @include('partials.user-card', ['user' => $user])
                        @endforeach
                        @break
                    @else
                        <h1 class="text-xl font-semibold">No users found</h1>
                    @endif
                    @break
            @endswitch
        </section>
    </main>
@endsection