@extends('layouts.app')

@section('content')
<article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2 m-24">
    <h1 class="text-4xl font-bold">Edit Profile</h1>
    <form method="post" action="{{ route('users.update', $user->id)  }}" class="grid gap-4 justify-self-stretch">
        {{ csrf_field() }}

        @include('partials.input-field', ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $user->name, 'placeholder' => 'John Doe', 'required' => false])
        @include('partials.input-field', ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'value' => $user->description, 'placeholder' => 'I am just a chill dev', 'required' => false])
        
        <div class="flex flex-col mt-6">
            @include('partials.text-button', ['text' => 'Update', 'label' => 'Update', 'type' => 'primary', 'submit' => true])
        </div>
    </form>

</article>
@endsection
