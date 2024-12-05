@props(['posts', 'showEmpty' => true])

@forelse ($posts as $post)
    @include('partials.post-card', ['post' => $post])
@empty
    @if ($showEmpty)
        <p>No posts at the moment</p>
    @endif
@endforelse