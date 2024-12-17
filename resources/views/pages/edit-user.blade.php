@extends('layouts.app')
@section('title')
    {{ 'Edit ' . $user->name . ' | ProGram' }}
@endsection
@section('content')
    <main class="card h-min p-10 pt-16 grid gap-6 justify-items-center col-start-2 m-6 md:m-16 lg:m-32">
        <h1 class="text-4xl font-bold">Edit Profile</h1>
        <form method="post" action="{{ route('user.update', $user->id) }}" class="grid gap-4 justify-self-stretch"
            id="profile-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('partials.input-field', ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'value' => $user->name, 'placeholder' => 'John Doe', 'required' => true])
            @include('partials.textarea', ['name' => 'description', 'label' => 'Description', 'type' => 'text', 'value' => $user->description, 'placeholder' => 'I am just a chill dev', 'required' => false])
            @include('partials.input-field', ['name' => 'handle', 'label' => 'Handle', 'type' => 'text', 'value' => $user->handle, 'placeholder' => 'john_doe', 'required' => true])

            @include('partials.input-field', ['name' => 'github_url', 'label' => 'GitHub URL', 'type' => 'url', 'value' => $user->stats->github_url, 'placeholder' => 'github.com/johndoe', 'required' => false])
            @include('partials.input-field', ['name' => 'gitlab_url', 'label' => 'GitLab URL', 'type' => 'url', 'value' => $user->stats->gitlab_url, 'placeholder' => 'gitlab.com/johndoe', 'required' => false])
            @include('partials.input-field', ['name' => 'linkedin_url', 'label' => 'LinkedIn URL', 'type' => 'url', 'value' => $user->stats->linkedin_url, 'placeholder' => 'linkedin.com/in/johndoe', 'required' => false])

            <section id="languages-section" class="flex flex-col">
                <label for="languages" class="font-medium">Languages</label>
                <select name="languages[]" id="languages" multiple class="card overflow-auto">
                    @foreach ($languages as $language)
                        <option class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $language->id }}"
                            {{ $user->stats->languages->contains('id', $language->id) ? 'selected' : '' }}>
                            {{ $language->name }}</option>
                    @endforeach
                </select>
            </section>

            <section id="technologies-section" class="flex flex-col">
                <label for="technologies" class="font-medium">Technologies</label>
                <select name="technologies[]" id="technologies" multiple class="card overflow-auto">
                    @foreach ($technologies as $technology)
                        <option class="w-full text-gray-600 dark:text-white px-4 py-2" value="{{ $technology->id }}"
                            {{ $user->stats->technologies->contains('id', $technology->id) ? 'selected' : '' }}>
                            {{ $technology->name }} </option>
                    @endforeach
                </select>
            </section>

            <section id="projects-section" class="flex flex-col">
                <label for="projects" class="font-medium">Projects</label>
                <div id="projects">
                    @foreach ($user->stats->topProjects as $project)
                        <div class="grid grid-cols-12 gap-2" data-project-id="{{ $project->id }}">
                            <input type="text" name="top_projects[{{ $project->id }}][name]"
                                value="{{ $project->name }}" placeholder="Project Name" class="col-span-5 w-full card my-2"
                                readonly>
                            <input type="url" name="top_projects[{{ $project->id }}][url]"
                                value="{{ $project->url }}" placeholder="Project URL"
                                class="lg:col-span-6 col-span-5 w-full card my-2" readonly>
                            <button type="button" class="btn btn-danger remove-project-btn">Remove</button>
                        </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-12 gap-2">
                    <input type="text" id="new-project-name" placeholder="Project Name"
                        class="col-span-5 w-full card my-2">
                    <input type="url" id="new-project-url" placeholder="Project URL"
                        class="lg:col-span-6 col-span-5 w-full card my-2">
                    <button type="button" id="add-project" class="btn btn-primary">Add Project</button>
                </div>
            </section>

            <section id="image-uploads">
                <div class="my-4">
                    <label for="profile_picture" class="font-medium block my-2">Profile Picture</label>
                    <input id="profile_picture" name="profile_picture" type="file">
                </div>
                <div class="my-4">
                    <label for="banner_picture" class="font-medium block my-2">Banner Picture</label>
                    <input id="banner_picture" name="banner_picture" type="file">
                </div>
            </section>

            <div class="flex flex-col w-full">
                @include('partials.text-button', [
                    'text' => 'Update',
                    'label' => 'Update',
                    'type' => 'primary',
                    'submit' => true,
                ])
            </div>
        </form>

        <form method="post" action="{{ route('user.destroy', $user->id) }}" class="w-full flex flex-col">
            @csrf
            @method('DELETE')
            @include('partials.text-button', [
                'text' => 'Delete Account',
                'type' => 'primary',
                'submit' => true,
            ])
        </form>
    </main>
@endsection
