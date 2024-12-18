@props(['users', 'showEmpty', 'responsive' => false])

@forelse ($users as $user)
    @include('partials.user-card', ['user' => $user, '$responsive' => $responsive])
@empty
    @if ($showEmpty)
        <p>No users at the moment</p>
    @endif
@endforelse
