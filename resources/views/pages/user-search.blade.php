@extends('layouts.app')

@section('content')
    <main class="p-4 grid grid-cols-4 gap-6">
        @if (count($users) > 0)
            <section class="card h-min">
                <h1 class="pb-2 text-xl font-semibold">Search Options</h1>
                <div class="hover:bg-slate-300 hover:dark:bg-slate-600 border-slate-300 dark:border-slate-600 transition-colors">
                    <a href="{{ route('post-search', ['query' => request('query')]) }}" class="py-2 font-medium flex">Search posts</a>
                </div>
            </section>
            <section class="flex flex-col col-span-3 gap-3">
                <h1 class="text-xl font-semibold">Found {{ count($users) . (count($users) === 1 ? ' user' : ' users') }}</h1>
                @foreach ($users as $user)
                    @include('partials.user-card', ['user' => $user])
                @endforeach
            </section>
        @else
            <section class="flex justify-center items-center col-span-4">
                <p class="text-lg font-medium text-center">No users found.</p>
            </section>
        @endif
    </main>
@endsection