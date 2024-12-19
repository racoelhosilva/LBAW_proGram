@extends('layouts.admin')
@section('title') {{'Admin Post Search | ProGram'}} @endsection
@section('content')
    <main class="px-8 flex flex-col gap-4">
        @include('admin.partials.search-field', ['route' => 'admin.post.index'])

        <div class="overflow-x-auto flex flex-col gap-4 pb-8">
            <table>
                <thead class="text-center">
                    <tr>
                        <th>ID</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr class="border-t border-white">
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->author->name }}</td>
                            <td>{{ $post->title }}</td>
                            <td class="flex justify-end">
                                <div class="dropdown">
                                    @include('partials.icon-button', ['iconName' => 'ellipsis', 'label' => 'Options', 'type' => 'transparent'])
                                    <div class="hidden">
                                        <div>
                                            @include('partials.dropdown-item', [
                                                'icon' => 'message-circle',
                                                'text' => 'View Post',
                                                'anchorUrl' => route('post.show', $post->id),
                                            ])
                                            @include('partials.confirmation-modal', [
                                                'label' => 'Delete Post',
                                                'icon' => 'trash',
                                                'message' => 'Are you sure you want to delete this post? This action cannot be undone!',
                                                'type' => 'dropdown',
                                                'action' => route('admin.post.destroy', $post->id),
                                                'method' => 'DELETE'
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No posts found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $posts->onEachSide(0)->links() }}
        </div>
    </main>
@endsection
