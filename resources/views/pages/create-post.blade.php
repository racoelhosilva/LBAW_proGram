@props(['tags'])

@extends('layouts.app')

@section('title', 'Create Post | ProGram')

@section('content')

    <main id="create-post-page" class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-12">
            <h1 class="text-2xl font-bold text-center">Create a New Post</h1>
            <form id="create-post-form" action="{{ route('post.store') }}" method="post" class="flex flex-col gap-4 quill-form" data-quill-field="text">
                @csrf
                
                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'required' => true
                ])

                @include('partials.quill-editor', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'required' => false,
                ])
                    
                <div class="flex flex-col">
                    <div class="flex gap-2">
                        <span class="mb-2 font-medium flex gap-2">Associated Tags</span>
                        @include('partials.help-icon', [
                            'text' => 'Tags help categorize your post and make it easier for others to find.',
                        ])
                    </div>
                    @include('partials.tag-select', [
                        'tags' => $tags,
                        'label' => 'Tags',
                        'selected' => [],
                        'form' => 'create-post-form',
                    ])
                </div>

                <div class="flex flex-col gap-2">
                    <div class="flex gap-2">
                        <label class="flex gap-2">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}>
                            <span class="font-medium">Make this post public</span>
                        </label>
                        @include('partials.help-icon', [
                            'text' => 'Public posts can be viewed by anyone on the platform. Private posts can only be viewed by your followers.',
                        ])
                    </div>
                    <div class="flex gap-2">
                        <label class="flex gap-2">
                            <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', false) ? 'checked' : '' }}>
                            <span class="font-medium">Make this post an announcement</span>
                        </label>
                        @include('partials.help-icon', [
                            'text' => 'Announcements are highlighted and pinned to your profile.',
                        ])
                    </div>
                </div>
                @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    
