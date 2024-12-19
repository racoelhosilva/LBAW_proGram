@props(['users', 'showEmpty', 'responsive' => false, 'remove' => false])

@forelse ($users as $user)
    @include('partials.user-card', ['user' => $user, 'responsive' => $responsive, 'remove' => $remove])
@empty
    @if ($showEmpty)
        <p>No users at the moment</p>
    @endif
@endforelse
