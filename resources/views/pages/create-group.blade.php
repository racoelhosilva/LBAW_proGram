@extends('layouts.app')
@section('title') {{'Create Group | ProGram'}} @endsection
@section('content')
    <main id="create-group-page" class="flex justify-center items-center">
        <article class="h-min card p-10  max-w-xl w-full flex flex-col  gap-12">
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
                
                <input type="hidden" name="owner_id" value="{{ auth()->id() }}">
                            
                @include('partials.text-button', ['text' => 'Create group', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    
