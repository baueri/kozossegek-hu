@section('subtitle', "Hírek - ")
@extends('portal')
@featuredTitle('Hírek')
<div class="container inner">
    <div>
        @foreach($news as $new)
        <a href="{{ $new->getUrl() }}" class="spiritual-movement-row">
            <div class="card mb-3 shadow rounded">
                <div class="no-gutters row">
                    <div class="p-2 align-middle text-center col-12 col-md-4 col-lg-3">
                        <img @lazySrc() data-src="{{ $new->header_image }}" data-srcset="{{ $new->header_image }}" alt="{{ $new->name }}" class="align-middle lazy" style="max-width: 300px; width: 100%; height: 100%; object-fit: contain">
                    </div>
                    <div class="p-3 flex-grow-1 text-center text-md-left col">
                        <h5><b class="card-title">{{ $new->title }}</b></h5>
                        <div class="card-text">{{ $new->excerpt() }}</div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<style>
    .spiritual-movement-row {
        transition: color ease-in .1s;
        text-decoration: none !important;
        color: var(--secondary);
    }
    .spiritual-movement-row:hover {
        color: var(--alt-blue) !important;
    }
</style>
