<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Admin</th>
            <th>Start</th>
            <th>Reason</th>
            <th>Duration</th>
            <th>Is Active?</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($bans as $ban)
            <tr>
                <td>{{ $ban->user->handle }}</td>
                <td>{{ $ban->administrator->name }}</td>
                <td>{{ $ban->start }}</td>
                <td>{{ $ban->reason }}</td>
                <td>{{ $ban->duration }}</td>
                <td>{{ $ban->is_active }}</td>
                <td>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No bans found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
{{ $bans->links() }}
