<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap" rel="stylesheet">

        <!-- Set dark mode with browser preferences (added to head to avoid FOUC) -->
        <script type="text/javascript">
            document.documentElement.classList.toggle(
                'dark',
                localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
            );

            // This also fixes the Firefox autofocus FOUC
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>

        <!-- Styles & Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/header.js', 'resources/js/search.js', 'resources/js/user.js', 'resources/js/post.js', 'resources/js/comment.js'])
    </head>
    <body class="bg-white dark:bg-slate-800 text-black dark:text-white">
        @include('partials.header')
        @yield('content')
        @include('partials.footer')
        @include('partials.toast-messages')
    </body>
</html>