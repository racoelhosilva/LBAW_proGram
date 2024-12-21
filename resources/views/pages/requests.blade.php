@extends('layouts.app')

@section('title', 'Follow Requests for ' . $user->name . ' | ProGram')

@section('content')
<main class="px-8">
    <article id="users" class="card space-y-3">
        <h3 class="text-xl font-bold">Follow Requests for {{$user->name}}</h3>
        <div class="grid gap-x-4 gap-y-2 grid-cols-1 lg:grid-cols-2">
            @forelse ($requests as $request)
                @include('partials.user-card-request', ['user' => $request->follower])
            @empty
                <p>This user has no follow requests</p>
            @endforelse
        </div>
    </article>
</main>
@endsection