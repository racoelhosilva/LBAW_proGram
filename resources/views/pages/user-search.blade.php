@extends('layouts.app')

@section('content')
    <main class="p-4">
        <section class="flex flex-col">
            @foreach ($users as $user)
                @include('partials.user-card', ['user' => $user])
            @endforeach
        </section>
    </main>
@endsection