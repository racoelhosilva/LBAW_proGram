@props(['groups', 'showEmpty' => true])

@forelse ($groups as $group)
    @include('partials.group-card', ['group' => $group])
@empty
    @if ($showEmpty)
        <p>No groups at the moment</p>
    @endif
@endforelse
