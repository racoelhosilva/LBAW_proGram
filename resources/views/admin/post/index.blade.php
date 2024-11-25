@extends('layouts.admin')

@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('partials.admin-search-field', ['route' => 'admin.post.search'])

        <table class="mt-4">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr class="border-t border-white">
                        <td>{{ $post->id }}</td>
                        <td><a href="/api/user/{{ $post->author->id }}">{{ $post->author->name }}</a></td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->text }}</td>
                        <td>
                            <form action="{{ route('post.destroy', $post->id) }}" method="POST">
                                @csrf
																@method("DELETE")
                                <button type="submit">Delete</button>
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
