@extends('layouts.auth')

@section('content')
    <main id="403-page" class="grid grid-cols-3 items-center">
        @include('partials.error-page', ['error' => '403', 'message' => 'Access Forbidden'])
    </main>
@endsection

