@extends('layouts.app')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}

        <label for="username">Username</label>
        <input id="username" type="text" name="username" value="{{ old('username') }}" class="bg-red-500" required>
        @if ($errors->has('username'))
            <span class="error">
                {{ $errors->first('username') }}
            </span>
        @endif

        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" class="bg-red-500" required
            autofocus>
        @if ($errors->has('name'))
            <span class="error">
                {{ $errors->first('name') }}
            </span>
        @endif

        <label for="email">E-Mail Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"class="bg-red-500" required>
        @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
        @endif

        <label for="password">Password</label>
        <input id="password" type="password" name="password"class="bg-red-500" required>
        @if ($errors->has('password'))
            <span class="error">
                {{ $errors->first('password') }}
            </span>
        @endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation"class="bg-red-500" required>

        <button type="submit">
            Register
        </button>
        <a class="button button-outline" href="{{ route('login') }}">Login</a>
    </form>
@endsection
