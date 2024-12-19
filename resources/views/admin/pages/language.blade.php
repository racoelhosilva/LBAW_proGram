@extends('layouts.admin')
@section('title')
    {{ 'Admin Language Search | ProGram' }}
@endsection
@section('content')
    <main class="px-8 flex flex-col gap-4">
        <div class="grid grid-cols-[1fr_auto] gap-4 items-center">
            @include('admin.partials.search-field', ['route' => 'admin.language.index'])


            <div class="modal ban-modal">
                @include('partials.text-button', [
                    'text' => 'Add Language',
                    'class' => 'open-button',
                    'type' => 'primary',
                ])
                <div>
                    <div>
                        <div class="mb-4 flex justify-between items-center">
                            <h1 class="text-2xl font-bold">Add Language</h1>
                            @include('partials.icon-button', [
                                'iconName' => 'x',
                                'class' => 'close-button',
                                'label' => 'Close',
                                'type' => 'transparent',
                            ])
                        </div>
                        <form method="post" action="{{ route('admin.language.store') }}" class="grid gap-4">
                            @csrf
                            @include('partials.input-field', [
                                'name' => 'language',
                                'label' => '',
                                'type' => 'text',
                                'value' => old('language'),
                                'placeholder' => 'JavaScript',
                                'required' => true,
                            ])
                            @include('partials.text-button', [
                                'text' => 'Submit',
                                'type' => 'primary',
                                'submit' => true,
                            ])
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table>
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Language</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($languages as $language)
                        <tr class="border-t border-white">
                            <td>{{ $language->id }}</td>
                            <td>{{ $language->name }}</td>
                            <td class="flex justify-end">
                                <div class="dropdown">
                                    @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                    <div class="hidden">
                                        <div>
                                            <form method="post" action="{{ route('admin.language.destroy', $language->id) }}" class="flex flex-col">
                                                @csrf
                                                @method('DELETE')
                                                @include('partials.dropdown-item', [
                                                    'icon' => 'trash',
                                                    'text' => 'Delete Language',
                                                ])
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No languages found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $languages->onEachSide(0)->links() }}
    </main>
@endsection
