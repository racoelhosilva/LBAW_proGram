@extends('layouts.app')

@section('content')
    <div>
        <h1>Users You Might Now</h1>
        <ul>
            @foreach($users as $user)
                <li>
                    <a href="{{ url('users/' . $user->id) }}">
                        {{ $user->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <div>
        <h1>Recommended Posts</h1>
        <p>No posts at the moment</p>
    </div>
    <div>
        <h1>Trending Tags</h1>
        <p>No tags at the moment</p>
    </div>
@endsection