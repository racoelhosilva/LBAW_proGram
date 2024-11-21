@extends('layouts.app')

@section('content')

@include('partials.post-card', ["post" => $post])
    
@endsection
