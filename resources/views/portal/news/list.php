@section('subtitle', "Hírek, események - ")
@extends('portal')
@featuredTitle('Hírek, események')
<div class="container inner">
    <div>
        @foreach($news as $new)
        <a href="{{ $new->getUrl() }}" class="spiritual-movement-row">
            <div class="card mb-3 shadow rounded">
                <div class="no-gutters row">
                    <div class="p-2 align-middle text-center">
                        <img @lazySrc() data-src="{{ $new->header_image }}"
                             data-srcset="{{ $new->header_image }}"
                             alt="{{ $new->name }}"
                             class="align-middle lazy"
                             style="width:200px; height: 200px; object-fit: cover; aspect-ratio: 1 / 1;"
                        >
                    </div>
                    <div class="p-3 flex-grow-1 text-center text-md-left col">
                        <h3><b class="card-title">{{ $new->title }}</b></h3>
                        <div class="text-muted
                            d-flex
                            justify-content-between
                            align-items-center
                            flex-column flex-md-row
                            mb-3
                        ">
                            <span style="font-size: 14px">{{ carbon($new->created_at)->format('Y. m. d.') }}</span>
                        </div>
                        <div class="card-text" style="font-size: 16px">{{ $new->excerpt(40, '...') }}</div>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-5">
        {{ $news->renderSmallPager() }}
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
