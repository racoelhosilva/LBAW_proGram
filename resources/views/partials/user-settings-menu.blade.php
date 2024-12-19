@extends('partials.responsive-dropdown', ['label' => 'User Settings'])

@section('dropdownContent')
    <nav class="card h-min flex flex-col">
        <h1 class="pb-4 text-xl font-semibold">User Settings</h1>
        @include('partials.menu-anchor', [
            'anchorUrl' => route('user.edit', ['id' => auth()->id()]),
            'icon' => 'user-round-pen',
            'text' => 'Edit Profile',
            'selected' => request()->routeIs('user.edit'),
        ])
{{--        @include('partials.menu-anchor', [--}}
{{--            'anchorUrl' => route('user.password.edit'),--}}
{{--            'icon' => 'lock',--}}
{{--            'text' => 'Change Password',--}}
{{--            'selected' => request()->routeIs('user.password.edit'),--}}
{{--        ])--}}
        @include('partials.menu-anchor', [
            'anchorUrl' => route('home'),
            'icon' => 'key-round',
            'text' => 'API Settings',
            'selected' => request()->routeIs('home'),
        ])
    </nav>
@endsection