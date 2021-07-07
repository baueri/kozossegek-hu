@section('header')
    <link rel="canonical" href="{{ $page->getUrl() }}" />
    <meta name="description" content="{{ $page->excerpt() }}" />
@endsection
@section('subtitle', $page->title . ' | ')
@section('header_content')
    @if($header_background)
        @featuredTitle($page_title)
    @endif
@endsection
@extends('portal')
<div class="container inner p-4 page">
    @if(!$header_background)<h1>{{ $page_title }}</h1>@endif
    <div>
        {{ $page->content }}
    </div>
</div>
