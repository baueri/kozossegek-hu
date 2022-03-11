@section('header')
    @include('asset_groups.select2')
    @include('asset_groups.editor')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@title($title)
@extends('admin')
@if($group->deleted_at)
    @alert('danger')
        <b>Törölt közösség!</b> A visszállításához kattints a <b>visszaállítás</b> gombra a jobb oldali sáv alján.
    @endalert
@endif
<form method="post" id="group-form" action="{{ $action }}">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group required">
                        <label for="name">Közösség neve:</label>
                        <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group required">
                        <label for="group_leaders">Közösségvezető(k)</label>
                        <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group required">
                        <label for="institute_id">Intézmény / plébánia</label>
                        <select name="institute_id" style="width:100%" class="form-control" required>
                            <option value="{{ $group->institute_id }}">
                                {{ $group->institute_id ? $institute->name . ' (' . $institute->city . ')' : 'intézmény' }}
                                @if($institute && $institute->approved == 0)
                                - függőben levő intézmény
                                @endif
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="user_id">Karbantartó</label>
                        <div style="width: 200px">
                            <select name="user_id" id="user_id" class="form-control">
                                <option value="{{ $group->user_id ?: '' }}">{{ $owner->name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">

                </div>
            </div>
            <div class="form-group">
                <label>Címkék</label>
                <div>
                    @foreach($tags as $tag)
                        <label class="mr-2" for="tag-{{ $tag['id'] }}">
                            <input type="checkbox"
                                name="tags[]"
                                id="tag-{{$tag['id']}}"
                                value="{{ $tag['slug'] }}"
                                @if(in_array($tag['slug'], $group_tags)) checked @endif
                            > {{ $tag['tag'] }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group required">
                <label for="description">Bemutatkozás</label>
                <textarea name="description" id="description" required>{{ $group->description }}</textarea>
            </div>
            <div class="row group-images">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fényképek</label>
                        <div class="group-image">
                            <img src="{{ $group->getThumbnail() . '?' . time() }}" id="image" width="300">
                        </div>
                        <label for="image-upload" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Kép feltöltése
                            <input type="file" onchange="loadFile(event, this);" data-target="temp-image" id="image-upload">
                        </label>
                        <div style="display: none"><img id="temp-image" /></div>
                        <input type="hidden" name="image">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 group-side-content">
            <div class="form-group">
                <label for="pending">Jóváhagyva</label>
                <select class="form-control" id="pending" name="pending" data-placeholder="jóváhagyás állapota">
                    <option></option>
                    <option value="0" @selected($group->pendingStatusIs(0))>jóváhagyva</option>
                    <option value="1" @selected($group->pendingStatusIs(1))>jóhávagyásra vár</option>
                    <option value="-1" @selected($group->pendingStatusIs(-1))>visszautasítva</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Állapot</label>
                <select id="status" name="status" class="form-control">
                    @foreach($statuses as $status => $name)
                    <option value="{{ $status }}"  @selected($group->status == $status)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group required">
                <label for="age_group">Korosztály</label>
                <select class="form-control" name="age_group[]" multiple="multiple">
                    @foreach($age_groups as $age_group)
                        <option value="{{ $age_group->value }}" @selected($age_group_array->has('value', $age_group->value))>
                            {{ $age_group->translate() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group required">
                <label for="occasion_frequency">Alkalmak gyakorisága</label>
                <select class="form-control" name="occasion_frequency" required>
                    @foreach($occasion_frequencies as $occasion_frequency)
                        <option value="{{ $occasion_frequency->value }}" @selected($group->occasion_frequency == $occasion_frequency->value)>
                            {{ $occasion_frequency->translate() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="on_days">Mely napo(ko)n</label>
                @component('day_selector', compact('group_days'))
            </div>
            <div class="form-group">
                <label for="join_mode">Csatlakozás módja</label>
                <select class="form-control" name="join_mode" data-allow-clear="1" data-placeholder="Nincs megadva">
                    <option></option>
                    @foreach($join_modes as $join_mode => $join_mode_name)
                        <option value="{{ $join_mode }}" @if($group->join_mode==$join_mode) selected @endif>
                            {{ $join_mode_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="spiritual_movement_id">Lelkiségi mozgalom</label>
                <select id="spiritual_movement_id" name="spiritual_movement_id" class="form-control" data-allow-clear="1" data-placeholder="Nincs megadva">
                    <option></option>
                    @foreach($spiritual_movements as $spiritual_movement)
                        <option value="{{ $spiritual_movement['id'] }}" @if($group->spiritual_movement_id == $spiritual_movement['id']) selected @endif>
                            {{ $spiritual_movement['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
                @if($group->exists())
                    @if(!$group->deleted_at)
                        <a href="#" onclick="deleteConfirm(() => { window.location.href = '@route("admin.group.delete", $group)' });" class="btn btn-danger"><i class="fa fa-trash"></i> törlés</a>
                    @else
                        <a href="@route('admin.group.restore', $group)" class="btn btn-warning"><i class="fa fa-sync"></i> visszaállítás</a>
                    @endif
                @endif
            </div>
            <div class="form-group">
                <label>Felöltött igazolás</label><br/>
                @if($group->exists() && $group->hasDocument())
                    <p><a href="{{ $group->getDocumentUrl() }}" download=""><i class="fa fa-download"></i> Igazolás letöltése: {{ $group->document }}</a></p>
                @else
                    @alert('warning') nincs igazolás @endalert
                @endif
            </div>

        </div>
    </div>
</form>

<script>
    var image_val;
    $(() => {

        var upload = null;
        function initCroppie()
        {
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

        $("#temp-image").on("load", function(){
            var newImg = $($(this).closest("div").html());
            $(".group-image").html(newImg);
            newImg.attr("id", "image").show();
            initCroppie();
        });

        $("form#group-form").submit(function(e){
            if (upload) {
                upload.croppie("result", {type: "base64", format: "jpeg", size: {width: 510, height: 510}}).then(function(base64){
                    image_val = base64;
                    $("[name=image]").val(base64);
                });
            }
        });

        $("[name=institute_id]").select2({
            placeholder: "intézmény",
            allowClear: true,
            ajax: {
                url: "@route('api.search-institute')",
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    params.city = $("[name=city]").val();
                    return params;
                }
            }
        });

        $("[name=user_id]").select2({
            placeholder: "karbantartó",
            allowClear: true,
            ajax: {
                url: "@route('api.search-user')",
                dataType: 'json',
                delay: 300
            }
        });

        initSummernote('[name=description]');

        $(".group-side-content select").each(function(){
            $(this).select2({
                allowClear: $(this).data("allow-clear") === "1",
                placeholder: $(this).data("placeholder")
            });
        });
    });
</script>
