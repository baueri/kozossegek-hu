@section('header')
    @include('asset_groups.select2')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('admin')
<form method="post" id="institute-form" action="{{ $action }}">
    @if($institute->miserend_id)
        @alert('warning')
            <b>Ez az intézmény a miserend.hu adatbázisból lett importálva.</b>
        @endalert
    @endif
    <div class="form-group">
        <label>Jóváhagyva</label>
        <div class="switch yesno" style="width:100px;">
            <input type="radio" id="approved-0" name="approved" value="1" @if($institute->approved) checked @endif>
            <input type="radio" id="approved-1" name="approved" value="0" @if(!$institute->approved) checked @endif>
            <label for="approved-1">igen</label>
            <label for="approved-0">nem</label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Intézmény / plébánia neve</label>
                <input type="text" name="name" class="form-control" value="{{ $institute->name }}">
            </div>
            <div class="form-group">
                <label>Alternatív név</label>
                <input type="text" name="name2" class="form-control" value="{{ $institute->name2 }}">
            </div>
            <div class="form-group">
                <label>Település</label>
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
            <div class="form-group">
                <label>Weboldal</label>
                <input type="text" name="website" class="form-control" value="{{ $institute->website }}">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Fénykép</label>
                <div class="institute-image">
                    <img src="{{ $institute->hasImage() ? $institute->getImageRelPath() . '?' . time() : '' }}" id="image" width="300">
                </div>
                <input type="file" onchange="loadFile(event, this);" data-target="temp-image">
                <div style="display: none"><img id="temp-image" /></div>
                <input type="hidden" name="image">
            </div>
            <div class="form-group" id="chosable_images">
                @if(isset($images))
                    <h4>Kép választása (miserend.hu-ról)</h4>
                    @foreach($images as $i => $chosable)
                    <input type="radio" name="image_url" value="{{ $chosable }}" id="{{ 'chosable_img_' . $i }}" @if($chosable === $institute->image_url) checked @endif style="display:none"/>
                    <label for="{{ 'chosable_img_' . $i }}">
                        <img src="{{ $chosable }}" style="width: 150px; height: auto"/>
                    </label>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @csrf()
</form>
<script>
    let image_val;
    $(() => {
        let upload = null;
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

        // initCroppie();
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
