@props(['error', 'message'])

<article class="h-min p-10 pt-16 grid gap-6 justify-items-center col-start-2">
    <h1 class="text-9xl font-bold text-center">
        {{ $number }}
    </h1>
    <h2 class="text-3xl font-bold text-center text-blue-600 dark:text-blue-400">
        {{ $error }}
    </h2>
    <h3 class="text-xl text-center">
        {{ $message }}
    </h3>
    @include('partials.text-button', ['anchorUrl' => route('home'), 'text' => 'Return to Home Page'])
</article>
