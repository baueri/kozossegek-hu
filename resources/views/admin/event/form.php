@section('header')
    @include('asset_groups.editor')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection

@extends('admin')

@if($event->exists())
    <a href="@route('admin.event.create')" class="btn btn-primary btn-sm mb-2">@icon('plus') Új Esemény</a>
@endif

<form method="post" action="{{ $action }}" id="event-form">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Cím</label>
                        <input type="text" class="form-control" name="name" value="{{ $event->name }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>(Szép) url</label>
                        <input type="text" class="form-control" name="slug" value="{{ $event->slug }}">
                        <small class="text-muted">Ha üres, automatikusan generáljuk.</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kezdés</label>
                        <input type="datetime-local" class="form-control" name="starts_at"
                               value="{{ $event->starts_at ? $event->starts_at->format('Y-m-d\\TH:i') : '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Vége</label>
                        <input type="datetime-local" class="form-control" name="ends_at"
                               value="{{ $event->ends_at ? $event->ends_at->format('Y-m-d\\TH:i') : '' }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="d-block">&nbsp;</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="all-day" name="all_day" value="1" @checked($event->all_day)>
                            <label class="form-check-label" for="all-day">Egész napos</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Leírás</label>
                <textarea name="description">{{ $event->description }}</textarea>
            </div>

            <div class="row event-images">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Kiemelt kép</label>
                        <div class="event-featured-wrap">
                            <img src="{{ $event->featured_image ? ($event->getFeaturedImageUrl() ?: $event->featured_image) : '/images/placeholder_rect.webp' }}?{{ time() }}" id="event-featured-image" width="300" alt="">
                        </div>
                        <label class="btn btn-primary event-featured-upload-btn mb-0">
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
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label>Állapot</label>
                <select name="status" class="form-control">
                    <?php foreach (\App\Enums\EventStatus::cases() as $s): ?>
                        <option value="<?= $s->value() ?>" @selected($event->status == $s->value())><?= $s->translate() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>@lang('event_life_cycle.heading')</label>
                <select name="lifecycle" class="form-control">
                    <?php foreach (\App\Enums\EventLifeCycle::cases() as $lc): ?>
                        <option value="<?= $lc->value() ?>" @selected($event->lifecycle == $lc->value())><?= $lc->translate() ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Címkék (vesszővel)</label>
                <input type="text" class="form-control" name="tags" value="{{ $tags ?? '' }}" placeholder="pl. lelki, ifjusagi">
            </div>

            <div class="form-group">
                <label>Szervező</label>
                <input type="text" class="form-control" name="organizer" value="{{ $event->organizer }}">
            </div>

            <div class="form-group">
                <label>Címkeresés (OpenStreetMap)</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="event-osm-q" placeholder="pl. utca, házszám, város" autocomplete="off">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="event-osm-search">Keresés</button>
                    </div>
                </div>
                <small class="text-muted d-block mt-1">Válassz egy találatot: kitöltjük a helyszín mezőket és a koordinátákat. Adatok: © OpenStreetMap.</small>
                <div id="event-osm-results" class="event-osm-results mt-2"></div>
            </div>

            <div class="form-group">
                <label>Helyszín neve</label>
                <input type="text" class="form-control" name="location_name" value="{{ $event->location_name }}">
            </div>
            <div class="form-group">
                <label>Cím (helyszín)</label>
                <input type="text" class="form-control" name="address" value="{{ $event->address }}">
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Lat</label>
                        <input type="text" class="form-control" name="lat" value="{{ $event->lat }}">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Lng</label>
                        <input type="text" class="form-control" name="lng" value="{{ $event->lng }}">
                    </div>
                </div>
            </div>

            @if($event && $event->exists())
                <p>
                    <a href="{{ $event->getUrl() }}" target="_blank"><i class="fa fa-eye"></i> megtekintés</a>
                </p>
            @endif

            @csrf()
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        </div>
    </div>
</form>

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
    max-height: 220px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    background: #fff;
    font-size: 0.875rem;
}
.event-osm-result-item {
    padding: 6px 8px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}
.event-osm-result-item:last-child {
    border-bottom: none;
}
.event-osm-result-item:hover {
    background: #f0f7ff;
}
</style>
<script>
$(() => {
    var geoSearchUrl = '@route('admin.event.geocode_search')';
    var upload = null;

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

    function initCroppie() {
        if (upload) {
            upload.croppie('destroy');
            upload = null;
        }
        upload = $("#event-featured-image").croppie({
            enableExif: true,
            mouseWheelZoom: false,
            viewport: {
                width: '250',
                height: '250',
                type: 'rectangle'
            },
            boundary: {
                width: '300',
                height: '300'
            }
        });
    }

    $("#event-temp-image").on("load", function () {
        var newImg = $($(this).closest("div").html());
        $(".event-featured-wrap").html(newImg);
        newImg.attr("id", "event-featured-image").show();
        initCroppie();
    });

    $("form#event-form").on("submit", function (e) {
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

    initSummernote('[name=description]');
});
</script>
