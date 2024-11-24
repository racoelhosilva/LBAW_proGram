<header class="h-24 px-24 grid grid-cols-[1fr_auto] items-center justify-end">
    <div class="inline-flex gap-8 items-center">  {{-- Left elements --}}
        @include('partials.logo')
        @include('partials.search-field')  {{-- To prevent search field from expanding all available space --}}
    </div>
    <div class="inline-flex gap-2 items-center">  {{-- Right elements --}}
        @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('home')])
        @include('partials.theme-button')
        @if (Auth::check())
            <a href="{{ route('home') }}">
                <img src="/img/placeholder.png" alt="Profile photo" class="h-[49.5px] w-[49.5px] rounded-full">
            </a>
        @else
            @include('partials.text-button', ['text' => 'Login/Register', 'id' => 'login-button', 'type' => 'primary', 'anchorUrl' => route('login')])
        @endif
        <div class="dropdown">
            @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @if (Auth::check())
                        @include('partials.dropdown-item', ['icon' => 'log-out', 'text' => 'Logout', 'anchorUrl' => route('logout')])
                    @else
                        @include('partials.dropdown-item', ['icon' => 'log-in', 'text' => 'Login', 'anchorUrl' => route('login')])
                        @include('partials.dropdown-item', ['icon' => 'user-round-plus', 'text' => 'Register', 'anchorUrl' => route('register')])
                    @endif
                </div>
            </div>
        </div>
    </div>
</header>