@props(['comments', 'showEmpty' => true])

@forelse ($comments as $comment)
    @include('partials.comment-card', ['comment' => $comment])
@empty
    @if ($showEmpty)
        <p>No comments at the moment</p>
    @endif
@endforelse