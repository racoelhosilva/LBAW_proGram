<div>
    <p>Dashboard</p>
    <form action="{{ route('admin.logout') }}" method="GET">
        @csrf
        <button type="submit">Logout</button>
    </form>
</div>
