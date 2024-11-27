@extends('layouts.app')

@section('title', $card->name)
@section('title') {{$card->name}} @endsection
@section('content')
    <section id="cards">
        @include('partials.card', ['card' => $card])
    </section>
@endsection