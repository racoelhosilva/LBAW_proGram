@extends('layouts.app')

@section('title') {{'Followers of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
@forelse ($user->followers as $follower)
    @include('partials.user-card', ['user' => $follower])
@empty
    <p>This user has no followers</p>
@endforelse
@endsection