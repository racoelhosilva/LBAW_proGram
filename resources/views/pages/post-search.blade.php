@extends('layouts.app')

@section('content')
    <main class="p-4 grid grid-cols-3 gap-6">
        @if (count($posts) > 0)
            <section class="card h-min">
                <h1 class="text-xl font-semibold">Found {{ count($posts) . (count($posts) === 1 ? ' post' : ' posts') }}</h1>
            </section>
            <section class="flex flex-col col-span-2 gap-3">
                @foreach ($posts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </section>
        @else
            <section class="flex justify-center items-center col-span-3">
                <p class="text-lg font-medium text-center">No posts found.</p>
            </section>
        @endif
    </main>
@endsection