@props(['user', 'requests'])

@extends('layouts.app')

@section('title', 'Follow Requests for ' . $user->name . ' | ProGram')

@section('content')
    <main class="px-8">
        <section id="user-follow-requests" class="card space-y-3">
            <h1 class="text-xl font-bold">Follow Requests for {{$user->name}}</h1>
            <div class="grid gap-x-4 gap-y-2 grid-cols-1 lg:grid-cols-2">
                @forelse ($requests as $request)
                    @include('partials.user-card-request', ['user' => $request->follower])
                @empty
                    <p>This user has no follow requests</p>
                @endforelse
            </div>
            @if (!$requests->isEmpty())
            <div>
                {{ $requests->onEachSide(0)->links() }}
            </div>
        @endif
    </section>
    </main>
@endsection