<header class="h-16 px-24 grid grid-cols-[1fr_auto] items-end justify-end">
    <div class="inline-flex gap-8 items-center">  {{-- Left elements --}}
        <a href="{{ route('home') }}">
            <img src="{{ url('svg/logo-large-black.svg') }}" alt="Logo" class="h-12 dark:hidden">
            <img src="{{ url('svg/logo-large-white.svg') }}" alt="Logo" class="h-12 hidden dark:block">
        </a>
        @include('partials.search-field')  {{-- To prevent search field from expanding all available space --}}
    </div>
    <div class="inline-flex gap-2 items-center">  {{-- Right elements --}}
        @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('home')])
        @include('partials.theme-button')
        @include('partials.text-button', ['text' => 'Login/Register', 'id' => 'login-button', 'type' => 'primary', 'anchorUrl' => route('login')])
        @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
    </div>
</header>