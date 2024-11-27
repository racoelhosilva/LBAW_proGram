@extends('layouts.auth')

@section('content')
    <main class="grid grid-cols-4 items-center">
        @include('auth.register-form')
    </main>
@endsection
