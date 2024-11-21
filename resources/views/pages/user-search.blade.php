@extends('layouts.app')

@section('content')
    <main class="p-4 grid grid-cols-3 gap-6">
        @if (count($users) > 0)
            <section class="card h-min">
                <h1 class="text-xl font-semibold">Found {{ count($users) . (count($users) === 1 ? ' user' : ' users') }}</h1>
            </section>
            <section class="flex flex-col col-span-2">
                @foreach ($users as $user)
                    @include('partials.user-card', ['user' => $user])
                @endforeach
            </section>
        @else
            <section class="flex justify-center items-center col-span-3">
                <p class="text-lg font-medium text-center">No users found.</p>
            </section>
        @endif
    </main>
@endsection