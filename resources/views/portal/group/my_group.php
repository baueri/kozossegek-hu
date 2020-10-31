@section('header')
@include('asset_groups.select2')
@include('asset_groups.editor')
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('portal')

<div class="container inner">
    <div class="row">
        <div class="col-md-3">
            @include('portal.partials.user-sidemenu')
        </div>
        <div class="col-md-9">
            
            @include('admin.partials.message')

            <h1 class="h3">Közösség módosítása</h1>
            <p>
                <a href="{{ $group->url() }}">Megtekintés</a>
            </p>
            <h3 class="h4 mt-3">Általános adatok</h3>
            <form method="post" id="group-form" action="{{ $action }}">
                <div class="row">
                    @if($group->pending)
                        @include('partials.alert', ['level' => 'warning', 'message' => 'A közösséged még függőben van, amíg nincs jóváhagyva, addig nem jelenítjük meg a közösségek között.'])
                    @elseif($group->status == App\Enums\GroupStatusEnum::INACTIVE) 
                        @include('partials.alert', ['level' => 'warning', 'message' => '<b>A közösséged jelenleg inaktív.</b><br> Nem jelenik meg sem a keresési találatok közzött, illetve az adatlapját se lehet megtekinteni.'])
                    @endif
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Állapot</label>
                            <select id="status" name="status" class="form-control">
                                @foreach($statuses as $status)
                                <option value="{{ $status->name }}" {{ $group->status == $status->name ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group required">
                    <label for="name">Közösség neve</label>
                    <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group required">
                            <label for="denomination">Felekezet</label>
                            <select class="form-control" name="denomination" required>
                                @foreach($denominations as $denomination)
                                <option value="{{ $denomination->name }}">{{ $denomination }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group required">
                            <label for="institute_id">Intézmény / plébánia</label>
                            <select name="institute_id" style="width:100%" class="form-control" required>
                                <option value="{{ $group->institute_id }}">
                                    {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
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
                                <option value="{{ $age_group->name }}" @if(in_array($age_group->name, $age_group_array)) selected @endif>{{ $age_group }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group required">
                            <label for="occasion_frequency">Alkalmak gyakorisága</label>
                            <select class="form-control" name="occasion_frequency" required>
                                @foreach($occasion_frequencies as $occasion_frequency)
                                <option value="{{ $occasion_frequency->name }}" @if($group->occasion_frequency == $occasion_frequency->name) selected @endif>{{ $occasion_frequency }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="on_days">Mely napo(ko)n</label>
                            <select class="form-control" name="on_days[]" multiple="multiple" id="on_days">
                                @foreach($days as $day)
                                <option value="{{ $day }}" @if(in_array($day, $group_days)) selected @endif>
                                    @lang("day.$day")
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
                <div class="form-group">
                    <label>A közösség jellemzői</label>
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

                <h3 class="h4 mt-3">Közösségvezető(k) adatai</h3>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group required">
                            <label for="group_leaders">Közösségvezető(k) neve(i)</label>
                            <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders ?: $user->name }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="group_leader_phone">Elérhetőség (Telefon)</label>
                            <input type="tel" name="group_leader_phone" id="group_leader_phone" value="{{ $group->group_leader_phone }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group required">
                            <label for="group_leader_email">Elérhetőség (Email cím)</label>
                            <input type="email" name="group_leader_email" id="group_leader_email" value="{{ $group->group_leader_email ?: $user->email }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row group-images">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fotó a közösségről <small>(Ha ezt nem adod meg, akkor az intézmény fotója jelenik meg)</small></label>
                            <div class="group-image">
                                <img src="{{ $images ? $images[0] . '?' . time() : '' }}" id="image" width="300">
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

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        </form>
    </div>
</div>
</div>
<script>
    $(() => {

        var image_val;

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

        $("#temp-image").on("load", function () {
            var newImg = $($(this).closest("div").html());
            $(".group-image").html(newImg);
            newImg.attr("id", "image").show();
            initCroppie();
        });

        $("form#group-form").submit(function (e) {
            if (upload) {
                upload.croppie("result", {type: "base64", format: "jpeg", size: {width: 510, height: 510}}).then(function (base64) {
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

        initSummernote('[name=description]', {
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['help']]
            ]
        });

        $("#age_group, #on_days").select2();
    });
</script>
<style>
    .required label:after {
        content: "*";
        color: red;
    }
</style>
