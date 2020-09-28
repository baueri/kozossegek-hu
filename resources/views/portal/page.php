@extends('portal')

<div class="container p-4 page">
    <h2 class="mb-4">{{ $page->title }}</h2>
    <div>
        {{ $page->content }}
    </div>
</div>