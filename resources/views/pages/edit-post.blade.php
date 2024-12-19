@extends('layouts.app')
@section('title')
    {{ 'Edit ' . $post->title . ' | ProGram' }}
@endsection
@section('content')
    <main id="edit-post-page" class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-12">
            <h1 class="mb-12 text-2xl font-bold">Edit Post</h1>
            <form id="edit-post-form" action="{{ route('post.update', $post->id) }}" method="POST" class="mb-4 grid gap-4 justify-self-stretch">
                @csrf

                @method('PUT')

                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'value' => $post->title,
                    'required' => true,
                ])

                @include('partials.textarea', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'placeholder' => 'Write your post here...',
                    'value' => $post->text,
                    'required' => false,
                ])

                <section class="flex flex-col">
                    <label for="tags" class="mb-2 font-medium">Associated Tags</label>
                    @include('partials.tag-select', [
                        'tags' => $tags,
                        'label' => 'Tags',
                        'selected' => $post->tags->pluck('id')->all(),
                        'form' => 'edit-post-form',
                    ])
                </section>

                <section class="flex flex-col">
                    <label class="mb-2">
                        @if($post->group()->first())
                            @if($post->group()->first()->is_public)
                                <input type="checkbox" name="is_public" value="1" checked hidden>
                            @else
                                <input type="checkbox" name="is_public" value="1" hidden>
                            @endif
                        @else
                            <input type="checkbox" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}> 
                            <span class="font-medium">Make this post public</span>
                        @endif
                    </label>
                </section>
                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_announcement" value="1" {{ $post->is_announcement ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Update Post', 'label' => 'update', 'type' => 'primary', 'submit' => true])
            </form>

            <form method="post" action="{{ route('post.destroy', $post->id) }}" class="w-full flex flex-col">
                @csrf
                @method('DELETE')
                @include('partials.text-button', [
                    'text' => 'Delete Post',
                    'type' => 'danger',
                    'submit' => true,
                ])
            </form>
        </article>
    </main>
@endsection
