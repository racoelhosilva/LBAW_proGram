@extends('layouts.app')

@section('title') {{'Followed by ' . $user->name . ' | ProGram'}} @endsection

@section('content')
@forelse ($user->following as $following)
    @include('partials.user-card', ['user' => $following])
@empty
    <p>This user does not follow any other user</p>
@endforelse
@endsection