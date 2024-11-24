@props(['userCount'])

@extends('layouts.admin')

@section('content')
    <main class="px-4">
        <h1 class="mb-4 text-2xl font-bold col-span-4">Dashboard</h1>
        
        <section class="grid grid-cols-4 gap-4">
            <article class="card grid grid-cols-[auto_1fr] items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                <div class="w-full flex flex-col">
                    <h2 class="text-xl font-bold">Users</h2>
                    <p class="mb-4"><span class="font-semibold">Number of users:</span> {{ $userCount }}</p>
                    <div class="flex">
                        @include('partials.text-button', ['text' => 'View Users', 'anchorUrl' => route('admin.user.search')])
                    </div>
                </div>
            </article>

            <form action="/admin/ban" method="GET">
                <button type="submit">List Bans</button>
            </form>

            <form action="{{ route('admin.logout') }}" method="GET">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </section>
    </main>
@endsection
