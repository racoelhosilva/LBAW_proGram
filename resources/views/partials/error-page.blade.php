@props(['error', 'message'])

<article class="h-min p-10 pt-16 grid gap-12 justify-items-center col-start-2">
    @include('partials.logo', ['size' => 'large'])
    <h1 class="text-3xl font-bold text-center">
        {{ $error . ' - ' . $message }}
    </h1>
</article>
