@title('Bejegyzések')
@section('title')
    @include('admin.page.title-bar')
@endsection
@extends('admin')

<a href="@route('admin.page.create', ['page_type' => $page_type])" class="btn btn-primary btn-sm mb-2">@icon('plus') Új bejegyzés</a>

{{ $table }}
