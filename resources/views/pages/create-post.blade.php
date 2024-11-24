@extends('layouts.app')

@section('content')
    <main id="create-post-page" class="grid grid-cols-3 items-center">
        <article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2">
            <h1 class="text-xl font-bold">Create a New Post</h1>
            <form action="{{ route('post.store') }}" method="POST" class="grid gap-4 justify-self-stretch">
                @csrf
                
                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'required' => true
                ])
                
                @include('partials.input-field', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'placeholder' => 'Write your post here...',
                    'required' => true
                    ])
                    
                    <div class="flex flex-col">
                        <label class="mb-2">
                            <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}> 
                            <span class="font-medium">Make this post public</span>
                        </label>
                    </div>
                            
                @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    