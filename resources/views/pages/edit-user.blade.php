@extends('layouts.app')

@section('content')
<article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2 m-24">
    <h1 class="text-4xl font-bold">Edit Profile</h1>
    <form method="post" action="{{ route('users.update', $user->id) }}" class="grid gap-4 justify-self-stretch">
        {{ csrf_field() }}

        @include('partials.input-field', ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $user->name, 'placeholder' => 'John Doe', 'required' => false])
        @include('partials.input-field', ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'value' => $user->description, 'placeholder' => 'I am just a chill dev', 'required' => false])
        @include('partials.input-field', ['name' => 'handle', 'label' => 'Handle', 'type' => 'text', 'value' => $user->handle, 'placeholder' => 'john_doe', 'required' => false])

        <div class="flex items-center mt-4">
            <input type="checkbox" id="is_public" name="is_public" value="1" class="w-5 h-5 mr-2 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                {{ $user->is_public ? 'checked' : '' }}
            >
            <label for="is_public" class="font-medium text-gray-700 dark:text-gray-200">
                Make profile public
            </label>
        </div>

        <div class="flex flex-col">
            <label for="languages" class="mb-2 font-medium">Languages</label>
            <select name="languages[]" id="languages" multiple class="card overflow-auto">
                @foreach ($languages as $language)
                    <option 
                        class="w-full text-gray-600 dark:text-white px-4 py-2" 
                        value="{{ $language->id }}" 
                        {{ $user->stats->languages->contains('id', $language->id) ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex flex-col">
            <label for="technologies" class="mb-2 font-medium">Technologies</label>
            <select name="technologies[]" id="technologies" multiple class="card overflow-auto">
                @foreach ($technologies as $technology)
                    <option 
                        class="w-full text-gray-600 dark:text-white px-4 py-2" 
                        value="{{ $technology->id }}" 
                        {{ $user->stats->technologies->contains('id', $technology->id) ? 'selected' : '' }}>
                        {{ $technology->name }}
                    </option>
                @endforeach
            </select>
        </div>
        


        <div class="flex flex-col mt-6 max-w-40">
            @include('partials.text-button', ['text' => 'Update', 'label' => 'Update', 'type' => 'primary', 'submit' => true])
        </div>
    </form>
</article>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('languages-container');
        const addButton = document.getElementById('add-language-button');

        addButton.addEventListener('click', function () {
            const newField = document.createElement('div');
            newField.classList.add('flex', 'items-center', 'mt-2');
            newField.innerHTML = `
                <input type="text" name="languages[]" class="flex-1 h-12 px-5 rounded-lg bg-white dark:bg-slate-700 text-gray-600 dark:text-white border border-slate-300 dark:border-slate-600 focus:border-blue-600 outline-none" placeholder="New Language">
                <button type="button" class="ml-2 text-red-500 remove-language">Remove</button>
            `;
            container.appendChild(newField);
        });

        container.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('remove-language')) {
                e.target.parentElement.remove();
            }
        });
    });
</script>
@endsection
