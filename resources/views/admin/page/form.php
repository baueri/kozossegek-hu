@section('header')
    @include('asset_groups.editor')
@endsection
@extends('admin')
@if($page->deleted_at)
    @alert('danger')
        <b>Töröl oldal!</b>
    @endalert
@endif
<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Oldal címe</label>
                        <input type="text" class="form-control" name="title" value="{{ $page->title }}">
                    </div>
                </div>
                <div class="col-md-4">

                    <div class="form-group">
                        <label>(Szép) url</label>
                        <input type="text" class="form-control" name="slug" value="{{ $page->slug }}">
                    </div>
                </div>
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
                    <option value="PUBLISHED" @selected($page->status == "PUBLISHED")>Közzétéve</option>
                    <option value="DRAFT" @selected($page->status == "DRAFT")>Piszkozat</option>
                </select>
            </div>

            <div class="form-group">
                <label>Fejléc háttérkép</label>
                <input type="text" class="form-control" name="header_image" value="{{ $page->header_image }}"/>
            </div>
            <div class="form-group">
                <label>Oldaltérkép prioritás</label>
                @component('priority_selector', ['priority' => $page->priority])
            </div>
            @if($page)
                <p>
                    <a href="{{ $page->getUrl() }}" target="_blank"><i class="fa fa-eye"></i> megtekintés</a>
                </p>
            @endif
            @csrf()
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        </div>
    </div>
</form>
<script>
$(document).ready(function () {
    initSummernote('[name=content]');
    var slugGenerator;

    $("[name=title]").change(function () {
        clearTimeout(slugGenerator);
        if (!$("[name=slug]").val()) {

            var title = $(this);
            slugGenerator = setTimeout(function () {
                $.post("@route('admin.page.api.generate_slug')?title=" + title.val(), function (response) {
                    if (response.success) {
                        $("[name=slug]").val(response.slug);
                    }
                });
            }, 350);
        }
    });
});
</script>
