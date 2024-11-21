@extends('layouts.app')

@section('content')
    <main class="p-4 grid grid-cols-3 gap-6">
        @if (count($posts) > 0)
            <section class="card h-min">
                <h1 class="pb-4 text-xl font-semibold">Found {{ count($posts) . (count($posts) === 1 ? ' post' : ' posts') }}</h1>
                <div class="border-t hover:bg-slate-300 hover:dark:bg-slate-600 border-slate-300 dark:border-slate-600 transition-colors">
                    <a href="{{ route('user-search', ['query' => request('query')]) }}" class="py-2 font-medium flex">Search users</a>
                </div>
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