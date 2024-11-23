<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
</head>

<body>
    <h1>Admin Login</h1>
    <form action="{{ route('admin.login') }}" method="POST">
        @csrf
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>
        </div>
        <button type="submit">Login</button>
    </form>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>

</html>
