@props(['user', 'class' => '', 'buttons' => null])

@php($groupUrl = route('group.show', $group->id))

<article class="card px-6 flex flex-col sm:flex-row justify-between w-full {{ $class }}">
    <div class="flex items-center">
 
        <div>
            <p class="text-base/4 font-medium"><a href="{{ $groupUrl }}">{{ $group->name}}</a></p>
        </div>
    </div>
    @if($buttons)
    <div class="flex gap-x-4 justify-end">
             {!! $buttons !!}
    </div>
    @endif
</article>
