@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<article id="users" class="mx-12 card space-y-3">
    <h3 class="text-xl font-bold">Followed by {{$user->name}}</h3>
    <div class="grid gap-x-4 gap-y-2 grid-cols-3">
        @forelse ($user->following as $following)
            @if (Auth::check() && Auth::id() == $user->id)
                <form action="{{ route('api.user.unfollow', $following->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    @include('partials.user-card', ['user' => $following, 'buttonText' => 'Unfollow'])
                </form>
            @else
                @include('partials.user-card', ['user' => $following])
            @endif
        @empty
            <p>This user does not follow any other user</p>
        @endforelse
    </div>
</article>
@endsection