@extends('layouts.app')

@section('content')
    <main id="home-page" class="px-8 grid grid-cols-4 gap-6">
        <section class="card h-min flex flex-col gap-3">
            <h1 class="text-xl font-bold">Users On This Platform</h1>
            @if (count($users) > 0)
                @foreach($users as $user)
                    @include('partials.user-card', ['user' => $user])
                @endforeach
            @else
                <p>No users at the moment</p>
            @endif
            <div class="flex justify-center pt-2">
                @include('partials.text-button', ['text' => 'Login/Register', 'anchorUrl' => route('login')])
            </div>
        </section>
        <section class="card h-min flex flex-col gap-3 col-span-2">
            <h1 class="text-xl font-bold">Recommended Posts</h1>
            @if (count($posts) > 0)
                @foreach($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            @else
                <p>No posts at the moment</p>
            @endif
        </section>
        <section class="card h-min flex flex-col gap-3">
            <h1 class="text-xl font-bold">Trending Topics</h1>
            @if (count($tags) > 0)
                @foreach($tags as $tag)
                    <div class="select-none">
                        <a href="/" class="ms-4 font-medium text-blue-600 dark:text-blue-400">{{ '#' . $tag->name }}</a>
                        <p class="ms-4 text-xs/3 text-gray-500 dark:text-gray-400">{{ $tag->posts->count() . ' posts' }}</p>
                    </div>
                @endforeach
            @else
                <p>No tags at the moment</p>
            @endif
        </section>
    </main>
@endsection