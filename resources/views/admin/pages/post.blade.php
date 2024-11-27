@extends('layouts.admin')
@section('title') {{'Admin Post Search | ProGram'}} @endsection
@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('admin.partials.search-field', ['route' => 'admin.post.index'])

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
                        <td class="pe-8 flex justify-end gap-2">
                            @include('partials.text-button', [
                                'text' => 'View Post',
                                'type' => 'secondary',
                                'anchorUrl' => route('post.show', $post->id),
                            ])
                            <form method="post" action="{{ route('post.destroy', $post->id) }}">
                                @csrf
                                @method('DELETE')
                                @include('partials.text-button', [
                                    'text' => 'Delete',
                                    'type' => 'secondary',
                                    'submit' => true,
                                ])
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No posts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $posts->links() }}
    </main>
@endsection
