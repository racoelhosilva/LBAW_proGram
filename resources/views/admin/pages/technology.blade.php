@extends('layouts.admin')
@section('title')
    {{ 'Admin Technology Search | ProGram' }}
@endsection
@section('content')
    <main class="px-8 flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-4 items-center">
            @include('admin.partials.search-field', ['route' => 'admin.technology.index'])
            <div class="modal ban-modal">
                @include('partials.text-button', [
                    'text' => 'Add Technology',
                    'class' => 'open-button',
                    'type' => 'primary',
                ])
                <div>
                    <section>
                        <div class="mb-4 flex justify-between items-center">
                            <h1 class="text-2xl font-bold">Add Technology</h1>
                            @include('partials.icon-button', [
                                'iconName' => 'x',
                                'class' => 'close-button',
                                'label' => 'Close',
                                'type' => 'transparent',
                            ])
                        </div>
                        <form method="post" action="{{ route('admin.technology.store') }}" class="grid gap-4">
                            @csrf
                            @include('partials.input-field', [
                                'name' => 'technology',
                                'label' => '',
                                'type' => 'text',
                                'value' => old('technology'),
                                'placeholder' => 'Laravel',
                                'required' => true,
                            ])
                            @include('partials.text-button', [
                                'text' => 'Submit',
                                'type' => 'primary',
                                'submit' => true,
                            ])
                        </form>
                    </section>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto flex flex-col gap-4">
            <table>
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Technology</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($technologies as $technology)
                        <tr class="border-t border-white">
                            <td>{{ $technology->id }}</td>
                            <td>{{ $technology->name }}</td>
                            <td class="flex justify-end">
                                <div class="dropdown">
                                    @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                    <div class="hidden">
                                        <div>
                                            @include('partials.confirmation-modal', [
                                                'label' => 'Delete Technology',
                                                'icon' => 'trash',
                                                'message' => 'Are you sure you want to delete this technology? This action cannot be undone!',
                                                'type' => 'dropdown',
                                                'action' => route('admin.technology.destroy', $technology->id),
                                                'method' => 'DELETE'
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No technologies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $technologies->onEachSide(0)->links() }}
        </div>
    </main>
@endsection
