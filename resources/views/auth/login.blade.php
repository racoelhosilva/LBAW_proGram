@extends('layouts.auth')
@section('title', 'Login | ProGram')
@section('content')
    <main id="login-page" class="px-8 flex justify-center items-center">
        @include('auth.login-form')
    </main>
@endsection
