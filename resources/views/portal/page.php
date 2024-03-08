@section('header')
    <link rel="canonical" href="{{ $page->getUrl() }}" />
    <meta name="description" content="{{ $page->excerpt() }}" />
@endsection
@section('subtitle', $page->title . ' | ')
@extends('portal')
@featuredTitle($page_title)
<div class="container inner page">
    @if(!$header_background)<h1>{{ $page_title }}</h1>@endif
    <div>
        {{ $page->content }}
    </div>
</div>
