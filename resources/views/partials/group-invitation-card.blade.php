@props(['group', 'class'])

@extends('partials.group-card', ['group' => $group, 'class' => $class])

@section('buttons')
    @include('partials.icon-button', ['iconName' => 'check', 'type' =>'secondary', 'id' => '', 'class' => 'accept-invite-button', 'label' => 'accept'])
    @include('partials.icon-button', ['iconName' => 'x', 'type' =>'secondary', 'id' => '', 'class' => 'reject-invite-button', 'label' => 'reject'])
@endsection
