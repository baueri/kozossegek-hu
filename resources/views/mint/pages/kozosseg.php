<mint-extend path="layout/portal.php" :subtitle="{$group->name . ' | '}" :body-class="group-page">

    <mint-section name="header">
        <meta name="keywords" content="{{ $keywords }}" />
        <meta name="description" content="{{ $group->name }}" />
        <meta name="thumbnail" content="{{ $group->getThumbnail() }}" />
        <meta property="og:url" content="{{ $group->url() }}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="kozossegek.hu - {{ $group->name }}" />
        <meta property="og:description" content="{{ $group->excerpt(20) }}" />
        <mod-og-image :src="{ $group->getThumbnail() }" />
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
            let contactFormLoading = false;

            $(document).off("click.kozossegContact", ".open-contact-modal").on("click.kozossegContact", ".open-contact-modal", function () {
                if (contactFormLoading) {
                    return;
                }
                contactFormLoading = true;
                const $modal = $("#contact-modal");
                const $inject = $("#contact-modal-fields");
                $.post({
                    url: "<?= route('portal.group-contact-form', ['kozosseg' => $slug]) ?>",
                    dataType: "html",
                    success: function (form) {
                    contactFormLoading = false;
                    $inject.empty().html(form);
                    $modal.find(".contact-modal__actions").show();
                    $modal.find(".contact-modal__intro").show();
                    $modal.find("[type=submit]").prop("disabled", false);
                    bootstrap.Modal.getOrCreateInstance(document.getElementById("contact-modal")).show();
                    },
                }).fail(function () {
                    contactFormLoading = false;
                    dialog.danger({ message: 'Nem sikerült betölteni az űrlapot.', size: 'md' }, m => m.closeAll());
                });
            });

            $("#contact-modal").off("submit.kozossegContact", "#contact-modal-ajax-form").on("submit.kozossegContact", "#contact-modal-ajax-form", function (e) {
                e.preventDefault();
                const $m = $("#contact-modal");
                const $form = $(this);
                const data = {
                    name:    $form.find("[name=name]").val(),
                    email:   $form.find("[name=email]").val(),
                    message: $form.find("[name=message]").val(),
                    website: $form.find("[name=website]").val()
                };
                $.post("<?= route('portal.contact-group', $group) ?>", data, function (response) {
                    if (response.success) {
                        $("#contact-modal-fields").html(response.msg);
                        $m.find(".contact-modal__intro").hide();
                        $m.find(".contact-modal__actions").hide();
                    } else {
                        dialog.danger({ message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!', size: 'md' }, m => m.closeAll());
                    }
                }).fail(() => {
                    dialog.danger({ message: 'Nem sikerült elküldeni az üzenetet, kérjük, próbáld meg később!', size: 'md' }, m => m.closeAll());
                });
            });

            <?php if ($group->lat && $group->lon): ?>
            let mapInitialized = false;
            let mapInstance    = null;

            $(".toggle-map").click(function (e) {
                e.preventDefault();
                $(".group-map").slideToggle(200);

                if (!mapInitialized) {
                    <?php $m = addslashes(json_encode([['lat' => $group->lat, 'lon' => $group->lon, 'popup_html' => "<strong>{$group->name}</strong><br>{$group->city}", 'marker' => '/images/marker_red.png']])); ?>
                    const markers = JSON.parse("{{ $m }}");
                    const map = L.map('map');
                    mapInstance = map;
                    map.attributionControl.setPrefix('');
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { minZoom: 7 }).addTo(map);

                    const leafletMarkers = [];
                    markers.forEach(marker => {
                        const icon = L.icon({ iconUrl: marker.marker, iconSize: [28, 28], popupAnchor: [0, -16] });
                        const m = L.marker([marker.lat, marker.lon], { icon }).addTo(map);
                        if (marker.popup_html) m.bindPopup(marker.popup_html);
                        leafletMarkers.push(m);
                    });

                    const group = new L.featureGroup(leafletMarkers);
                    map.fitBounds(group.getBounds(), { padding: [20, 20] });
                    setTimeout(() => map.invalidateSize(), 300);
                    mapInitialized = true;
                } else {
                    setTimeout(() => mapInstance.invalidateSize(), 300);
                }
            });
            <?php endif; ?>
        });
        </script>
    </mint-section>

    <section class="page-header">
        <div class="container">
            <h1 class="group-title-modern">{{ $group->name }}</h1>

            <div x:if="{ $institute }" class="group-institute">
                <mod-icon :name="map-marker-alt" />{{ $group->city }}, {{ $institute->name }}
            </div>

            <div class="group-tags-modern">
                <span x:foreach="{ $group->tags as $tag }" class="tag-pill">{{ $tag->translate() }}</span>
            </div>
        </div>
    </section>

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

                        <div x:if="{ $group->join_mode }" class="highlight-item">
                            <i class="fas fa-door-open"></i>
                            <div>
                                <small>Csatlakozás módja</small>
                                <strong>{{ $group->joinModeText() }}</strong>
                            </div>
                        </div>

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
                    <?php
                    $reportRolunkMessage = "Bejelentés, észrevétel a következő közösség adatlapjával kapcsolatban:\n\n" . $group->url();
                    $reportRolunkHref = route('portal.page', ['slug' => 'rolunk'])
                        . '?' . http_build_query(['message' => $reportRolunkMessage], '', '&', PHP_QUERY_RFC3986)
                        . '#contact';
                    ?>

                    <div x:if="{ $group->description }" class="group-card shadow">
                        <h3>Bemutatkozás</h3>
                        <p>{{ $group->description }}</p>
                    </div>

                    <div class="group-profile-footer group-card mt-3 shadow">
                        <div x:if="{ $group->lat && $group->lon }" class="group-profile-footer__map">
                            <div class="group-profile-footer__map-toggle">
                                <a href="#" class="btn btn-outline-orange toggle-map">
                                    <mod-icon :name="map" /> Térkép
                                </a>
                            </div>
                            <div class="group-map" style="display:none;">
                                <div id="map"></div>
                            </div>
                        </div>

                        <div class="group-actions">
                            <button class="btn btn-contact open-contact-modal">
                                <i class="fas fa-paper-plane"></i>
                                Kapcsolatfelvétel
                            </button>

                            <div class="share-btn">
                                <div class="fb-share-button"
                                    data-href="{{ $group->url() }}"
                                    data-layout="button"
                                    data-size="small">
                                    <a target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($group->url()) }}&amp;src=sdkpreparse"
                                        class="fb-xfbml-parse-ignore">Megosztás</a>
                                </div>
                            </div>

                            <a class="group-report-btn group-report-btn--end" href="<?= htmlspecialchars($reportRolunkHref, ENT_QUOTES, 'UTF-8') ?>">
                                <i class="fas fa-flag" aria-hidden="true"></i>
                                Bejelentés
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <div x:if="{ $similar_groups->isNotEmpty() }" class="container mb-5">
        <div class="group-card shadow">
            <h2 class="section-title">Hasonló közösségek</h2>
            <div class="row" id="kozossegek-list">
                <div x:foreach="{ $similar_groups as $similarGroup }" class="col-lg-3 col-md-4 col-sm-6 mb-3">
                    <mod-kozosseg-card :group="{ $similarGroup }" />
                </div>
            </div>
        </div>
    </div>

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

</mint-extend>
