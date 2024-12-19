@props(['label', 'text', 'action', 'method', 'type', 'icon'])

<div class="modal ban-modal">
    @switch($type)
        @case('button')
            @include('partials.text-button', [
                'text' => $label,
                'class' => 'open-button',
                'type' => 'danger',
            ])
            @break
        @case('dropdown')
            @include('partials.dropdown-item', [
                'icon' => $icon,
                'text' => $label,
                'class' => 'open-button',
            ])
    @endswitch

    <div>
        <div>
            <section>
                <div class="mb-4 flex justify-between items-center">
                    <h1 class="text-2xl font-bold">{{ $label }}?</h1>
                    @include('partials.icon-button', [
                        'iconName' => 'x',
                        'class' => 'close-button',
                        'label' => 'Close',
                        'type' => 'transparent',
                    ])
                </div>
                {{ $text }}

                <div class="grid grid-cols-2">
                    @include('partials.text-button', [
                        'text' => 'Cancel',
                        'type' => 'primary',
                        'class' => 'close-button',
                    ])
                    <form method="{{ $method }}" action="{{ $action }}" class="flex">
                        @csrf
                        @include('partials.text-button', [
                            'text' => 'Continue',
                            'type' => 'danger',
                            'submit' => true,
                        ])
                    </form>
                </div>
            </section>
        </div>
    </div>
</div>
