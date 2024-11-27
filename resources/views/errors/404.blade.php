@extends('layouts.app')

@section('content')
    <main id="404-page" class="grid grid-cols-3 items-center">
        @include('errors.error-page', [
            'number' => '404', 
            'error' => 'Oops! Page Not Found...', 
            'message' => 'The page you are looking for might have been removed, renamed or is temporarily unavailable.',
            ])
    </main> 
@endsection

