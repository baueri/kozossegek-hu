@section('header')
    @include('asset_groups.editor')
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection
@section('title')
    @include('admin.page.title-bar')
@endsection
@extends('admin')
@if($page->deleted_at)
    @alert('danger')
        <b>Töröl oldal!</b>
    @endalert
@endif
@if($page->exists())
    <a href="@route('admin.page.create', ['page_type' => $page_type])" class="btn btn-primary btn-sm mb-2">@icon('plus') Új bejegyzés</a>
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
            @if(!$page->exists())
                <div class="form-group">
                    <label>Típus</label>
                    <select name="page_type" class="form-control">
                        <?php foreach (\App\Enums\PageType::cases() as $type): ?>
                            <option value="<?= $type->value() ?>" @selected($page_type == $type)><?= $type->translate() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            @endif
            <div class="form-group">
                <label>Oldaltérkép prioritás</label>
                @component('priority_selector', ['priority' => $page->priority])
            </div>
            <div class="form-group featured-image">
                <label>Kiemelt kép</label><br/>
                <button type="button" class="btn btn-secondary set-image mb-2">@icon('image') Kép kiválasztása</button>
                <div class="selected-image py-4">
                    <img src="{{ $page?->header_image }}" class="mb-2 image-preview"/>
                </div>
                <input type="text" class="form-control image-url" name="header_image" value="{{ $page->header_image }}"/>
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

    $(".set-image").click(() => {
        selectImageFromMediaLibrary({
            onSelect: (selected) => {
                $(".selected-image").html("<img src='" + selected.src + "'/>");
                $(".image-url").val(selected.src);
            }
        });
    });

    new Dropzone(".selected-image", {
        url: "@route('admin.content.upload.upload_file')",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        init: function () {
            this.on("success", file => {
                $(".selected-image img.image-preview").attr("src", file.xhr.response);
                $(".image-url").val(file.xhr.response);
                file.previewElement.remove();
            });
        },
        // addedfile: f => { }
    });
});
</script>
