<header class="h-24 px-24 grid grid-cols-[auto_1fr_auto] items-center justify-end">
    <div class="inline-flex gap-8 items-center">
        @include('partials.logo')
        @include('partials.search-field')
    </div>
    <div class="col-start-3 inline-flex gap-2 items-center">
        @if (Auth::check())
            @include('partials.text-button', [
                'text' => 'Create Post',
                'id' => 'create-post-button',
                'type' => 'primary',
                'anchorUrl' => route('post.create'),
            ])
        @endif
        @include('partials.icon-button', [
            'iconName' => 'home',
            'id' => 'home-button',
            'label' => 'Home',
            'type' => 'secondary',
            'anchorUrl' => route('home'),
        ])
        @include('partials.theme-button')
        @if (Auth::check())
            <a href="{{ route('user.notifications', auth()->id()) }}" id="notification-button" aria-label="Notification" class="p-3 secondary-btn relative">
                @include('partials.icon', ['name' => 'notification'])
                <span class="{{auth()->user()->notifications->count() == 0 ? "hidden" : ""}} absolute bottom-0 right-0 block text-xs font-bold text-white bg-red-500 rounded-full flex items-center justify-center p-0.5" id="notification-count">
                    {{ auth()->user()->notifications->count() }}
                </span>
            </button>


            <a href="{{ route('user.show', auth()->id()) }}">
                <img src="{{ auth()->user()->getProfilePicture() }}" alt="Profile photo" class="h-[49.5px] w-[49.5px] rounded-full object-cover">
            </a>
        @else
            @include('partials.text-button', [
                'text' => 'Login/Register',
                'id' => 'login-button',
                'type' => 'primary',
                'anchorUrl' => route('login'),
            ])
        @endif
        
        <article class="dropdown">
            @include('partials.icon-button', [
                'iconName' => 'grip',
                'id' => 'more-button',
                'label' => 'More',
                'type' => 'transparent',
            ])
            <div class="hidden">
                <div>
                    @if (Auth::check())
                        <form method="post" action="{{ route('logout') }}" class="flex flex-col">
                            @csrf
                            @include('partials.dropdown-item', ['icon' => 'log-out', 'text' => 'Logout', 'submit' => true])
                        </form>
                    @else
                        @include('partials.dropdown-item', [
                            'icon' => 'log-in',
                            'text' => 'Login',
                            'anchorUrl' => route('login'),
                        ])
                        @include('partials.dropdown-item', [
                            'icon' => 'user-round-plus',
                            'text' => 'Register',
                            'anchorUrl' => route('register'),
                        ])
                    @endif
                    @include('partials.dropdown-item', [
                        'icon' => 'info',
                        'text' => 'About',
                        'anchorUrl' => route('about'),
                    ])
                    @include('partials.dropdown-item', [
                        'icon' => 'message-circle-question',
                        'text' => 'FAQs',
                        'anchorUrl' => route('faqs'),
                    ])
                    @include('partials.dropdown-item', [
                        'icon' => 'mail',
                        'text' => 'Contact Us',
                        'anchorUrl' => route('contactus'),
                    ])
                </div>
            </div>
        </article>
    </div>
</header>
