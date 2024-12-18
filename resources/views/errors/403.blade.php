@extends('layouts.app')

@section('content')
    <main id="403-page" class="flex flex-col justify-center items-center">
        @include('errors.error-page', [
            'number' => '403', 
            'error' => 'Oops! Forbidden...',
            'message' => 'You do not have permission to access this page.',
            ])
    </main> 
@endsection
