@extends('layouts.admin')

@section('content')

<main class="px-4 flex flex-col gap-4">
    @include('partials.admin-search-field', ['route' => 'admin.user.search'])

    <table class="mt-4">
        <thead class="text-center">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Handle</th>
                <th>Ban</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr class="border-t border-white">
                    <td>{{ $user->id }}</td>
                    <td><a href="/api/user/{{ $user->id }}">{{ $user->name }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->handle }}</td>
                    <td>
                        <form action="{{ route('admin.ban.store', $user->id) }}" method="POST">
                            @csrf
                            <input type="text" name="reason" placeholder="Reason for ban" required>
                            <input type="number" name="duration" placeholder="Duration (days)" required>
                            <button type="submit">Ban</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $users->links() }}
</main>

@endsection
