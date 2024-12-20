@extends('layouts.app')
@section('title') {{'Edit ' . $group->name . ' | ProGram'}} @endsection
@section('content')
    <main id="edit-group-page" class="px-8">
        <article class="card h-min p-10 pt-16 flex flex-col gap-12">
            <h1 class="text-xl font-bold text-center">Edit {{$group->name}}</h1>
            <form action="{{ route('group.update',$group->id) }}" method="POST" class="grid gap-4 justify-self-stretch">
                @csrf
                @method('PUT')
                @include('partials.input-field', [
                    'name' => 'name',
                    'label' => 'Group Name',
                    'placeholder' => 'Enter name',
                    'value' => $group->name,
                    'required' => true,
                    'help' => 'The name of your group. It will identify your group in the platform.'
                ])
                @include('partials.textarea', [
                    'name' => 'description',
                    'label' => 'Group Description',
                    'placeholder' => 'Write your description here...',
                    'value' => $group->description,
                    'required' => false,
                    'help' => 'This is a brief description that better describes your group. It will be displayed on the group page.'
                ])
                <section class="flex flex-col">
                    <label class="flex gap-2">
                        <input type="checkbox" name="is_public" value="1" {{ $group->is_public ? 'checked' : '' }}>
                        <span class="font-medium">Make this group public</span>
                        @include('partials.help-icon', [
                            'text' => 'Public groups are visible to anyone on the platform. The content of private groups can only be viewed by members.',
                        ])
                    </label>
                </section>
                <input type="hidden" name="owner_id" value="{{ auth()->id() }}">
                            
                @include('partials.text-button', ['text' => 'Edit group', 'label' => 'create', 'type' => 'primary', 'submit' => true])
            </form>
        </article>
    </main>
@endsection
    
