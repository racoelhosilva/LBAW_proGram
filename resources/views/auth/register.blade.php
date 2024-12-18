@extends('layouts.auth')
@section('title') {{'Register | ProGram'}} @endsection
@section('content')
    <main class="px-8 flex justify-center items-center">
        @include('auth.register-form')
    </main>
@endsection
