<button id="theme-button" aria-label="Light/Dark Mode" class="p-3 secondary-btn">
    <span class="dark:hidden">@include('partials.icon', ['name' => 'moon'])</span>
    <span class="hidden dark:block">@include('partials.icon', ['name' => 'sun'])</span>
</button>