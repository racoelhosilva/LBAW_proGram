@extends('layouts.app')

@section('title') {{'Followers of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3">
    <h3 class="text-xl font-bold">Followers of {{$user->name}}</h3>
    @forelse ($user->followers as $follower)
        @include('partials.user-card', ['user' => $follower])
        @if (Auth::check() && Auth::id() == $user->id)
            @include('partials.text-button', ['text' => 'Remove', 'type' => 'secondary'])
        @endif
    @empty
        <p>This user has no followers</p>
    @endforelse
</article>
@endsection