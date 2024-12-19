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
                    <label for="tags" class="font-medium">Associated Tags</label>
                    @include('partials.tag-select', [
                        'tags' => $tags,
                        'label' => 'Tags',
                        'selected' => [],
                        'form' => 'create-post-form',
                    ])
                </section>

                <section class="flex flex-col">
                    <label class="mb-2">
                        @if($group->is_public)
                            <input type="checkbox" name="is_public" value="1" checked hidden>
                        @else
                            <input type="checkbox" name="is_public" value="1" hidden>
                        @endif
                    </label>
                </section>
                            
                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', false) ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
