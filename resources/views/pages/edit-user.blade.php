@extends('layouts.app')

@section('content')
<article class="card h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2 m-6 md:m-12 lg:m-24">
    <h1 class="text-4xl font-bold">Edit Profile</h1>
    <form method="post" action="{{ route('users.update', $user->id) }}" class="grid gap-4 justify-self-stretch" id="profile-form">
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

        <!-- Languages Section -->
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
        
        <!-- Technologies Section -->
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

        <!-- Projects Section -->
        <div class="flex flex-col">
            <label for="projects" class="mb-2 font-medium">Projects</label>

            <!-- Existing Projects List -->
            <div id="projects" >
                @foreach ($user->stats->projects as $project)
                    <div class="flex items-center mb-4" data-project-id="{{ $project->id }}">
                        <input type="text" name="projects[{{ $project->id }}][name]" value="{{ $project->name }}" placeholder="Project Name" class="w-full card mb-2 mr-2">
                        <input type="url" name="projects[{{ $project->id }}][url]" value="{{ $project->url }}" placeholder="Project URL" class="w-full card mb-2 mr-2">
                        <button type="button" class="btn btn-danger text-white remove-project-btn" onclick="removeProject(this)">Remove</button>
                    </div>
                @endforeach
            </div>
            <div id = "new_projects">
            </div>

            <!-- Add New Project Button -->
            <div class="flex items-center mb-4">
                <input type="text" id="new_project_name" placeholder="Project Name" class="w-full card mb-2 mr-2">
                <input type="url" id="new_project_url" placeholder="Project URL" class="w-full card mb-2 mr-2">
                <button type="button" id="add_project" class="btn btn-primary text-white">Add Project</button>
            </div>
        </div>

        <div class="flex flex-col mt-6 max-w-40">
            @include('partials.text-button', ['text' => 'Update', 'label' => 'Update', 'type' => 'primary', 'submit' => true])
        </div>
    </form>
</article>

<script>
let projectCounter = 1; 

document.getElementById('add_project').addEventListener('click', function () {
    const name = document.getElementById('new_project_name').value.trim();
    const url = document.getElementById('new_project_url').value.trim();

    if (name && url) {
        const newProjectId = projectCounter++;

        const container = document.createElement('div');
        container.classList.add('flex', 'items-center', 'mb-4');
        container.dataset.projectId = newProjectId;

        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.name = `new_projects[${newProjectId}][name]`;
        nameInput.value = name;
        nameInput.placeholder = 'Project Name';
        nameInput.dataset.projectId = newProjectId; 
        nameInput.classList.add('w-full', 'card', 'mb-2', 'mr-2');

        const urlInput = document.createElement('input');
        urlInput.type = 'url';
        urlInput.name = `new_projects[${newProjectId}][url]`;
        urlInput.value = url;
        urlInput.placeholder = 'Project URL';
        urlInput.dataset.projectId = newProjectId;
        urlInput.classList.add('w-full', 'card', 'mb-2', 'mr-2');

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'text-white');
        removeButton.innerText = 'Remove';
        removeButton.onclick = function () {
            container.remove();
        };

        container.appendChild(nameInput);
        container.appendChild(urlInput);
        container.appendChild(removeButton);

        const projectsSection = document.getElementById('new_projects');
        projectsSection.appendChild(container);

        document.getElementById('new_project_name').value = '';
        document.getElementById('new_project_url').value = '';
    } else {
        alert('Please provide both project name and URL.');
    }
});


function removeProject(button) {
    button.parentElement.remove();
}


</script>

@endsection
