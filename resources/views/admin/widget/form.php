
@extends('admin')
<form method="post" action="{{ $action }}">
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Név</label>
                <input type="text" name="name" class="form-control" value="{{ $widget->name }}">
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="form-group">
                <label>Egyedi azonosító</label>
                <input type="text" name="uniqid" class="form-control" value="{{ $widget->uniqid }}">
            </div>
        </div>
    </div>
    @include($form_view)
    <div class="form-group mt-3">
        <a href="@route('admin.widget.list')" class="btn btn-default">Vissza</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
    </div>
    <input type="hidden" name="type" value="{{ $type }}">
</form>

<script>
    $(() => {
    var slugGenerator;
    $("[name=name]").change(function () {
        clearTimeout(slugGenerator);
        if (!$("[name=uniqid]").val()) {
            var name = $(this);
            slugGenerator = setTimeout(function () {
                $.post("@route('admin.widget.api.generate_uniqid')?name=" + name.val(), function (response) {
                    if (response.success) {
                        $("[name=uniqid]").val(response.uniqid);
                    }
                });
            }, 350);
        }
    });
    })
</script>