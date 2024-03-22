@section('subtitle', $page->title . ' - ')
@extends('portal')
@featuredTitle()
<h3 class="py-3 mb-0">{{ $page->title }}</h3>
@endfeaturedTitle
<div class="container inner">
    <img src="{{ $page->header_image }}" alt="{{ $page->title }}" style="width: 100%; height: 300px; object-fit: cover" class="mb-4">
    {{ $page->content }}
</div>
