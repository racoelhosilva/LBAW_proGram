@extends('layouts.app')
@section('title') {{'Post ' . $post->title . ' | ProGram'}} @endsection
@section('content')
<main id="home-page" class="px-8 py-4 grid grid-cols-3 grid-rows-[auto_1fr] gap-6">
    <section id="post" class="col-span-3 lg:col-span-2">
        @include('partials.post-card', ["post" => $post])
    </section>
    
    <section id="comment-section" class="card flex flex-col gap-3 col-span-3 lg:col-span-1" style="height: calc(100vh - 6rem - 4rem - 3rem);">
        <h1 class="text-xl font-bold">Comments</h1>
        @auth
            <form id ="comment-submit-form">
                @include('partials.textarea', ['name' => 'content', 'placeholder' => 'Write a comment...', 'label' => 'Comment Content', 'required' => true])
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                @if (Auth::check())
                    <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                @endif
                @include('partials.text-button', ['text' => 'Post Comment', 'class' => 'w-full mt-2 mb-2', 'submit' => true])
            </form>
        @endauth
        <div class="flex-1 overflow-y-auto space-y-3" id="comment-list">
            @include('partials.comment-list', ['comments' => $comments])
        </div>
        <div class="flex flex-col items-center">
            @include('partials.loading-spinner')
        </div>
    </section>
</main>
@endsection
