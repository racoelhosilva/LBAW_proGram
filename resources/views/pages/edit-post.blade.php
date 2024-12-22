@props(['post', 'tags'])

@extends('layouts.app')

@section('title', 'Edit Post - ' . $post->title . ' | ProGram')

@section('content')
    <main id="edit-post-page" class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-2">
            <h1 class="mb-12 text-2xl font-bold text-center">Edit Post</h1>
            <form id="edit-post-form" action="{{ route('post.update', $post->id) }}" method="POST" class="mb-4 grid gap-4 justify-self-stretch quill-form" data-quill-field="text">
                @csrf
                @method('PUT')

                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'value' => $post->title,
                    'required' => true,
                ])

                @include('partials.quill-editor', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'value' => $post->text,
                    'required' => false,
                ])

                <section class="flex flex-col">
                    <label for="tags" class="mb-2 font-medium flex gap-2">
                        Associated Tags
                        @include('partials.help-icon', [
                            'text' => 'Tags help categorize your post and make it easier for others to find.',
                        ])
                    </label>
                    @include('partials.tag-select', [
                        'tags' => $tags,
                        'label' => 'Tags',
                        'selected' => $post->tags->pluck('id')->all(),
                        'form' => 'edit-post-form',
                    ])
                </section>

                <section class="flex flex-col gap-2">
                    <label class="flex gap-2">
                        @if($post->group()->first())
                            <input type="checkbox" name="is_public" value="1" {{ $post->group()->first()->is_public ? 'checked' : '' }} hidden>
                        @else
                            <input type="checkbox" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}> 
                            <span class="font-medium">Make this post public</span>
                            @include('partials.help-icon', [
                            'text' => 'Public posts can be viewed by anyone on the platform. Private posts can only be viewed by your followers.',
                        ])
                        @endif
                    </label>
                    <label class="flex gap-2">
                        <input type="checkbox" name="is_announcement" value="1" {{ $post->is_announcement ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                        @if($post->group)
                            @include('partials.help-icon', [
                                'text' => 'Announcements are highlighted and placed in the announcements board.',
                            ])
                        @else
                            @include('partials.help-icon', [
                                'text' => 'Announcements are highlighted and pinned to your profile.',
                            ])
                        @endif
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Update Post', 'label' => 'update', 'type' => 'primary', 'submit' => true])
            </form>

            @include('partials.confirmation-modal', [
                'label' => 'Delete Post',
                'message' => 'Are you sure you want to delete this post? It\'s data will be lost FOREVER (i.e. a very long time)!',
                'action' => route('post.destroy', $post->id),
                'type' => 'button',
                'method' => 'DELETE',
            ])
        </article>
    </main>
@endsection
