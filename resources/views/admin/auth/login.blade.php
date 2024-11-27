@extends('layouts.auth')
@section('title') {{'Admin Login | ProGram'}} @endsection
@section('content')
    <main id="admin-login-page" class="grid grid-cols-3 items-center">
        @include('admin.auth.login-form')
    </main>
@endsection