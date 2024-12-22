@extends('layouts.admin')
@section('title', 'Admin Ban Search | ProGram')
@section('content')
    <main class="px-8 flex flex-col gap-4">
        @include('admin.partials.search-field', ['route' => 'admin.ban.index'])

        <div class="overflow-x-auto flex flex-col gap-4 pb-2">
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
                            <td class="flex justify-end">
                                @if ($ban->isActive())
                                    <div class="dropdown">
                                        @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                        <div class="hidden">
                                            <div>
                                                @include('partials.confirmation-modal', [
                                                    'icon' => 'user-round-x',
                                                    'label' => 'Revoke',
                                                    'message' => 'Are you sure you want to revoke this ban?',
                                                    'type' => 'dropdown',
                                                    'action' => route('admin.ban.revoke', $ban->id),
                                                    'method' => 'POST',
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">No bans found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $bans->onEachSide(0)->links() }}
        </div>
    </main>
@endsection
