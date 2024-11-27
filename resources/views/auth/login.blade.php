@extends('layouts.auth')

@section('content')
    <main id="login-page" class="grid grid-cols-3 items-center">
        @include('auth.login-form')
    </main>
@endsection