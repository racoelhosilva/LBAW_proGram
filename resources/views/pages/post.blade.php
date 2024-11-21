@extends('layouts.app')

@section('content')

<main id="home-page" class="px-8 py-4 grid grid-cols-3 gap-6">
    <section id="post" class="col-span-2">
        @include('partials.post-card', ["post" => $post])
    </section>
    
    <section class="card h-fit flex flex-col gap-3 col-span-1">
        <h1 class="text-xl font-bold">Comments</h1>
        @if ($post->comments > 0)
            <div class="flex-1 overflow-y-auto space-y-3">
                @foreach($post->allComments as $comment)
                    @include('partials.comment-card', ['comment' => $comment])
                @endforeach
            </div>
        @else
            <p>No comments on this post</p>
        @endif
    </section>
</main>

@endsection
