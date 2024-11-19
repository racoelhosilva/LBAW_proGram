<header class="h-16 px-24 grid grid-cols-[auto_1fr_auto_auto_auto_auto] gap-2 items-center justify-end">
    <h1>ProGram</h1>
    <span>@include('partials.search-field')</span>  {{-- To prevent search field from expanding all available space --}}
    @include('partials.icon-button', ['iconName' => 'home', 'label' => 'Home', 'type' => 'secondary'])
    @include('partials.theme-button')
    @include('partials.text-button', ['text' => 'Login/Register', 'type' => 'primary'])
    @include('partials.icon-button', ['iconName' => 'grip', 'label' => 'More', 'type' => 'transparent'])
</header>