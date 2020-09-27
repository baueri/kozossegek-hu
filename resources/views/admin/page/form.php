@section('header')
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
@endsection

@extends('admin')

<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-md-9">
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
        <div class="col-md-3">
            <div class="form-group">
                <label>Állapot</label>
                <select name="status" class="form-control">
                    <option value="PUBLISHED" {{ $page->status == "PUBLISHED" ? "selected" : "" }}>Közzétéve</option>
                    <option value="DRAFT" {{ $page->status == "DRAFT" ? "selected" : "" }}>Piszkozat</option>
                </select>
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

    var slugGenerator;
    
    $("[name=title]").change(function () {
        clearTimeout(slugGenerator);
        if (!$("[name=slug]").val()) {

            var title = $(this);
            slugGenerator = setTimeout(function () {
                $.post("{{ route('admin.page.api.generate_slug') }}?title=" + title.val(), function (response) {
                    if (response.success) {
                        $("[name=slug]").val(response.slug);
                    }
                });
            }, 350);
        }
    });
});
</script>