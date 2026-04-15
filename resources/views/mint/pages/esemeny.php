<mint-extend path="layout/portal.php" :subtitle="{$event->name . ' | '}" :body-class="group-page">

    <mint-section name="header">
        <meta name="description" content="{{ $event->name }}" />
        <meta property="og:title" content="{{ $event->name }}" />
        <meta property="og:description" content="{{ str_more($event->description, 120) }}" />
        <meta property="og:image" content="{{ $event->getFeaturedImageUrl() }}" />
        <mod-og-image :src="{ $event->getFeaturedImageUrl() }" />
        <script type="application/ld+json">{{ $eventSchemaJsonLd }}</script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    </mint-section>

    <mint-section name="scripts">
        <script>
        $(() => {
            let mapInitialized = false;
            let mapInstance = null;

            $(".toggle-map").click(function(e) {
                e.preventDefault();
                const mapContainer = $(".group-map");
                mapContainer.slideToggle(200);
                setTimeout(() => {
                    if (typeof L === "undefined") return;
                    if (!mapInitialized) {
                        const lat = {{ (float) $event->lat }};
                        const lng = {{ (float) $event->lng }};
                        const map = L.map('map');
                        mapInstance = map;
                        map.attributionControl.setPrefix('');
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                        const marker = L.marker([lat, lng]).addTo(map);
                        marker.bindPopup("<strong>{{ addslashes($event->name) }}</strong><br>{{ addslashes($event->location_name) }}");
                        map.setView([lat, lng], 13);
                        mapInitialized = true;
                    }
                    if (mapInstance) mapInstance.invalidateSize();
                }, 250);
            });
        });
        </script>
    </mint-section>

    <section class="page-header">
        <div class="container">
            <div class="group-tags-modern mb-2">
                <span x:foreach="{ $event->tags as $tag }" class="tag-pill">{{ $tag->tag }}</span>
            </div>
            <h1 class="group-title-modern">{{ $event->name }}</h1>
            <div class="event-hero-meta">
                <span class="event-hero-meta__when">
                    <mod-icon :name="calendar-alt" />
                    <span>{{ $event->getCardScheduleLabel() }}</span>
                </span>
                <div x:if="{ $event->getHeroLocationLabel() }" class="event-hero-meta__where">
                    <span class="event-hero-meta__sep" aria-hidden="true">·</span>
                    <mod-icon :name="map-marker-alt" />
                    <span>{{ $event->getHeroLocationLabel() }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="group-hero-modern mb-5">
        <div class="container">
            <div class="group-header-grid">

                <div class="group-image-side">

                    <div class="community-image{{ $event->isCancelled() ? ' community-image--cancelled' : '' }}">
                        <img src="{{ $event->getFeaturedImageUrl() }}" alt="{{ $event->name }}">
                        <div x:if="{ $event->isCancelled() }" class="event-card-cancelled-overlay" aria-hidden="true">
                            <span class="event-card-cancelled-overlay__text">@lang('event_life_cycle.cancelled')</span>
                        </div>
                    </div>

                    <div class="group-highlight-card side shadow">

                        <div class="highlight-item highlight-item--schedule">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <small>Időpont</small>
                                <strong>{{ $event->getCardScheduleLabel() }}</strong>
                                <a href="@route('event.ics', ['event' => $event->id])" class="event-sidebar-ics-link">
                                    <i class="fas fa-calendar-plus" aria-hidden="true"></i>
                                    Hozzáadás naptárhoz
                                </a>
                            </div>
                        </div>

                        <div class="highlight-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <small>Helyszín</small>
                                <strong>{{ $event->location_name }}</strong>
                                <span x:if="{ $event->address }" class="community-city d-block">{{ $event->address }}</span>
                            </div>
                        </div>

                        <div x:if="{ $event->organizer }" class="highlight-item">
                            <i class="fas fa-user"></i>
                            <div>
                                <small>Szervező</small>
                                <strong>{{ $event->organizer }}</strong>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="group-header-single">

                    <div x:if="{ $event->description }" class="group-card shadow">
                        <h3>Esemény leírása</h3>
                        <p>{{ $event->description }}</p>
                    </div>

                    <a href="@route('event.ics', ['event' => $event->id])" class="btn btn-contact mt-3">
                        <i class="fas fa-calendar-plus"></i>
                        Hozzáadás naptárhoz
                    </a>

                    <div x:if="{ $event->lat && $event->lng }" class="group-card shadow mt-3 map-wrapper">
                        <p class="text-center mb-2">
                            <a href="#" class="btn btn-outline-orange toggle-map">
                                <mod-icon :name="map" /> Térkép
                            </a>
                        </p>
                        <div class="group-map" style="display:none;">
                            <div id="map"></div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</mint-extend>
