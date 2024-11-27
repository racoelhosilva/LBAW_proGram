<section id="toast-container" class="fixed bottom-4 right-4 space-y-4">
    <article id="error-toast" class="toast-message {{ ! $errors->any() ? 'hidden' : '' }} p-4 rounded-lg bg-red-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-x'])
        <p>{{ $errors->first() }}</p>
    </article>

    <article id="success-toast" class="toast-message {{ ! session('success') ? 'hidden' : '' }} p-4 rounded-lg bg-green-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
        @include('partials.icon', ['name' => 'circle-check'])
        <p>{{ session('success') }}</p>
    </article>
</section>