@extends('layouts.admin')

@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('partials.admin-search-field', ['route' => 'admin.ban.search'])

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Username</th>
                    <th>Admin</th>
                    <th>Start</th>
                    <th>Reason</th>
                    <th>Duration</th>
                    <th>Is Active?</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bans as $ban)
                    <tr>
                        <td><strong>{{ $ban->id }}</strong></td>
                        <td>{{ $ban->user->handle }}</td>
                        <td>{{ $ban->administrator->name }}</td>
                        <td>{{ $ban->start }}</td>
                        <td>{{ $ban->reason }}</td>
                        <td>{{ $ban->duration != '00:00:00' ? $ban->duration : 'Permanent' }}</td>
                        <td>{{ $ban->isActive() ? 'Yes' : 'No' }}</td>
                        <td class="pe-8 flex justify-end gap-2">
                            @if ($ban->isActive())
                                <form action="{{ route('admin.ban.revoke', $ban->id) }}" method="POST">
                                    @csrf
                                    @include('partials.text-button', [
                                        'text' => 'Revoke',
                                        'type' => 'secondary',
                                        'submit' => true,
                                    ])
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No bans found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $bans->links() }}
    </main>
@endsection
