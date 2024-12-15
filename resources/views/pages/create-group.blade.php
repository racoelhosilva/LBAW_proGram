@extends('layouts.app')
@section('title') {{'Create Post | ProGram'}} @endsection
@section('content')
    <main id="create-post-page" class="grid grid-cols-[1fr_auto_1fr] items-center">
        <article class="card h-min p-10 pt-16 grid gap-12  col-start-2 ">
            <h1 class="text-xl font-bold">Create Group</h1>
            <form action="{{ route('group.store') }}" method="POST" class="grid gap-4 justify-self-stretch">
                @csrf
                
                @include('partials.input-field', [
                    'name' => 'name',
                    'label' => 'Group Name',
                    'placeholder' => 'Enter name',
                    'required' => true
                ])
                
                @include('partials.textarea', [
                    'name' => 'description',
                    'label' => 'Group Description',
                    'placeholder' => 'Write your description here...',
                    'required' => false
                ])
                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }}> 
                        <span class="font-medium">Make this group public</span>
                    </label>
                </section>
                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_announcement" value="1" {{ old('is_announcement', true) ? 'checked' : '' }}> 
                        <span class="font-medium">Make this post an announcement</span>
                    </label>
                </section>
                <input type="hidden" name="owner_id" value="{{ auth()->id() }}">
                            
                @include('partials.text-button', ['text' => 'Create group', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    
