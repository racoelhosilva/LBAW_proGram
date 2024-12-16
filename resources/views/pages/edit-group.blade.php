@extends('layouts.app')
@section('title') {{'Edit ' . $group->name . ' | ProGram'}} @endsection
@section('content')
    <main id="create-post-page" class="grid grid-cols-[1fr_auto_1fr] sm:grid-cols-3 items-center">
        <article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2">
            <h1 class="text-xl font-bold">Edit {{$group->name}}</h1>
            <form action="{{ route('group.update',$group->id) }}" method="POST" class="grid gap-4 justify-self-stretch">
                @csrf

                @method('PUT')
                
                @include('partials.input-field', [
                    'name' => 'name',
                    'label' => 'Group Name',
                    'placeholder' => 'Enter name',
                    'value' => $group->name,
                    'required' => true
                ])
                
                @include('partials.textarea', [
                    'name' => 'description',
                    'label' => 'Group Description',
                    'placeholder' => 'Write your description here...',
                    'value' => $group->description,
                    'required' => false
                ])
                <section class="flex flex-col">
                    <label class="mb-2">
                        <input type="checkbox" name="is_public"  value="1" {{ $group->is_public ? 'checked' : '' }}> 
                        <span class="font-medium">Make this group public</span>
                    </label>
                </section>
                <input type="hidden" name="owner_id" value="{{ auth()->id() }}">
                            
                @include('partials.text-button', ['text' => 'Edit group', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    
