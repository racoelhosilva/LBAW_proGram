@extends('layouts.auth')
@section('title')
    {{ 'Login | ProGram' }}
@endsection
@section('content')
    <main id="login-page" class="grid grid-cols-3 items-center">
        @include('auth.login-form')
    </main>
@endsection
