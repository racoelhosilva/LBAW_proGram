@extends('layouts.app')

@section('title') {{$group->name . 'members | ProGram'}} @endsection

@section('content')
    <main id="members-page" class="px-8 py-4 flex flex-col gap-6">
        <h1 class="text-4xl text-center font-medium">Members of {{$group->name}}</h1>
        @forelse ($members as $user)
            @include('partials.user-card', ['user' => $user])
        @empty
            <p>No users at the moment</p>
        @endforelse
    </main>
@endsection
