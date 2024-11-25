@extends('layouts.admin')

@section('content')
    <main class="px-4 flex flex-col gap-4">
        @include('partials.admin-search-field', ['route' => 'admin.user.search'])

        <table class="mt-4">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Descrition</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr class="border-t border-white">
                        <td>{{ $post->id }}</td>
                        <td><a href="/api/user/{{ $post->author->id }}">{{ $post->author->name }}</a></td>
                        <td>{{ $post->title }}</td>
                        <td>{{ $post->text }}</td>
                        <!-- <td> -->
                        <!--     <form action="{{-- route('admin.ban.store', $post->author->id) --}}" method="POST"> -->
                        <!--         @csrf -->
                        <!--         <input type="text" name="reason" placeholder="Reason for ban" required> -->
                        <!--         <input type="number" name="duration" placeholder="Duration (days)" required> -->
                        <!--         <button type="submit">Ban</button> -->
                        <!--     </form> -->
                        <!-- </td> -->
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
