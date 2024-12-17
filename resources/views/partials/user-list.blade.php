@props(['users', '$showEmpty'])

@forelse ($users as $user)
    @include('partials.user-card', ['user' => $user])
@empty
    @if ($showEmpty)
        <p>No users at the moment</p>
    @endif
@endforelse
