@props(['label', 'message', 'action', 'method', 'type', 'icon', 'class' => ''])

<div class="modal flex flex-col">
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
        <section class="space-y-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">{{ $label }}?</h1>
                @include('partials.icon-button', [
                    'iconName' => 'x',
                    'class' => 'close-button',
                    'label' => 'Close',
                    'type' => 'transparent',
                ])
            </div>
            {{ $message }}

            <div class="grid grid-cols-2 gap-4">
                @include('partials.text-button', [
                    'text' => 'Cancel',
                    'type' => 'secondary',
                    'class' => 'close-button',
                ])
                @isset($action)
                    <form method="post" action="{{ $action }}" class="flex flex-col">
                        @csrf
                        @method($method)
                        @include('partials.text-button', [
                            'text' => 'Continue',
                            'type' => 'danger',
                            'submit' => true,
                            'class' => $class,
                        ])
                    </form>
                @else
                    @include('partials.text-button', [
                        'text' => 'Continue',
                        'type' => 'danger',
                        'submit' => true,
                        'class' => 'close-button ' . $class,
                    ])
                @endisset
            </div>
        </section>
    </div>
</div>
