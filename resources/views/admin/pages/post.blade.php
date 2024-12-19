@extends('layouts.admin')
@section('title') {{'Admin Post Search | ProGram'}} @endsection
@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('admin.partials.search-field', ['route' => 'admin.post.index'])

        <div class="overflow-x-auto">
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
                                        @include('partials.dropdown-item', [
                                            'icon' => 'message-circle',
                                            'text' => 'View Post',
                                            'anchorUrl' => route('post.show', $post->id),
                                        ])
                                        <form method="post" action="{{ route('post.destroy', $post->id) }}" class="flex flex-col">
                                            @csrf
                                            @method('DELETE')
                                            @include('partials.dropdown-item', [
                                                'icon' => 'message-circle-x',
                                                'text' => 'Delete Post',
                                            ])
                                        </form>
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
        </div>
        {{ $posts->onEachSide(0)->links() }}
    </main>
@endsection
