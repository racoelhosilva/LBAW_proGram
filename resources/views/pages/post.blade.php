@extends('layouts.app')
@section('title') {{'Post ' . $post->title . ' | ProGram'}} @endsection
@section('content')
<main id="home-page" class="px-8 py-4 grid grid-cols-3 gap-6">
    <section id="post" class="col-span-2">
        @include('partials.post-card', ["post" => $post])
    </section>
    
    <section id="comment-section" class="card h-fit flex flex-col gap-3 col-span-1">
        <h1 class="text-xl font-bold">Comments</h1>
        @auth
            <form id ="comment-submit-form" action="{{ route('api.comment.store') }}" >
                @csrf
                @include('partials.textarea', ['name' => 'content', 'placeholder' => 'Write a comment...', 'label' => ''])
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                @if (Auth::check())
                    <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                @endif
                @include('partials.text-button', ['text' => 'Post Comment', 'class' => 'w-full mt-2 mb-2', 'submit' => true])
            </form>
        @endauth
        <div class="flex-1 overflow-y-auto space-y-3 comment-list">
            @forelse($post->allComments as $comment)
                @include('partials.comment-card', ['comment' => $comment])
            @empty
                <p>No comments on this post</p>
            @endforelse
        </div>
    </section>
</main>
@endsection
