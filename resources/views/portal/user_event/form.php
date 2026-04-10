@section('header')
@include('asset_groups.editor')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('portal2026.portal')

@featuredTitle($event->exists() ? 'Esemény szerkesztése' : 'Új esemény')
<div class="container-fluid inner inner--account">
    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            @include('portal.partials.user-sidemenu')
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
            @include('admin.partials.message')

            @if($event->exists())
                @if($event->status === 'pending')
                    @alert('warning')
                        <b>Az esemény jóváhagyásra vár.</b> Amíg nincs jóváhagyva, nem jelenik meg a nyilvános eseménylistán.
                    @endalert
                @elseif($event->status === 'rejected')
                    @alert('warning')
                        <b>Az esemény elutasításra került.</b> Ellenőrizd az adatokat, és ha szükséges, vedd fel a kapcsolatot az üzemeltetővel.
                    @endalert
                @endif
                <p class="mb-3">
                    @if($event->isApproved() && $event->lifecycle === 'active')
                        <a href="{{ $event->getUrl() }}" target="_blank" rel="noopener" class="font-weight-500">@icon('eye') Megtekintés (nyilvános oldal)</a>
                    @endif
                </p>
            @endif

            <form method="post" action="{{ $action }}" id="portal-event-form">
                <div class="step-container shadow account-step-form">
                    <div class="row">
                        <div class="col-lg-8 pr-lg-4">
                            <h3 class="h5 text-muted mb-3">Alapadatok</h3>
                            <div class="form-group mb-2">
                                <label for="ev-name" class="mb-1">Cím</label>
                                <input type="text" class="form-control form-control-sm" id="ev-name" name="name" value="{{ $event->name }}" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="ev-starts" class="mb-1">Kezdés</label>
                                    <input type="{{ $event->all_day ? 'date' : 'datetime-local' }}" class="form-control form-control-sm" id="ev-starts" name="starts_at"
                                           value="{{ $event->starts_at ? ($event->all_day ? $event->starts_at->format('Y-m-d') : $event->starts_at->format('Y-m-d') . 'T' . $event->starts_at->format('H:i')) : '' }}" required>
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label for="ev-ends" class="mb-1">Vége</label>
                                    <input type="{{ $event->all_day ? 'date' : 'datetime-local' }}" class="form-control form-control-sm" id="ev-ends" name="ends_at"
                                           value="{{ $event->ends_at ? ($event->all_day ? $event->ends_at->format('Y-m-d') : $event->ends_at->format('Y-m-d') . 'T' . $event->ends_at->format('H:i')) : '' }}">
                                </div>
                                <div class="form-group col-sm-4 mb-2 d-flex align-items-end">
                                    <div class="form-check pb-1">
                                        <input class="form-check-input" type="checkbox" id="ev-all-day" name="all_day" value="1" @checked($event->all_day)>
                                        <label class="form-check-label" for="ev-all-day">Egész napos</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="mb-1">Leírás</label>
                                <textarea name="description" id="description">{{ $event->description }}</textarea>
                            </div>

                            <h3 class="h5 text-muted mb-2">Helyszín</h3>
                            <div class="form-group mb-2">
                                <label class="mb-1 small font-weight-bold">Címkeresés (OpenStreetMap)</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="event-osm-q" placeholder="Utca, házszám, város…" autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="event-osm-search">Keresés</button>
                                    </div>
                                </div>
                                <small class="text-muted">Válassz találatot a mezők kitöltéséhez. © OpenStreetMap.</small>
                                <div id="event-osm-results" class="event-osm-results mt-1"></div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-2">
                                    <label for="ev-loc-name" class="mb-1">Helyszín neve</label>
                                    <input type="text" class="form-control form-control-sm" id="ev-loc-name" name="location_name" value="{{ $event->location_name }}">
                                </div>
                                <div class="form-group col-md-6 mb-2">
                                    <label for="ev-address" class="mb-1">Cím</label>
                                    <input type="text" class="form-control form-control-sm" id="ev-address" name="address" value="{{ $event->address }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6 col-md-3 mb-2">
                                    <label for="ev-lat" class="mb-1">Szél. (lat)</label>
                                    <input type="text" class="form-control form-control-sm" id="ev-lat" name="lat" value="{{ $event->lat }}">
                                </div>
                                <div class="form-group col-6 col-md-3 mb-2">
                                    <label for="ev-lng" class="mb-1">Hossz. (lng)</label>
                                    <input type="text" class="form-control form-control-sm" id="ev-lng" name="lng" value="{{ $event->lng }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 border-left-lg mt-4 mt-lg-0 pl-lg-4">
                            <h3 class="h5 text-muted mb-3">Kép és címkék</h3>
                            <div class="form-group mb-3">
                                <label class="mb-1 d-block">Kiemelt kép</label>
                                <div class="event-featured-wrap mx-auto mx-lg-0" style="max-width: 260px;">
                                    <img src="{{ $event->featured_image ? ($event->getFeaturedImageUrl() ?: $event->featured_image) : '/images/placeholder_rect.webp' }}?{{ time() }}" id="event-featured-image" class="img-fluid" style="max-width: 100%;" alt="">
                                </div>
                                <label class="btn btn-primary btn-sm event-featured-upload-btn mb-0 mt-2">
                                    <i class="fa fa-upload"></i> Kép feltöltése
                                    <input type="file"
                                        accept="image/*"
                                        class="event-featured-upload-input"
                                        onchange="loadFile(event, this);"
                                        data-target="event-temp-image"
                                        id="event-image-upload">
                                </label>
                                <div style="display: none"><img id="event-temp-image" alt=""></div>
                                <input type="hidden" name="featured_image_data" value="">
                            </div>
                            <div class="form-group mb-2">
                                <label for="ev-tags" class="mb-1">Címkék <span class="text-muted font-weight-normal">(vesszővel)</span></label>
                                <input type="text" class="form-control form-control-sm" id="ev-tags" name="tags" value="{{ $tags ?? '' }}" placeholder="pl. lelki, ifjusagi">
                            </div>
                            <div class="form-group mb-2">
                                <label for="ev-organizer" class="mb-1">Szervező</label>
                                <input type="text" class="form-control form-control-sm" id="ev-organizer" name="organizer" value="{{ $event->organizer ?: auth()->name }}">
                            </div>
                            @if($event->exists())
                            <div class="form-group mb-0">
                                <label for="ev-lifecycle" class="mb-1">@lang('event_life_cycle.heading')</label>
                                <select name="lifecycle" id="ev-lifecycle" class="form-control form-control-sm">
                                    @foreach(\App\Enums\EventLifeCycle::cases() as $lc)
                                        <option value="{{ $lc->value }}" @selected($event->lifecycle === $lc->value)>{{ $lc->translate() }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">@lang('event_life_cycle.help_cancelled')</small>
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">
                    <div class="d-flex flex-wrap align-items-center">
                        @csrf()
                        <button type="submit" class="btn btn-orange px-4 rounded-pill"><i class="fa fa-save"></i> Mentés</button>
                        <a href="@route('portal.my_events')" class="btn btn-link btn-sm ml-2">Vissza a listához</a>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<style>
.event-featured-upload-btn {
    position: relative;
    overflow: hidden;
    cursor: pointer;
}
.event-featured-upload-input {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    font-size: 0;
}
.event-osm-results {
    max-height: 160px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: #fff;
    font-size: 0.8125rem;
}
.event-osm-result-item {
    padding: 5px 8px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}
.event-osm-result-item:last-child {
    border-bottom: none;
}
.event-osm-result-item:hover {
    background: #f0f7ff;
}
@media (min-width: 992px) {
    .border-left-lg {
        border-left: 1px solid #e9ecef;
    }
}
</style>
<script>
$(() => {
    var geoSearchUrl = '@route('portal.my_event.geocode_search')';
    var upload = null;

    initSummernote('[name=description]', {
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['help']]
        ]
    });

    $('#event-osm-search').on('click', function () {
        var q = $('#event-osm-q').val().trim();
        var $out = $('#event-osm-results');
        $out.empty();
        if (q.length < 3) {
            $out.html('<div class="text-danger small p-2">Írj be legalább 3 karaktert.</div>');
            return;
        }
        $out.html('<div class="text-muted small p-2">Keresés…</div>');
        $.getJSON(geoSearchUrl, {q: q})
            .done(function (res) {
                $out.empty();
                if (!res.success) {
                    $out.html('<div class="text-danger small p-2">' + (res.msg || 'Hiba történt.') + '</div>');
                    return;
                }
                var rows = res.results || [];
                if (!rows.length) {
                    $out.html('<div class="text-muted small p-2">Nincs találat.</div>');
                    return;
                }
                rows.forEach(function (item) {
                    var $row = $('<div class="event-osm-result-item"></div>').text(item.label);
                    $row.on('click', function () {
                        $('[name=location_name]').val(item.name || '');
                        $('[name=address]').val(item.address || item.label || '');
                        $('[name=lat]').val(item.lat || '');
                        $('[name=lng]').val(item.lng || '');
                        $out.empty();
                    });
                    $out.append($row);
                });
            })
            .fail(function () {
                $out.html('<div class="text-danger small p-2">A keresés nem sikerült.</div>');
            });
    });

    $('#event-osm-q').on('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#event-osm-search').trigger('click');
        }
    });

    function eventFormToDatePart(v) {
        if (!v || typeof v !== 'string') {
            return '';
        }
        return v.length >= 10 ? v.slice(0, 10) : '';
    }

    function syncEventDateTimeInputs(allDay) {
        var $starts = $('#ev-starts');
        var $ends = $('#ev-ends');
        var sv = $starts.val() || '';
        var ev = $ends.val() || '';

        if (allDay) {
            $starts.attr('type', 'date');
            $ends.attr('type', 'date');
            $starts.val(eventFormToDatePart(sv));
            $ends.val(eventFormToDatePart(ev));
        } else {
            $starts.attr('type', 'datetime-local');
            $ends.attr('type', 'datetime-local');
            if (sv.length === 10) {
                $starts.val(sv + 'T00:00');
            } else if (sv) {
                $starts.val(sv);
            }
            if (ev.length === 10) {
                $ends.val(ev + 'T00:00');
            } else if (ev) {
                $ends.val(ev);
            }
        }
    }

    $('#ev-all-day').on('change', function () {
        syncEventDateTimeInputs(!!this.checked);
    });
    syncEventDateTimeInputs($('#ev-all-day').prop('checked'));

    function initCroppie() {
        if (upload) {
            upload.croppie('destroy');
            upload = null;
        }
        upload = $("#event-featured-image").croppie({
            enableExif: true,
            mouseWheelZoom: false,
            viewport: {
                width: '220',
                height: '220',
                type: 'rectangle'
            },
            boundary: {
                width: '260',
                height: '260'
            }
        });
    }

    $("#event-temp-image").on("load", function () {
        var newImg = $($(this).closest("div").html());
        $(".event-featured-wrap").html(newImg);
        newImg.attr("id", "event-featured-image").show();
        initCroppie();
    });

    $("form#portal-event-form").on("submit", function (e) {
        var $desc = $("#description");
        if ($desc.length && $desc.next(".note-editor").length) {
            $desc.val($desc.summernote("code"));
        }
        if (!upload) {
            return;
        }
        e.preventDefault();
        var form = this;
        upload.croppie("result", {type: "base64", format: "jpeg", size: {width: 510, height: 510}}).then(function (base64) {
            $("[name=featured_image_data]").val(base64);
            upload = null;
            form.submit();
        });
    });

    /* Croppie only after a new file is chosen (#event-temp-image load → initCroppie), not for the current/placeholder image */
});
</script>
