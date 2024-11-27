@extends('layouts.auth')
@section('title') {{'Login'}} @endsection
@section('content')
    <main id="login-page" class="grid grid-cols-3 items-center">
        @include('partials.login-form')
    </main>
@endsection