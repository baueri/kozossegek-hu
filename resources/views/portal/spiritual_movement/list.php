@header()
@og_image()
@endheader

@section('subtitle', "{$title} - ")

@extends('portal')

@featuredTitle($title)

<div class="container inner">
    <div class="text-center fst-italic mb-5">
        {{ $description }}
    </div>
    <div>
        @foreach($spiritualMovements as $spiritualMovement)
        <a href="{{ $spiritualMovement->getUrl() }}" class="movement-card">
            <div class="movement-inner">
                <div class="movement-image">
                    <img src="{{ $spiritualMovement->image_url }}"
                        alt="{{ $spiritualMovement->name }}">
                </div>
                <div class="movement-content">
                    <h3 class="movement-title">
                        {{ $spiritualMovement->name }}
                    </h3>
                    <p class="movement-text">
                        {{ $spiritualMovement->excerpt() }}
                    </p>
                    <div class="movement-footer">
                        <span class="movement-count">
                            {{ $spiritualMovement->groups_count ?? 0 }} közösség
                        </span>
                        <span class="movement-link">
                            Megnézem
                            <span class="arrow-icon">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 12H19M19 12L13 6M19 12L13 18"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

</div>

<style>/* CARD */
.movement-card {
    display: block;
    background: #fff;
    border-radius: 1.5rem;
    margin-bottom: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    transition: all 0.25s ease;
    text-decoration: none;
    color: inherit;
}

.movement-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 25px 60px rgba(0,0,0,0.1);
    text-decoration: none;
}

/* INNER */
.movement-inner {
    display: flex;
    align-items: center;
    padding: 16px;
    gap: 16px;
}

/* IMAGE */
.movement-image {
    width: 90px;
    height: 90px;
    flex-shrink: 0;
    border-radius: 1rem;
    overflow: hidden;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.movement-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

/* CONTENT */
.movement-content {
    flex: 1;
}

/* TITLE */
.movement-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem;
    margin-bottom: 6px;
    color: #0f172a;
}

/* TEXT */
.movement-text {
    font-size: 0.9rem;
    color: #64748b;
    margin-bottom: 10px;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* FOOTER */
.movement-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* COUNT */
.movement-count {
    font-size: 0.8rem;
    color: #94a3b8;
}

/* LINK */
.movement-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--orange);
    font-weight: 500;
}

/* ARROW */
.arrow-icon {
    transition: transform 0.25s ease;
}

.movement-card:hover .arrow-icon {
    transform: translateX(4px);
}
</style>