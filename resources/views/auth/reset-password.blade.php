@extends('layouts.auth')
@section('title', 'Reset Password | ProGram')
@section('content')
    <main id="reset-password-page" class="flex justify-center items-center">
        @include('auth.reset-password-form')
    </main>
@endsection
