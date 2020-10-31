@extends('portal')
@featuredTitle($page->title)
<div class="container p-4 page">
    
    <div>
        {{ $page->content }}
    </div>
</div>