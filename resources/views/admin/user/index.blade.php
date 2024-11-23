<form action="{{ route('admin.user.search') }}" method="GET">
    <input type="text" name="query" placeholder="Search users" value="{{ request()->query('query') }}">
    <button type="submit">Search</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->handle }}</td>
                <td>
                    <!-- temporary route while the profile page is not ready -->
                    <form action="/api/user/{{ $user->id }}" method="GET">
                        <button type="submit">View Profile</button>
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
