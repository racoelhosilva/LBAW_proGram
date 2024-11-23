<div>
    <p>Dashboard</p>
    <form action="{{ route('admin.users.search') }}" method="GET">
        <button type="submit">Search Users</button>
    </form>

    <form action="{{ route('admin.logout') }}" method="GET">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
