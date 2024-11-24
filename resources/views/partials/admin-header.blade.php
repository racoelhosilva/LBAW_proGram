<header class="h-24 px-24 grid grid-cols-[1fr_auto] items-center justify-end">
    @include('partials.admin-logo')

    <div class="inline-flex gap-2 items-center">
        @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('admin.dashboard')])
        @include('partials.theme-button')
        @include('partials.icon-button', ['iconName' => 'log-out', 'id' => 'logout-button', 'label' => 'Logout', 'type' => 'secondary', 'anchorUrl' => route('admin.logout')])
        @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
    </div>
</header>