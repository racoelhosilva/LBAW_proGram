@extends('layouts.auth')

@section('content')
    <main id="404-page" class="grid grid-cols-3 items-center">
        @include('partials.error-page', ['error' => '404', 'message' => 'Page Not Found'])
    </main> 
@endsection

