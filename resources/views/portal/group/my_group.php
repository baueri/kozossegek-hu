@section('header')
@include('asset_groups.select2')
@include('asset_groups.editor')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('portal')

<div class="container inner">
    @include('portal.partials.user-sidemenu')
    @include('admin.partials.message')

    <h1 class="h3">Közösség módosítása</h1>
    @if($group->exists())
    <p>
        <a href="{{ $group->url() }}">Megtekintés</a>
    </p>
    @endif
    <h3 class="h4 mt-3">Általános adatok</h3>
    <form method="post" id="group-form" action="@route('portal.my_group.update', $group)" enctype="multipart/form-data">
        @if($group->pending == 1)
            @alert('warning')
                <b>A közösséged még függőben van.</b> Amíg nincs jóváhagyva, addig nem jelenítjük meg a közösségek között.
            @endalert
        @elseif($group->isRejected())
            @alert('warning')
                <b>A közösséged vissza lett dobva.</b><br/> Nézd meg az adataidat, hogy minden rendben van-e, majd kattints a mentés gombra.
            @endalert
        @elseif($group->status == App\Enums\GroupStatus::inactive)
            @alert('warning')
                <b>A közösséged jelenleg inaktív.</b><br> Nem jelenik meg sem a keresési találatok közzött, illetve az
                adatlapját se lehet megtekinteni.
            @endalert
        @endif
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="status">Állapot</label>
                    <select id="status" name="status" class="form-control">
                        @foreach($statuses as $status => $name)
                            <option value="{{ $status }}" @selected($group->status == $status)>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group required">
                    <label for="name">Közösség neve</label>
                    <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required">
                    <label for="group_leaders">Közösségvezető(k) neve(i)</label>
                    <input type="text" name="group_leaders" id="group_leaders" class="form-control"
                           value="{{ $group->group_leaders ?: $user->name }}" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group required">
                    <label for="institute_id">Intézmény / plébánia</label>
                    <select name="institute_id" style="width:100%" class="form-control" required>
                        <option value="{{ $group->institute_id }}">
                            {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                            @if($institute && $institute->approved == 0)
                                - függőben levő intézmény
                            @endif
                        </option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group required">
                    <label for="age_group">Korosztály <small>(legalább egyet adj meg)</small></label>
                    <select class="form-control" name="age_group[]" multiple="multiple" id="age_group" required>
                        @foreach($age_groups as $age_group)
                        <option value="{{ $age_group->value }}" @selected(in_array($age_group->name, $age_group_array))>
                            {{ $age_group->translate() }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group required">
                    <label for="occasion_frequency">Alkalmak gyakorisága</label>
                    <select class="form-control" id="occasion_frequency" name="occasion_frequency" required>
                        @foreach($occasion_frequencies as $occasion_frequency)
                        <option value="{{ $occasion_frequency->value }}" @selected($group->occasion_frequency == $occasion_frequency->value)>
                            {{ $occasion_frequency->translate() }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="on_days">Mely napo(ko)n</label>
                    @component('day_selector', compact('group_days'))
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="on_days">Csatlakozási lehetőség módja <i class="fa fa-info-circle"
                         title="<b>Egyéni megbeszélés alapján:</b> Közösségvezetővel egyeztetve történik<br/><b>Folyamatos csatlakozási lehetőség:</b> Az év folyamán bármikor jöhetnek új tagok<br/><b>Időszakos csatlakozás:</b> pl.: Minden félév első hónapja, negyedévente stb"
                         data-html="true"></i></label>
                    @join_mode_selector($group->join_mode)
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="spiritual_movement_id">Lelkiségi mozgalom</label>
            @spiritual_movement_selector($group->spiritual_movement_id)
        </div>
        <h3 class="h4 mt-3">A közösség jellemzői</h3>
        <div class="form-group">
            <div>
                @foreach($tags as $tag)
                <label class="mr-2" for="tag-{{ $tag->value }}">
                    <input type="checkbox"
                           name="tags[]"
                           id="tag-{{$tag->value}}"
                           value="{{ $tag->value }}"
                           @if(in_array($tag->value, $group_tags)) checked @endif
                    > {{ $tag->translate() }}
                </label>
                @endforeach
            </div>
        </div>

        <div class="form-group required">
            <h3 class="h4 mt-3">Bemutatkozás</h3>
            <textarea name="description" id="description">{{ $group->description }}</textarea>
        </div>
        <div class="group-images">
                <div class="form-group">
                    <h3 class="h4 mt-3 mb-0">Fotó a közösségről</h3>
                    <p><small>(Ha ezt nem adod meg, akkor az intézmény fotója jelenik meg)</small><br/></p>
                    <div class="group-image">
                        <img src="{{ $group->getThumbnail() . '?' . time() }}" id="image" width="300">
                    </div>
                    <label for="image-upload" class="btn btn-primary">
                        <i class="fa fa-upload"></i> Kép feltöltése
                        <input type="file" onchange="loadFile(event, this);" data-target="temp-image"
                               id="image-upload">
                    </label>
                    <div style="display: none"><img id="temp-image"/></div>
                    <input type="hidden" name="image">
                </div>
        </div>
        <div>
            <h3 class="h4 mt-3">Igazolás</h3>
            <p><b>Fájl:</b> @if($group->document)
                <a href="{{ $group->getDocumentUrl() }}" download><i class="fa fa-download"></i> {{ $group->document }}</a>
                @else
                    Még nincs feltöltve
                @endif
            </p>
            <div class="form-group">
                <div class="mb-3">
                    <h5 class="mb-0">Igazoló dokumentum lecserélése</h5>
                    <p><small>Microsoft office dokumentum (<b>doc, docx</b>), <b>pdf</b> vagy kép formátum</small></p>
                    <input type="file" name="document">
                </div>
            </div>
        </div>
        <hr>
        @csrf()
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        <a href="@route('portal.delete_group', $group)" class="text-danger float-right confirm-action">közösségem törlése</a>
    </form>
</div>
<script>
    $(() => {

        var image_val;

        var upload = null;

        function initCroppie() {
            upload = $("#image").croppie({
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

        $("#temp-image").on("load", function () {
            var newImg = $($(this).closest("div").html());
            $(".group-image").html(newImg);
            newImg.attr("id", "image").show();
            initCroppie();
        });

        $("form#group-form").submit(function (e) {
            if (upload) {
                upload.croppie("result", {
                    type: "base64",
                    format: "jpeg",
                    size: {width: 510, height: 510}
                }).then(function (base64) {
                    image_val = base64;
                    $("[name=image]").val(base64);
                });
            }
        });

        $("[name=institute_id]").instituteSelect();
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

        $("#age_group").select2();
    });
</script>
