@extends('layouts.app')
@section('title') {{'Home | ProGram'}} @endsection
@section('content')
    <main id="home-page" class="px-8 grid grid-cols-4 gap-6">
        <section class="card h-min flex flex-col gap-3">
            <h1 class="text-xl font-bold">Users On This Platform</h1>
            @forelse ($users as $user)
                @include('partials.user-card', ['user' => $user])
            @empty
                <p>No users at the moment</p>
            @endforelse

            @if (!Auth::check())
                <div class="flex justify-center pt-2">
                    @include('partials.text-button', [
                        'text' => 'Login/Register',
                        'anchorUrl' => route('login'),
                    ])
                </div>
            @endif
        </section>

        <section id="home-posts" class="card h-min flex flex-col gap-3 col-span-2">
            <h1 class="text-xl font-bold">Recommended Posts</h1>
            @include('partials.post-list', ['posts' => $posts])
        </section>

        <section class="card h-min flex flex-col gap-3">
            <h1 class="text-xl font-bold">Trending Topics</h1>
            @forelse ($tags as $tag)
                <div class="select-none">
                    <span class="ms-4 font-medium text-blue-600 dark:text-blue-400">{{ '#' . $tag->name }}</span>
                    <p class="ms-4 text-xs/3 text-gray-500 dark:text-gray-400">{{ $tag->posts->count() . ' posts' }}</p>
                </div>
            @empty
                <p>No tags at the moment</p>
            @endforelse
        </section>
    </main>
@endsection
