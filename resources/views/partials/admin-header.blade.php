<header class="h-24 px-24 grid grid-cols-[1fr_auto] items-center justify-end">
    <div class="flex">
        @include('partials.admin-logo')
    </div>

    <div class="inline-flex gap-2 items-center">
        @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('admin.dashboard')])
        @include('partials.theme-button')
        <article class="dropdown">
            @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', ['icon' => 'user-round', 'text' => 'View Users', 'anchorUrl' => route('admin.user.search')])
                    @include('partials.dropdown-item', ['icon' => 'user-round-x', 'text' => 'View Bans', 'anchorUrl' => route('admin.ban.search')])
                    @include('partials.dropdown-item', ['icon' => 'message-circle', 'text' => 'View Posts', 'anchorUrl' => route('admin.post.search')])
                </div>
                <div>
                    @include('partials.dropdown-item', ['icon' => 'log-out', 'text' => 'Logout', 'anchorUrl' => route('admin.logout')])
                </div>
            </div>
        </article>
    </div>
</header>
