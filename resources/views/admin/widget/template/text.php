@include('asset_groups.editor')

<div class="row">
    <div class="col-lg-8">
        <textarea id="w_text" name="data">{{ $widget->data }}</textarea>
    </div>
</div>

<script>
    $(() => {
        initSummernote("#w_text", {
            height: 500
        });
    });
</script>
