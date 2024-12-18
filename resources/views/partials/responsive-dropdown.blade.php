@props(['label'])

<div class="responsive-dropdown closed w-full h-auto">
    <button class="h-12 px-4 font-medium rounded-lg hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors flex gap-1 items-center">
        More Options
        @include('partials.icon', ['name' => 'chevron-down'])
        @include('partials.icon', ['name' => 'chevron-up'])
    </button>
    <div class="flex flex-col gap-4">
        <section id="search-options" class="card h-min flex flex-col">
            <h1 class="pb-4 text-xl font-semibold">Search Options</h1>
            <div class="grid justify-stretch">
                @include('partials.search-type-button', ['optionType' => 'posts', 'searchType' => request('search_type'), 'icon' => 'message-circle', 'text' => 'Posts'])
                @include('partials.search-type-button', ['optionType' => 'users', 'searchType' => request('search_type'), 'icon' => 'user-round', 'text' => 'Users'])
                @include('partials.search-type-button', ['optionType' => 'groups', 'searchType' => request('search_type'), 'icon' => 'users-round', 'text' => 'Groups'])
            </div>
        </section>

        <section id="search-filters" class="card h-min row-start-2 grid justify-stretch gap-4">
            <h1 class="text-xl font-semibold">Search Parameters</h1>
            @if(request('search_type') === 'posts')
                @include('partials.select', [
                    'name' => 'tags[]',
                    'label' => 'Filter by Tags',
                    'options' => $tagOptions,
                    'multi' => true,
                    'selected' => request('tags'),
                    'form' => 'search-field'
                ])
                @include('partials.select', [
                    'name' => 'search_attr',
                    'label' => 'Search By',
                    'options' => [
                        ['name' => 'All', 'value' => null],
                        ['name' => 'Post Author', 'value' => 'author'],
                        ['name' => 'Post Group', 'value' => 'group'],
                    ],
                    'selected' => request('search_attr'),
                    'form' => 'search-field'
                ])
            @endif
            @switch(request('search_type'))
                @case('posts')
                    @include('partials.select', [
                        'name' => 'order_by',
                        'label' => 'Order By',
                        'options' => [
                            ['name' => 'Relevance', 'value' => null],
                            ['name' => 'Newest', 'value' => 'newest'],
                            ['name' => 'Oldest', 'value' => 'oldest'],
                            ['name' => 'Likes', 'value' => 'likes'],
                            ['name' => 'Comments', 'value' => 'comments'],
                            ['name' => 'Title', 'value' => 'title'],
                        ],
                        'selected' => request('order_by'),
                        'form' => 'search-field'
                    ])
                    @break

                @case('users')
                    @include('partials.select', [
                        'name' => 'order_by',
                        'label' => 'Order By',
                        'options' => [
                            ['name' => 'Relevance', 'value' => null],
                            ['name' => 'User Name', 'value' => 'name'],
                            ['name' => 'Followers', 'value' => 'followers'],
                        ],
                        'selected' => request('order_by'),
                        'form' => 'search-field'
                    ])
                    @break

                @case('groups')
                    @include('partials.select', [
                        'name' => 'order_by',
                        'label' => 'Order By',
                        'options' => [
                            ['name' => 'Relevance', 'value' => null],
                            ['name' => 'Group Name', 'value' => 'name'],
                            ['name' => 'Members', 'value' => 'members'],
                        ],
                        'selected' => request('order_by'),
                        'form' => 'search-field'
                    ])
                    @break
            @endswitch

            @include('partials.text-button', [
                'text' => 'Apply Parameters',
                'type' => 'primary',
                'submit' => true,
                'form' => 'search-field'
            ])
        </section>
    </div>
</div>
