@extends('layouts.app')
@section('title')
    {{ 'Edit ' . $user->name . ' | ProGram' }}
@endsection
@section('content')
    <main class="px-8 grid grid-cols-4 grid-rows-[auto_1fr] gap-6">
        @include('partials.user-settings-menu')
        <section class="card p-10 pt-16 h-min col-span-4 lg:col-span-3 grid gap-6 justify-items-center">
            <h1 class="text-2xl font-bold">Edit Profile</h1>
            <form method="post" action="{{ route('user.update', $user->id) }}" class="grid gap-4 justify-self-stretch"
                id="profile-form" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('partials.input-field', [
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'text',
                    'value' => $user->name,
                    'placeholder' => 'John Doe',
                    'required' => true,
                ])
                @include('partials.textarea', [
                    'name' => 'description',
                    'label' => 'Description',
                    'type' => 'text',
                    'value' => $user->description,
                    'placeholder' => 'I am just a chill dev',
                    'required' => false,
                ])
                @include('partials.input-field', [
                    'name' => 'handle',
                    'label' => 'Handle',
                    'type' => 'text',
                    'value' => $user->handle,
                    'placeholder' => 'john_doe',
                    'required' => true,
                ])
                <div class="flex items-center mt-4">
                    <input type="checkbox" id="is_public" name="is_public" value="1"
                        class="w-5 h-5 mr-2 text-blue-600 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                        {{ $user->is_public ? 'checked' : '' }}>
                    <label for="is_public" class="font-medium text-gray-700 dark:text-gray-200">
                        Make profile public
                    </label>
                </div>


                @include('partials.input-field', [
                    'name' => 'github_url',
                    'label' => 'GitHub URL',
                    'type' => 'url',
                    'value' => $user->stats->github_url,
                    'placeholder' => 'github.com/johndoe',
                    'required' => false,
                ])
                @include('partials.input-field', [
                    'name' => 'gitlab_url',
                    'label' => 'GitLab URL',
                    'type' => 'url',
                    'value' => $user->stats->gitlab_url,
                    'placeholder' => 'gitlab.com/johndoe',
                    'required' => false,
                ])
                @include('partials.input-field', [
                    'name' => 'linkedin_url',
                    'label' => 'LinkedIn URL',
                    'type' => 'url',
                    'value' => $user->stats->linkedin_url,
                    'placeholder' => 'linkedin.com/in/johndoe',
                    'required' => false,
                ])

                <section id="languages-section" class="flex flex-col">
                    <label for="languages" class="font-medium">Languages</label>
                    @include('partials.tag-select', [
                        'tags' => $languages,
                        'label' => 'Languages',
                        'selected' => $user->stats->languages->pluck('id')->all(),
                        'form' => 'profile-form',
                    ])
                </section>

                <section id="technologies-section" class="flex flex-col">
                    <label for="technologies" class="font-medium">Technologies</label>
                    @include('partials.tag-select', [
                        'tags' => $technologies,
                        'label' => 'Technologies',
                        'selected' => $user->stats->technologies->pluck('id')->all(),
                        'form' => 'profile-form',
                    ])
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
                    'type' => 'danger',
                    'submit' => true,
                ])
            </form>
        </section>

        <section class="w-full">
            <h2 class="text-2xl font-bold">API Token</h2>
            <div class="py-2 flex justify-between items-center gap-4">
                @isset($user->token)
                    @include('partials.copy-field', ['value' => $user->token->value])
                    <form method="post" action="{{ route('token.destroy', $user->token->id) }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        @include('partials.text-button', ['text' => 'Revoke Token', 'label' => 'Revoke Token', 'type' => 'primary', 'submit' => true])
                    </form>
                @else
                    <p class="text-gray-700 dark:text-gray-400">You do not have an API token yet.</p>
                    <form method="post" action="{{ route('token.store') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="duration" value="day">
                        @include('partials.text-button', ['text' => 'Generate Token', 'label' => 'Generate Token', 'type' => 'primary', 'submit' => true])
                    </form>
                @endisset
            </div>
        </section>
    </main>
@endsection
