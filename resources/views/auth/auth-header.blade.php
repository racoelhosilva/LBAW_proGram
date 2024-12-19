<header class="h-24 px-8 lg:px-24 flex gap-2 items-center justify-end">
    <div class="hidden xl:flex">
        @include('partials.icon-button', [
            'iconName' => 'home',
            'id' => 'home-button',
            'label' => 'Home',
            'type' => 'secondary',
            'anchorUrl' => route('home'),
        ])
    </div>
    @include('partials.theme-button')
    <article class="dropdown">
        @include('partials.icon-button', [
            'iconName' => 'grip',
            'id' => 'more-button',
            'label' => 'More',
            'type' => 'transparent',
        ])
        <div class="hidden">
            <div>
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
            </div>
            <div>
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
                    'anchorUrl' => route('contact-us'),
                ])
            </div>
        </div>
    </article>
</header>