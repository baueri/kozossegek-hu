@section('header')
    @include('asset_groups.select2')
    @include('asset_groups.editor')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@title($title)
@extends('admin')
<form method="post" id="group-form" action="{{ $action }}">
    <div class="row">
        <div class="col-md-9">
            <div class="form-group">
                <label for="name">Közösség neve</label>
                <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control" autofocus>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="institute_id">Intézmény / plébánia</label>
                        <select name="institute_id" style="width:100%" class="form-control">
                            <option value="{{ $group->institute_id }}">
                                {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Karbantartó</label>
                        <select name="user_id" class="form-control">
                            <option value="{{ $group->user_id }}">{{ $owner->name }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leaders">Közösségvezető(k)</label>
                        <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leader_phone">Telefon</label>
                        <input type="tel" name="group_leader_phone" id="group_leader_phone" value="{{ $group->group_leader_phone }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="group_leader_email">Email cím</label>
                        <input type="email" name="group_leader_email" id="group_leader_email" value="{{ $group->group_leader_email }}" class="form-control">
                    </div>
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
            <div class="form-group">
                <label for="description">Leírás</label>
                <textarea name="description" id="description">{{ $group->description }}</textarea>
            </div>
            <div class="row group-images">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fényképek</label>
                        <div class="group-image">
                            <img src="{{ $images ? $images[0] : '' }}" id="image" width="300">
                        </div>
                        <label for="image-upload" class="btn btn-primary">
                            <i class="fa fa-upload"></i> Kép feltöltése
                            <input type="file" onchange="loadFile(event, this);" data-target="temp-image" id="image-upload">
                        </label>
                        <div style="display: none"/><img id="temp-image" /></div>
                        <input type="hidden" name="image">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 group-side-content">
            <div class="form-group">
                <label>Jóváhagyva</label>
                <div class="switch yesno" style="width:100px;">
                    <input type="radio" id="pending-0" name="pending" value="0" @if(!$group->pending) checked @endif>
                    <input type="radio" id="pending-1" name="pending" value="1" @if($group->pending) checked @endif>
                    <label for="pending-1">igen</label>
                    <label for="pending-0">nem</label>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Állapot</label>
                <select id="status" name="status" class="form-control">
                    @foreach($statuses as $status)
                    <option value="{{ $status->name }}" {{ $group->status == $status->name ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="denomination">Felekezet</label>
                <select class="form-control" name="denomination">
                    @foreach($denominations as $denomination)
                    <option value="{{ $denomination->name }}">{{ $denomination }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="age_group">Korosztály</label>
                <select class="form-control" name="age_group[]" multiple="multiple">
                    @foreach($age_groups as $age_group)
                        <option value="{{ $age_group->name }}" @if(in_array($age_group->name, $age_group_array)) selected @endif>{{ $age_group }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="occasion_frequency">Alkalmak gyakorisága</label>
                <select class="form-control" name="occasion_frequency">
                    @foreach($occasion_frequencies as $occasion_frequency)
                        <option value="{{ $occasion_frequency->name }}" @if($group->occasion_frequency == $occasion_frequency->name) selected @endif>{{ $occasion_frequency }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="on_days">Mely napo(ko)n</label>
                <select class="form-control" name="on_days[]" multiple="multiple">
                    @foreach($days as $day)
                        <option value="{{ $day }}" @if(in_array($day, $group_days)) selected @endif>
                             @lang("day.$day")
                         </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="spiritual_movement_id">Lelkiségi mozgalom</label>
                <select id="spiritual_movement_id" name="spiritual_movement_id" class="form-control">
                    <option></option>
                    @foreach($spiritual_movements as $spiritual_movement)
                        <option value="{{ $spiritual_movement['id'] }}" @if($group->spiritual_movement_id == $spiritual_movement['id']) selected @endif>
                            {{ $spiritual_movement['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
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

        $("[name=spiritual_movement_id]").select2({
            placeholder: "lelkiségi mozgalom",
            allowClear: true,
        });

        $("[name=on_days]").select2({
            placeholder: "napok",
            allowClear: true,
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

        $(".group-side-content select:not(#spiritual_movement_id, #on_days)").select2();
    });
</script>
