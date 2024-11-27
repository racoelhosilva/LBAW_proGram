@props(['reason', 'expires'])

@extends('layouts.app')

@section('content')
    <main id="banned-page" class="grid grid-cols-3 items-center">
        @include('errors.error-page', [
            'number' => '403', 
            'error' => 'You are currently banned.',
            'message' => "You do not have permission to access the site.\nReason: $reason\nExpires: $expires",
            'button' => 'logout',
            ])
    </main> 
@endsection

