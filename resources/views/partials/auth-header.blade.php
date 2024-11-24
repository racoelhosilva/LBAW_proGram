<header class="h-24 px-24 flex gap-2 items-center justify-end">
    @include('partials.icon-button', ['iconName' => 'home', 'id' => 'home-button', 'label' => 'Home', 'type' => 'secondary', 'anchorUrl' => route('home')])
    @include('partials.theme-button')
    @include('partials.icon-button', ['iconName' => 'grip', 'id' => 'more-button', 'label' => 'More', 'type' => 'transparent'])
</header>