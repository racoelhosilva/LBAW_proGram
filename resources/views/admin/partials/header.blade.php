<header class="h-24 px-8 lg:px-24 grid grid-cols-[auto_1fr_auto] items-center justify-end">
    @include('admin.partials.logo')

    <div class="col-start-3 flex gap-2 items-center">
        <div class="hidden sm:flex">
            @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('home')])
        </div>
        <div class="hidden sm:flex">
            @include('partials.icon-button', ['iconName' => 'layout-dashboard', 'id' => 'dashboard-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('admin.dashboard')])
        </div>
        @include('partials.theme-button')
        <div class="dropdown">
            @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
            <div class="hidden">
                <div>
                    @include('partials.dropdown-item', ['icon' => 'user-round', 'text' => 'View Users', 'anchorUrl' => route('admin.user.index')])
                    @include('partials.dropdown-item', ['icon' => 'user-round-x', 'text' => 'View Bans', 'anchorUrl' => route('admin.ban.index')])
                    @include('partials.dropdown-item', ['icon' => 'message-circle', 'text' => 'View Posts', 'anchorUrl' => route('admin.post.index')])
                    @include('partials.dropdown-item', ['icon' => 'hash', 'text' => 'View Tags', 'anchorUrl' => route('admin.tag.index')])
                    @include('partials.dropdown-item', ['icon' => 'code', 'text' => 'View Languages', 'anchorUrl' => route('admin.language.index')])
                    @include('partials.dropdown-item', ['icon' => 'layers', 'text' => 'View Technologies', 'anchorUrl' => route('admin.technology.index')])
                </div>
                <div class="flex sm:!hidden">
                    @include('partials.dropdown-item', ['icon' => 'home', 'text' => 'View Home', 'anchorUrl' => route('home')])
                </div>
                <div>
                    <form method="post" action="{{ route('admin.logout') }}" class="flex flex-col">
                        @csrf
                        @include('partials.dropdown-item', ['icon' => 'log-out', 'text' => 'Logout', 'submit' => true])
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
