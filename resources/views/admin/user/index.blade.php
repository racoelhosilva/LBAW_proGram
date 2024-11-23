<form action="{{ route('admin.user.search') }}" method="GET">
    <input type="text" name="query" placeholder="Search users" value="{{ request()->query('query') }}">
    <button type="submit">Search</button>
</form>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Ban</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
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
