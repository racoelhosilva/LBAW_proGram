@extends('layouts.app')

@section('title') {{'Followers of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3">
    <h3 class="text-xl font-bold">Followers of {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 grid-cols-3">
        @forelse ($user->followers as $follower)
            @if (Auth::check() && Auth::id() == $user->id)
                @include('partials.user-card', ['user' => $follower, 'buttonText' => 'Remove'])
            @else
                @include('partials.user-card', ['user' => $follower])
            @endif
        @empty
            <p>This user has no followers</p>
        @endforelse
    <div>
</article>
@endsection