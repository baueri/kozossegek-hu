@section('subtitle', "{$title} - ")
@extends('portal')
@featuredTitle($title)
<div class="container inner">
    <div class="text-center font-italic mb-5">
        {{ $description }}
    </div>
    <div>
        @foreach($spiritualMovements as $spiritualMovement)
        <a href="{{ $spiritualMovement->getUrl() }}" class="spiritual-movement-row">
            <div class="card mb-3 shadow rounded">
                <div class="no-gutters row">
                    <div class="p-2 align-middle text-center col-12 col-md-4 col-lg-2">
                        <img src="{{ $spiritualMovement->image_url }}" alt="{{ $spiritualMovement->name }}" class="align-middle" style="max-width: 300px; width: 100%; height: 100%; object-fit: contain">
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
