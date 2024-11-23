@extends('layouts.app')

@section('content')
<main id="login-page" class="grid grid-cols-3">
    @include('partials.login-form', ['route' => 'login', 'logo' => 'partials.logo'])
</main>
@endsection