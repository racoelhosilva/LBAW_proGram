@extends('layouts.app')

@section('title') {{'Create Post on '. $group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="create-post-page" class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-12">
            <h1 class="text-2xl font-bold text-center">Create Post on {{$group->name}}</h1>
            <form id="create-group-post-form" class="grid gap-4" action="{{ route('group.post.store', $group->id) }}" method="POST">
                
                @csrf
                <input type="hidden" id="group_id" name="group_id" value="{{ $group->id }}">

                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'required' => true
                ])
                
                @include('partials.textarea', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'placeholder' => 'Write your post here...',
                    'required' => false
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
                        'selected' => [],
                        'form' => 'create-post-form',
                    ])
                </section>

                <input type="checkbox" name="is_public" value="1" {{ $group->is_public ? 'checked' : '' }} hidden>

                <section class="flex flex-col">
                    <label class="flex gap-2">
                        <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', false) ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                        @include('partials.help-icon', [
                            'text' => 'Announcements are highlighted and placed in the announcements board.',
                        ])
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
