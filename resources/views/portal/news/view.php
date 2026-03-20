@section('header')
    <link rel="canonical" href="{{ $entry->getUrl() }}" />
    <meta name="description" content="{{ $entry->excerpt() }}" />
    @if($entry->header_image)
        @og_image($entry->featuredImageUrl())
    @endif
@endsection
@section('subtitle', $entry->title . ' - ')
@extends('portal')
@featuredTitle('Hírek')
<div class="container inner">
    <img src="{{ $entry->header_image }}" alt="{{ $entry->title }}" style="width: 100%; object-fit: cover" class="mb-4">
    <h1 class="mb-2">
        <b>{{ $entry->title }}</b>
    </h1>
    <div class="mb-4">
        <span class="text-muted" style="font-size: 14px">{{ carbon($entry->created_at)->format('Y. m. d.') }}</span>
    </div>
    {{ $entry->content }}
</div>
