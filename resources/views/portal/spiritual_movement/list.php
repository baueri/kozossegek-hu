@section('subtitle', "{$title} - ")
@section('header_content')
    @featuredTitle($title)
@endsection
@extends('portal')
<div class="container inner">
    <div class="text-center text-muted font-italic mb-5">
        {{ $description }}
    </div>
    <div>
        @foreach($spiritualMovements as $spiritualMovement)
        <a href="{{ $spiritualMovement->getUrl() }}" class="spiritual-movement-row">
            <div class="card mb-3 shadow-sm rounded">
                <div class="no-gutters row">
                    <div class="p-2 align-middle text-center col-2">
                        <img src="{{ $spiritualMovement->image_url }}" alt="{{ $spiritualMovement->name }}" class="align-middle w-100">
                    </div>
                    <div class="p-3 flex-grow-1 text-center text-md-left col">
                        <h5><b class="card-title">{{ $spiritualMovement->name }}</b></h5>
                        <div class="card-text">{{ $spiritualMovement->excerpt() }}</div>
                        <p class="card-text">
                            <small class="text-muted">Regisztrált közösségek: {{ $spiritualMovement->groups_count ?? 0 }}</small>
                        </p>
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
