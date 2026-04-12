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
                            @if($institute)
                            <strong>{{ $institute->name }}</strong>
                            <span class="text-muted d-block" style="font-size:.85rem;font-weight:400;">
                                {{ $group->city }}@if($institute->address), {{ $institute->address }}@endif
                            </span>
                            @else
                            <strong>{{ $group->city }}</strong>
                            @endif
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
<div class="modal fade contact-modal" id="contact-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered contact-modal__dialog">
        <div class="modal-content contact-modal__shell">
            <button type="button" class="login-prompt-close" data-bs-dismiss="modal" aria-label="Bezárás">
                <i class="fas fa-times"></i>
            </button>

            <div class="login-prompt-icon">
                <i class="fas fa-envelope"></i>
            </div>

            <h2 class="login-prompt-title">Kapcsolatfelvétel</h2>
            <p class="login-prompt-subtitle contact-modal__intro">
                Írj üzenetet a közösség vezetőjének — hamarosan válaszolni fog.
            </p>

            <div class="contact-modal__inject" id="contact-modal-fields"></div>

            <div class="contact-modal__actions">
                <button type="button" class="btn-contact-modal-secondary" data-bs-dismiss="modal">Mégse</button>
                <button type="submit" form="contact-modal-ajax-form" class="btn btn-orange rounded-pill px-4">
                    <i class="fas fa-paper-plane me-1"></i>Üzenet küldése
                </button>
            </div>
        </div>
    </div>
</div>
<x-group-card event="$group"/>
<script>
    $(() => {
        let contactFormLoading = false;

        $(document).off("click.kozossegContact", ".open-contact-modal").on("click.kozossegContact", ".open-contact-modal", function() {
            if (contactFormLoading) {
                return;
            }
            contactFormLoading = true;
            const $modal = $("#contact-modal");
            const $inject = $("#contact-modal-fields");
            $.post({
                url: "@route('portal.group-contact-form', ['kozosseg' => $slug])",
                dataType: "html",
                success: function(form) {
                contactFormLoading = false;
                $inject.empty().html(form);
                $modal.find(".contact-modal__actions").show();
                $modal.find(".contact-modal__intro").show();
                $modal.find("[type=submit]").prop("disabled", false);
                bootstrap.Modal.getOrCreateInstance(document.getElementById("contact-modal")).show();
                },
            }).fail(function() {
                contactFormLoading = false;
                dialog.danger({ message: 'Nem sikerült betölteni az űrlapot.', size: 'md' }, m => m.closeAll());
            });
        });

        $("#contact-modal").off("submit.kozossegContact", "#contact-modal-ajax-form").on("submit.kozossegContact", "#contact-modal-ajax-form", function(e) {
            e.preventDefault();

            const $m = $("#contact-modal");
            const $form = $(this);
            const data = {
                name: $form.find("[name=name]").val(),
                email: $form.find("[name=email]").val(),
                message: $form.find("[name=message]").val(),
                website: $form.find("[name=website]").val()
            };
            $.post("@route('portal.contact-group', $group)", data, function(response) {
                if (response.success) {
                    $("#contact-modal-fields").html(response.msg);
                    $m.find(".contact-modal__intro").hide();
                    $m.find(".contact-modal__actions").hide();
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

</style>