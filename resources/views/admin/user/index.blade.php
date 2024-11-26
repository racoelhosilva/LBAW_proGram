@extends('layouts.admin')

@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('partials.admin-search-field', ['route' => 'admin.post.search'])

    <table class="mt-4">
        <thead class="text-center">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Handle</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-t border-white">
                    <td>{{ $user->id }}</td>
                    <td><a href="/api/user/{{ $user->id }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->handle }}</td>
                    <td class="pe-8 flex justify-end gap-2">
                        {{-- TODO: Place user profile --}}
                        @include('partials.text-button', ['text' => 'View Profile', 'type' => 'secondary', 'anchorUrl' => route('home', ['id' => $user->id])])
                        <div class="modal ban-modal">
                            @include('partials.text-button', ['text' => 'Ban', 'class' => 'open-button', 'type' => 'secondary'])
                            <div>
                                <div>
                                    <div class="mb-4 flex justify-between items-center">
                                        <h1 class="text-2xl font-bold">Ban User</h1>
                                        @include('partials.icon-button', ['iconName' => 'x', 'class' => 'close-button', 'label' => 'Close', 'type' => 'transparent'])
                                    </div>
                                    <form method="post" action="{{ route('admin.ban.store', $user->id) }}" class="grid gap-4">
                                        @csrf
                                        @include('partials.input-field', ['name' => 'reason', 'label' => 'Reason for ban', 'placeholder' => 'Inappropriate behavior', 'required' => true])
                                        @include('partials.input-field', ['name' => 'duration', 'type' => 'number', 'label' => 'Duration (days)', 'placeholder' => '15', 'required' => true])
                                        <label><input type="checkbox" name="permanent"> Permanent</label>
                                        @include('partials.text-button', ['text' => 'Ban User', 'type' => 'primary', 'submit' => true])
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->links() }}
</main>

@endsection
