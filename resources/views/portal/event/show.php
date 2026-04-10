@section('header')
    <meta name="description" content="{{ $event->name }}" />
    <meta property="og:title" content="{{ $event->name }}" />
    <meta property="og:description" content="{{ str_more($event->description, 120) }}" />
    <meta property="og:image" content="{{ $event->featured_image }}" />
    @og_image($event->getFeaturedImageUrl())
    <script type="application/ld+json">{{ $eventSchemaJsonLd }}</script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" @preload_css() />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@extends('portal2026.portal')
@featuredTitle()
<h1 class="group-title-modern">
    {{ $event->name }}
</h1>

<div class="group-institute">
    @icon('calendar-alt')
    {{ $event->getScheduleRangeLabel() }}
</div>
@endfeaturedTitle

<div class="group-hero-modern mb-5">
    <div class="container">

        <div class="group-header-grid">

            <div class="group-image-side">

                <div class="community-image{{ $event->isCancelled() ? ' community-image--cancelled' : '' }}">
                    <img src="{{ $event->getFeaturedImageUrl() }}" alt="{{ $event->name }}">

                    @if($event->isCancelled())
                    <div class="event-card-cancelled-overlay" aria-hidden="true">
                        <span class="event-card-cancelled-overlay__band"></span>
                        <span class="event-card-cancelled-overlay__text">@lang('event_life_cycle.cancelled')</span>
                    </div>
                    @endif
                </div>

                <div class="group-highlight-card side shadow">

                    <div class="highlight-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <small>Helyszín</small>
                            <strong>{{ $event->location_name }}</strong>
                        </div>
                    </div>

                    @if($event->address)
                    <div class="highlight-item">
                        <i class="fas fa-road"></i>
                        <div>
                            <small>Cím</small>
                            <strong>{{ $event->address }}</strong>
                        </div>
                    </div>
                    @endif

                    <div class="highlight-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <small>Szervező</small>
                            <strong>{{ $event->organizer }}</strong>
                        </div>
                    </div>

                </div>

            </div>
            <div class="group-header-single">

                @if($event->description)
                <div class="group-card shadow">
                    <h3>Esemény leírása</h3>
                    <p>{{ $event->description }}</p>
                </div>
                @endif

                <a href="@route('event.ics', ['event' => $event->id])" class="btn btn-contact">
                    <i class="fas fa-calendar-plus"></i>
                    Hozzáadás naptárhoz
                </a>

                @if($event->lat && $event->lng)
                <div class="group-card shadow mt-3 map-wrapper">

                    <p class="text-center mb-2">
                        <a href="#" class="btn btn-outline-orange toggle-map">
                            @icon('map') Térkép
                        </a>
                    </p>

                    <div class="group-map" style="display:none;">
                        <div id="map"></div>
                    </div>

                </div>
                @endif

            </div>

        </div>

    </div>
</div>

<script>
    $(() => {

    let mapInitialized = false;
    let mapInstance = null;

    $(".toggle-map").click(function(e) {
        e.preventDefault();

        const mapContainer = $(".group-map");

        mapContainer.slideToggle(200);

        setTimeout(() => {

            if (typeof L === "undefined") {
                console.error("Leaflet nincs betöltve");
                return;
            }

            if (!mapInitialized) {

                const lat = "{{ (float) $event->lat }}";
                const lng = "{{ (float) $event->lng }}";

                const map = L.map('map');
                mapInstance = map;

                // Clear default Leaflet prefix (1.8+ includes UA flag SVG); OSM tile attribution stays.
                map.attributionControl.setPrefix('');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                    .addTo(map);

                const marker = L.marker([lat, lng]).addTo(map);
                marker.bindPopup("<strong>{{ $event->name }}</strong><br>{{ $event->location_name }}");

                map.setView([lat, lng], 13);

                mapInitialized = true;
            }

            // 🔥 EZ A LÉNYEG
            if (mapInstance) {
                mapInstance.invalidateSize();
            }

        }, 250); // megvárjuk az animációt
    });

});
</script>
<style>
.group-image-side .community-image {
    position: relative;
    display: block;
    overflow: hidden;
    border-radius: 1.5rem;
}

.group-image-side .community-image img {
    width: 100%;
    display: block;
}

.group-image-side .community-image.community-image--cancelled img {
    filter: grayscale(1);
    opacity: 0.55;
}

.group-image-side .event-card-cancelled-overlay {
    position: absolute;
    inset: 0;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.group-image-side .event-card-cancelled-overlay__band {
    position: absolute;
    left: -18%;
    width: 136%;
    height: 3.35rem;
    top: 50%;
    margin-top: -1.675rem;
    background: rgba(185, 28, 28, 0.92);
    transform: rotate(-14deg);
    box-shadow: 0 2px 14px rgba(0, 0, 0, 0.2);
}

.group-image-side .event-card-cancelled-overlay__text {
    position: relative;
    z-index: 1;
    color: #fff;
    font-weight: 800;
    font-size: 1.15rem;
    line-height: 1.35;
    letter-spacing: 0.06em;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.35);
    padding: 0.45rem 1rem;
}

#map {
    height: 400px;
    width: 100%;
}
</style>