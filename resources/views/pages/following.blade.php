@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3">
    <h3 class="text-xl font-bold">Followed by {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 grid-cols-3">
        @forelse ($user->following as $following)
            @include('partials.user-card', ['user' => $following])
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection