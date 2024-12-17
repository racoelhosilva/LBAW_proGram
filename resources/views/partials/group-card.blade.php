@props(['group'])

<article class="card px-6">
    <h1 class="font-bold text-xl">{{ $group->name }}</h1>
    <p class="text-pretty break-words">{{ $group->description }}</p>
</article>