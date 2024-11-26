@extends('layouts.admin')

@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('partials.admin-search-field', ['route' => 'admin.post.search'])

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
                        <td><a href="/api/user/{{ $post->author->id }}">{{ $post->author->name }}</a></td>
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
