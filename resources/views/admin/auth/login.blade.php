@extends('layouts.auth')
@section('title', 'Admin Login | ProGram')
@section('content')
    <main id="admin-login-page" class="px-8 flex justify-center items-center">
        @include('admin.auth.login-form')
    </main>
@endsection