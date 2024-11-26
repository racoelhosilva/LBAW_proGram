<section id="toast-container" class="fixed bottom-4 right-4 space-y-4">
    @if (session('message'))
        <div class="toast-message p-4 rounded-lg bg-blue-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
            @include('partials.icon', ['name' => 'info'])
            {{ session('message') }}
        </div>
    @endif

    @if (session('error'))
        <div class="toast-message p-4 rounded-lg bg-red-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
            @include('partials.icon', ['name' => 'circle-x'])
            {{ session('error') }}
        </div>
    @endif


    @if (session('success'))
        <div class="toast-message p-4 rounded-lg bg-green-500 border-2 border-white text-white font-medium shadow-lg transition-opacity flex gap-3">
            @include('partials.icon', ['name' => 'circle-check'])
            <p>{{ session('success') }}</p>
        </div>
    @endif
</section>