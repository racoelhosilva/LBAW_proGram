@props(['provider'])

@php
    switch ($provider) {
        case 'google':
            $svgPath = '/svg/google-icon-logo-svgrepo-com.svg';
            $alt = 'Google logo';
            $label = 'Sign in with Google';
            $route = route('google.auth');
            break;

        case 'github':
            $svgPath = '/svg/github-142-svgrepo-com.svg';
            $alt = 'GitHub logo';
            $label = 'Sign in with GitHub';
            $route = route('github.auth');
            break;

        case 'gitlab':
            $svgPath = '/svg/gitlab-svgrepo-com.svg';
            $alt = 'GitLab logo';
            $label = 'Sign in with GitLab';
            $route = route('gitlab.auth');
            break;
    }
@endphp

<div class="oauth-button flex justify-center">
    <a href="{{ $route }}" aria-label="{{ $label }}" class="h-12 w-full bg-white border border-slate-300 rounded-lg flex items-center justify-center">
        <img src="{{ url($svgPath) }}" alt="{{ $alt }}" class="h-8 w-8">
    </a>
    @include('partials.loading-spinner', ['hidden' => true])
</div>