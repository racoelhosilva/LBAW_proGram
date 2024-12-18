@props(['reason', 'expires'])

@extends('layouts.app')

@section('content')
    <main id="deleted-page" class="grid grid-cols-3 items-center">
        @include('errors.error-page', [
            'number' => '403',
            'error' => 'Your account has been deleted',
            'message' => '',
            'button' => 'logout',
        ])
    </main>
@endsection
