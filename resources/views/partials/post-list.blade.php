@props(['posts'])

@forelse ($posts as $post)
    @include('partials.post-card', ['post' => $post])
@empty
    <p>No posts at the moment</p>
@endforelse