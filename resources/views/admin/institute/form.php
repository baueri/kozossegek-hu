@section('header')
    @include('asset_groups.select2')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('admin')
<form method="post" id="institute-form" class="row" action="{{ $action }}">
    <div class="col-md-4">
        <div class="form-group">
            <label>Intézmény / plébánia neve</label>
            <input type="text" name="name" class="form-control" value="{{ $institute->name }}">
        </div>
        <div class="form-group">
            <label>Város</label>
            <select name="city" class="form-control">
                <option value="{{ $institute->city }}">{{ $institute->city }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Városrész</label>
            <select name="district" class="form-control">
                <option value="{{ $institute->district }}">{{ $institute->district }}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Cím</label>
            <input type="text" name="address" class="form-control" value="{{ $institute->address }}">
        </div>
        <div class="form-group">
            <label>Intézményvezető / plébános neve</label>
            <input type="text" name="leader_name" class="form-control" value="{{ $institute->leader_name }}">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Fénykép</label>
            <div class="institute-image">
                <img src="{{ '/media/institutes/inst_' . $institute->id . '.jpg' }}" id="image" >
            </div>
            <input type="file" onchange="loadFile(event, this);" data-target="temp-image">
            <div style="display: none"/><img id="temp-image" /></div>
            <input type="hidden" name="image">
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
                viewport: {
                    width: '300',
                    height: '300',
                    type: 'rectangle'
                },
                boundary: {
                    width: '300',
                    height: '300'
                }
            });
        }

        initCroppie();
        $("[name=city]").citySelect();
        $("[name=district]").districtSelect({city_selector: "[name=city]"});

        $("form#institute-form").submit(function(e){
            if (upload) {
                upload.croppie("result", {type: "base64", format: "jpeg", size: {width: 600, height: 600}}).then(function(base64){
                    image_val = base64;
                    $("[name=image]").val(base64);
                });

            }
        });

        $("#temp-image").on("load", function(){
            var newImg = $($(this).closest("div").html());
            $(".institute-image").html(newImg);
            newImg.attr("id", "image").show();
            initCroppie();
        });

    });

</script>
