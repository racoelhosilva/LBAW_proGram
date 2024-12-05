@props(['posts'])

@if ($posts)
    @foreach ($posts as $post)
        @include('partials.post-card', ['post' => $post])
    @endforeach
    <a href="{{ $posts->nextPageUrl() }}">Next posts</a>
@else
    <p>No posts at the moment</p>
@endif