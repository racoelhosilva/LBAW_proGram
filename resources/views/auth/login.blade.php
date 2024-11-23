@extends('layouts.app')

@section('content')
<main id="login-page">
    @include('partials.login-form', ['route' => 'login', 'logo' => 'partials.logo'])
</main>
@endsection