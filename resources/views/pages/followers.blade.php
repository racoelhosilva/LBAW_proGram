@props(['user'])

@extends('layouts.app')

@section('title') {{'Followers of ' . $user->name . ' | ProGram'}} @endsection

@section('content')
<main class="px-8">
    <article id="users" class="card space-y-3">
        <h3 class="text-xl font-bold">Followers of {{$user->name}}</h3>
        <div class="grid gap-x-4 gap-y-2 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            @include('partials.user-list', ['users' => $user->followers, 'remove' => $isOwnFollowers])
        <div>
    </article>
</main>
@endsection