@section('header_content')
    @featuredTitle($page->title)
@endsection
@extends('portal')
<div class="container p-4 page">
    
    <div>
        {{ $page->content }}
    </div>
</div>