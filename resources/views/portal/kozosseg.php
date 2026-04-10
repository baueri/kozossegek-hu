<?php

use App\Models\ChurchGroupView;

/** @var ChurchGroupView $group */
?>

@section('header')
<meta name="keywords" content="{{ $keywords }}" />
<meta name="description" content="{{ $group->name }}" />
<meta name="thumbnail" content="{{ $group->getThumbnail() }}" />
<meta property="og:url" content="{{ $group->url() }}" />
<meta property="og:type" content="website" />
<meta property="og:title" content="kozossegek.hu - {{ $group->name }}" />
<meta property="og:description" content="{{ $group->excerpt(20) }}" />
@og_image($group->getThumbnail())
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
    crossorigin="" @preload_css() />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endsection

@extends('portal2026.portal')
@featuredTitle()
<h1 class="group-title-modern">
    {{ $group->name }}
</h1>

@if($institute)
<div class="group-institute">
    @icon('map-marker-alt'){{ $group->city }}, {{ $institute->name }}
</div>
@endif

<div class="group-tags-modern">
    @foreach($group->tags as $tag)
    <span class="tag-pill">{{ $tag->translate() }}</span>
    @endforeach
</div>

@endfeaturedTitle

<div class="group-hero-modern mb-5">
    <div class="container">
        <div class="group-header-grid">

            <div class="group-image-side">
                <img src="{{ $group->getThumbnail() }}" alt="{{ $group->name }}">

                <div class="group-highlight-card side shadow">
                    <div class="highlight-item">
                        <i class="fas fa-user"></i>
                        <div>
                            <small>Közösségvezető</small>
                            <strong>{{ $group->group_leaders }}</strong>
                        </div>
                    </div>

                    <div class="highlight-item">
                        <i class="fas fa-users"></i>
                        <div>
                            <small>Korosztály</small>
                            <strong>{{ $group->allAgeGroupsAsString() }}</strong>
                        </div>
                    </div>

                    <div class="highlight-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <small>Alkalmak gyakorisága</small>
                            <strong>{{ $group->occasionFrequency() }}</strong>
                        </div>
                    </div>

                    @if($group->join_mode)
                    <div class="highlight-item">
                        <i class="fas fa-door-open"></i>
                        <div>
                            <small>Csatlakozás módja</small>
                            <strong>{{ $group->joinModeText() }}</strong>
                        </div>
                    </div>
                    @endif

                    <div class="highlight-item">
                        <i class="fas fa-map"></i>
                        <div>
                            <small>Helyszín</small>
                            <strong>{{ $group->city }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group-header-single">

                @if($group->description)
                <div class="group-card shadow">
                    <h3>Bemutatkozás</h3>
                    <p>{{ $group->description }}</p>
                </div>
                @endif

                @if($group->lat && $group->lon)
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

                <div class="group-actions">
                    <button class="btn btn-contact open-contact-modal">
                        <i class="fas fa-paper-plane"></i>
                        Kapcsolatfelvétel
                    </button>

                    <div class="share-btn">
                        @facebook_share_button($group->url())
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@if($similar_groups->isNotEmpty())
<div class="container mb-5">
    <div class="group-card shadow">
        <h2 class="section-title">Hasonló közösségek</h2>
        <div class="row" id="kozossegek-list">
            @foreach($similar_groups as $similarGroup)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                @include('portal/partials/kozosseg_card.php', ['group' => $similarGroup])
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<div class="modal fade contact-modal" id="contact-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope"></i>
                    Kapcsolatfelvétel
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Bezárás"></button>
            </div>

            <form>
                <div class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Mégse
                    </button>

                    <button type="submit" class="btn btn-contact">
                        <i class="fas fa-paper-plane"></i>
                        Üzenet küldése
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
<x-group-card event="$group"/>
<script>
    $(() => {
        $(".open-contact-modal").click(function() {
            $.post("@route('portal.group-contact-form', ['kozosseg' => $slug])", function(form) {
                $("#contact-modal .modal-body").html(form);
                bootstrap.Modal.getOrCreateInstance(document.getElementById("contact-modal")).show();
            });
        });

        $("#contact-modal form").submit(function(e) {
            e.preventDefault();

            const data = {
                name: $("[name=name]").val(),
                email: $("[name=email]").val(),
                message: $("[name=message]").val(),
                website: $("[name=website]").val()
            };
            $.post("@route('portal.contact-group', $group)", data, function(response) {
                if (response.success) {
                    $("#contact-modal .modal-body").html(response.msg);
                    $("#contact-modal [type=submit]").remove();
                } else {
                    dialog.danger({
                        message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!',
                        size: 'md'
                    }, m => m.closeAll());
                }
            }).fail(() => {
                dialog.danger({
                    message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!',
                    size: 'md'
                }, m => m.closeAll());
            });
        });

        @if($group->lat && $group->lon)
        let mapInitialized = false;
        let mapInstance = null;

        $(".toggle-map").click(function(e) {
            e.preventDefault();

            const mapContainer = $(".group-map");
            mapContainer.slideToggle(200);

            if (!mapInitialized) {
                <?php $m = addslashes(json_encode([['lat' => $group->lat, 'lon' => $group->lon, 'popup_html' => "<strong>{$group->name}</strong><br>{$group->city}", 'marker' => '/images/marker_red.png']])); ?>
                const markers = JSON.parse("{{ $m }}");

                const map = L.map('map');
                mapInstance = map;

                map.attributionControl.setPrefix('');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    minZoom: 7,
                }).addTo(map);

                const leafletMarkers = [];

                markers.forEach(marker => {
                    const icon = L.icon({
                        iconUrl: marker.marker,
                        iconSize: [28, 28],
                        popupAnchor: [0, -16]
                    });

                    const m = L.marker([marker.lat, marker.lon], {
                        icon
                    }).addTo(map);

                    if (marker.popup_html) {
                        m.bindPopup(marker.popup_html);
                    }

                    leafletMarkers.push(m);
                });

                const group = new L.featureGroup(leafletMarkers);
                map.fitBounds(group.getBounds(), {
                    padding: [20, 20]
                });

                setTimeout(() => {
                    map.invalidateSize();
                }, 300);

                mapInitialized = true;
            } else {
                // ha már volt init → csak resize
                setTimeout(() => {
                    mapInstance.invalidateSize();
                }, 300);
            }
        });
        @endif
    });
</script>
<style>
    .map-toggle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .map-collapsible {
        margin-top: 15px;
        border-radius: 12px;
        overflow: hidden;
    }

    #map {
        border-radius: 12px;
        height: 500px;
    }
    .btn-contact {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: white;
    border: none;
    border-radius: 999px;
    padding: 12px 22px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 8px 20px rgba(249, 115, 22, 0.25);
    transition: all 0.2s ease;
}

.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(249, 115, 22, 0.35);
    color: white;
}

.contact-modal .modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    overflow: hidden;
}

.contact-modal .modal-header {
    background: linear-gradient(135deg, #f97316, #f59e0b);
    color: white;
    border: none;
}

.contact-modal .modal-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
}

.contact-modal .modal-body {
    padding: 20px;
}

.contact-modal .modal-footer {
    border-top: 1px solid #f1f5f9;
    padding: 15px 20px;
}
</style>