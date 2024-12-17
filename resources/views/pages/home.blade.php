@extends('layouts.app')
@section('title') {{'Home | ProGram'}} @endsection
@section('content')
    <main id="home-page" class="px-8 grid grid-cols-4 gap-6">
        <section class="card h-min hidden lg:flex flex-col gap-3 ">
            <h1 class="text-xl font-bold">Users On This Platform</h1>
            @include('partials.user-list', ['users' => $users, 'responsive' => true])

            @if (!Auth::check())
                <div class="flex justify-center pt-2">
                    @include('partials.text-button', [
                        'text' => 'Login/Register',
                        'anchorUrl' => route('login'),
                    ])
                </div>
            @endif
        </section>

        <section class="card h-min space-y-3 col-span-4 lg:col-span-2">
            <h1 class="text-xl font-bold">Recommended Posts</h1>
            <div id="home-posts" class="space-y-3">
                @include('partials.post-list', ['posts' => $posts])
            </div>
            <div class="flex flex-col items-center">
                @include('partials.loading-spinner')
            </div>
        </section>

        <section class="card h-min hidden lg:flex flex-col gap-3">
            <h1 class="text-xl font-bold">Trending Topics</h1>
            @forelse ($tags as $tag)
                <div class="ms-4">
                    @include('partials.tag', ['tag' => $tag])
                    <p class="text-xs/3 text-gray-500 dark:text-gray-400 select-none">{{ $tag->posts->count() . ' posts' }}</p>
                </div>
            @empty
                <p>No tags at the moment</p>
            @endforelse
        </section>
    </main>
@endsection
