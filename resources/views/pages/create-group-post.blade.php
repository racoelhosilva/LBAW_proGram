@extends('layouts.app')

@section('title') {{'Create Post on '. $group->name . ' | ProGram'}} @endsection

@section('content')
    <main id="create-post-page" class="grid grid-cols-3 items-center">
        <article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2">
            <h1 class="text-xl font-bold">Create a New Post</h1>
            <form id="create-post-form" class="grid gap-4 justify-self-stretch">
                @csrf
                
                <!-- Hidden input for group_id -->
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
                    <label for="tags" class="mb-2 font-medium">Associated Tags</label>
                    <select name="tags[]" id="tags" multiple class="card overflow-auto">
                        @foreach ($tags as $tag)
                            <option class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
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
                        <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', true) ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
