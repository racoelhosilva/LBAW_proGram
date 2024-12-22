@extends('layouts.auth')
@section('title', 'Reset Password | ProGram')
@section('content')
    <main id="reset-password-page" class="px-8 flex justify-center items-center">
        @include('auth.forgot-password-form')
    </main>
@endsection
