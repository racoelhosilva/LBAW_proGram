@extends('layouts.auth')

@section('content')
    <main id="login-page" class="grid grid-cols-3 items-center">
        @include('partials.login-form', ['route' => 'login', 'logo' => 'partials.logo'])
    </main>
@endsection