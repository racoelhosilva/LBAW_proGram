@extends('layouts.app')
@section('title') {{'Edit ' . $post->title . ' | ProGram'}} @endsection
@section('content')
    <main id="create-post-page" class="grid grid-cols-3 items-center">
        <article class="card h-min p-10 pt-16 grid justify-items-center col-start-2">
            <h1 class="mb-12 text-xl font-bold">Edit Post</h1>
            <form action="{{ route('post.update', $post->id) }}" method="POST" class="mb-4 grid gap-4 justify-self-stretch">
                @csrf

                @method('PUT')
                
                @include('partials.input-field', [
                    'name' => 'title',
                    'label' => 'Post Title',
                    'placeholder' => 'Enter title',
                    'value' => $post->title,
                    'required' => true
                ])
                
                @include('partials.textarea', [
                    'name' => 'text',
                    'label' => 'Post Content',
                    'placeholder' => 'Write your post here...',
                    'value' => $post->text,
                    'required' => true
                ])
                    
                <section class="flex flex-col">
                    <label for="tags" class="mb-2 font-medium">Associated Tags</label>
                    <select name="tags[]" id="tags" multiple class="card overflow-auto">
                        @foreach ($tags as $tag)
                            <option {{ $post->hasTag($tag) ? "checked" : "" }} class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $tag->id }}" {{ $post->hasTag($tag) ? "selected" : "" }}>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </section>

                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_public" value="1" {{ $post->is_public ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post public</span>
                    </label>
                </section>
                @include('partials.text-button', ['text' => 'Update Post', 'label' => 'update', 'type' => 'primary', 'submit' => true])
            </form>
            
            <form method="post" action="{{ route('post.destroy', $post->id) }}" class="w-full flex flex-col">
                @csrf
                @method('DELETE')
                @include('partials.text-button', ['text' => 'Delete Post', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
