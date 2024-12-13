@extends('layouts.app')
@section('title') {{$user->name . ' Notifications | ProGram'}} @endsection
@section('content')
<main id="home-page" class="px-8 py-4">
    @foreach($notifications as $notification)
        {{ $notification }}
    @endforeach
</main>
@endsection
