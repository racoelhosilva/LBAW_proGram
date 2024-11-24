@extends('layouts.app')

@section('content')
    <main>
        <h1>Create a New Post</h1>
        <form action="{{ route('post.store') }}" method="POST" class="">
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

            <div class="form-check mb-3">
                <input type="checkbox" id="is_public" name="is_public" class="form-check-input" value="1" checked>
                <label for="is_public" class="form-check-label">Make this post public</label>
                @error('is_public')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            @include('partials.text-button', ['text' => 'Create Post', 'label' => 'create', 'type' => 'primary', 'submit' => true])
        </form>
    </main>
@endsection
