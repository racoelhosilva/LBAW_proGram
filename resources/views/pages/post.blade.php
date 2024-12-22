@props(['post'])

@extends('layouts.app')

@section('title', 'Post ' . $post->title . ' | ProGram')

@section('openGraph')
    <meta property="og:title" content="Post {{ $post->title }} | ProGram' }}">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ route('post.show', $post->id) }}">
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $post->creation_timestamp }}">
    <meta property="article:author" content="{{ $post->author->name }}">
@endsection

@section('content')
<main id="post-page" class="px-8 py-4 grid grid-cols-3 grid-rows-[auto_1fr] gap-6">
    <div class="col-span-3 lg:col-span-2">
        @include('partials.post-card', ["post" => $post])
    </div>
    
    <section id="comment-section" class="card flex flex-col gap-3 col-span-3 lg:col-span-1" style="height: calc(100vh - 6rem - 4rem - 3rem);">
        <h1 class="text-xl font-bold">Comments</h1>
        @auth
            <form id="comment-submit-form" action="{{ route('api.comment.store') }}">
                @include('partials.textarea', ['name' => 'content', 'placeholder' => 'Write a comment...', 'label' => 'Comment Content', 'required' => true])
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="hidden" name="author_id" value="{{ auth()->id() }}">
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
