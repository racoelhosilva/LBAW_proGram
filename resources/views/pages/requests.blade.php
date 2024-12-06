@extends('layouts.app')

@section('title') {{'Follow Requests for ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3">
    <h3 class="text-xl font-bold">Follow Requests for {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 grid-cols-2">
        @forelse ($requests as $request)
            <div class="grid gap-x-4 gap-y-2 grid-cols-[1fr_auto_auto] items-center">
                @include('partials.user-card', ['user' => $request->follower])
                @include('partials.text-button', ['text' => 'Accept', 'type' => 'secondary'])
                @include('partials.text-button', ['text' => 'Reject', 'type' => 'secondary'])
            </div>
        @empty
            <p>This user has no follow requests</p>
        @endforelse
    </div>
</article>
@endsection