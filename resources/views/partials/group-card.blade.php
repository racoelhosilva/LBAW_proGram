@props(['user', 'class' => '', 'buttons' => null])

@php($groupUrl = route('group.show', $group->id))

<article class="card px-6 flex flex-col sm:flex-row justify-between w-full min-h-20 {{ $class }}">
    <div class="flex items-center">
 
        <div>
            <p class="text-base/4 font-medium"><a href="{{ $groupUrl }}">{{ $group->name}}</a></p>
            <p class="text-xs/3 mt-1 font-medium text-gray-500 dark:text-gray-400 select-none">
                <a href="{{ $groupUrl }}">{{ \Illuminate\Support\Str::words($group->description, 10, '...') }}</a>
                
        </div>
    </div>
    @if($buttons)
    <div class="flex  justify-end group-buttons-container " data-group-id={{$group->id}}>
             {!! $buttons !!}
    </div>
    @endif
</article>
