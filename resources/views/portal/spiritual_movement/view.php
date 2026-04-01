@header()
    @og_image($spiritualMovement->image_url)
@endheader

@section('subtitle', $spiritualMovement->name . ' - ')
@extends('portal2026.portal')

@featuredTitle()
<section class="movement-hero">

    <div class="container">

        {{ $spiritualMovement->getBreadcrumb() }}

        <div class="movement-hero-inner">

            <div class="movement-hero-image">
                <img src="{{ $spiritualMovement->image_url }}"
                     alt="{{ $spiritualMovement->name }}">
            </div>

            <div class="movement-hero-content">

                <h1 class="movement-title">
                    {{ $title }}
                </h1>

                @if($spiritualMovement->website)
                    <a href="{{ $spiritualMovement->website }}"
                       target="_blank"
                       class="movement-website">
                        Weboldal megnyitása
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                @endif

            </div>

        </div>

    </div>

</section>
@endfeaturedTitle

<div class="container inner">

    <div class="movement-description">
        {{ $spiritualMovement->description }}
    </div>

    @if($groups && $groups->isNotEmpty())

        <h2 class="movement-section-title text-center">
            A(z) {{ $spiritualMovement->name }} közösségei:
        </h2>

        <div class="row" id="kozossegek-list">
            @foreach($groups as $i => $group)
                <div class="col-xl-4 col-md-6 mb-3">
                    @include('portal.partials.kozosseg_card', ['group' => $group])
                </div>
            @endforeach
        </div>

    @endif

</div>

<style>
/* HERO */
.movement-hero {
    padding: 40px 0 20px;
}

.movement-hero-inner {
    display: flex;
    align-items: center;
    gap: 30px;
    margin-top: 20px;
}

.movement-hero-image {
    width: 140px;
    height: 140px;
    border-radius: 1.5rem;
    overflow: hidden;
    background: #f1f5f9;
    flex-shrink: 0;
}

.movement-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.movement-title {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    margin-bottom: 10px;
}

.movement-website {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--orange);
    text-decoration: none;
}

.movement-website:hover {
    gap: 10px;
}

/* DESCRIPTION */
.movement-description {
    max-width: 800px;
    margin: 30px auto;
    color: #475569;
    line-height: 1.7;
    font-size: 1rem;
}

/* SECTION TITLE */
.movement-section-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    margin: 50px 0 30px;
}

/* GRID */
#kozossegek-list {
    margin-top: 10px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .movement-hero-inner {
        flex-direction: column;
        text-align: center;
    }

    .movement-hero-image {
        width: 100px;
        height: 100px;
    }
}
</style>