@section('header')
<!-- include summernote css/js -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection

@extends('admin')

<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-md-10">
            <div class="form-group">
                <label>Oldal címe</label>
                <input type="text" class="form-control" name="title" value="{{ $page->title }}">
            </div>
            <div class="form-group">
                <label>(Szép) url</label>
                <input type="text" class="form-control" name="slug" value="{{ $page->slug }}">
            </div>
            <div class="form-group">
                <label>Tartalom</label>
                <textarea name="content">{{ $page->content }}</textarea>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
       
</form>
<script>
$(document).ready(function () {
    $('[name=content]').summernote({
        tabsize: 2,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });
});
</script>