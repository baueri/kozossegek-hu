@title('Statisztika')
@extends('admin')
<div class="row">
    <div class="col-md-3">
        <form method="get" action="@route('admin.statistics')">
            <div class="form-group">
                <label>Város</label>
                <input type="text" name="varos" class="form-control" value="{{ $varos }}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Keresés</button>
            <a href="@route('admin.statistics')">Szűrés törlése</a>
        </form>
        <p class="mt-3">
            <a href="@route('api.admin.statistics.export', compact('varos'))" class="text-success">@icon('file-csv') exportálás <code>csv</code>-be</a>
        </p>
    </div>
</div>
{{ $table }}