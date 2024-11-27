<header class="h-24 px-24 grid grid-cols-[1fr_auto] items-center justify-end">
    <div class="flex">
        @include('admin.partials.logo')
    </div>

    <div class="inline-flex gap-2 items-center">
        @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('home')])
        @include('partials.icon-button', ['iconName' => 'layout-dashboard', 'id' => 'dashboard-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('admin.dashboard')])
        @include('partials.theme-button')
        <article class="dropdown">
            @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', ['icon' => 'user-round', 'text' => 'View Users', 'anchorUrl' => route('admin.user.index')])
                    @include('partials.dropdown-item', ['icon' => 'user-round-x', 'text' => 'View Bans', 'anchorUrl' => route('admin.ban.index')])
                    @include('partials.dropdown-item', ['icon' => 'message-circle', 'text' => 'View Posts', 'anchorUrl' => route('admin.post.index')])
                </div>
                <div>
                    <form method="post" action="{{ route('admin.logout') }}" class="flex flex-col">
                        @csrf
                        @include('partials.dropdown-item', ['icon' => 'log-out', 'text' => 'Logout', 'submit' => true])
                    </form>
                </div>
            </div>
        </article>
    </div>
</header>