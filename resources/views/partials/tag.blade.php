@props(['tag', 'small' => false])

<a href="{{ route('search', ['search_type' => 'posts', 'tags[]' => $tag->id]) }}" class="{{ $small ? 'text-sm' : 'text-base' }} font-medium text-blue-600 dark:text-blue-400 select-none">{{ '#' . $tag->name }}</a>
