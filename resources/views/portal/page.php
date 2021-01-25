@section('header_content')
    @featuredTitle($page_title)
@endsection
@extends('portal')
<div class="container inner p-4 page">
    <div>
        {{ $page->content }}
    </div>
</div>
