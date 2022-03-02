@title('Statisztika')
@extends('admin')
<div class="row">
    <div class="col-md-3">
        @alert('info')
            A statisztikai adatok naponta frissülnek. <a href="@route('api.admin.statistics.sync')"><b>frissítés most</b></a>
        @endalert
    </div>
</div>
<form method="get" action="@route('admin.statistics')" class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Város</label>
            <input type="text" name="varos" class="form-control" value="{{ $varos }}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Periódus</label>
            <select class="form-control" name="periodus">
                <option value="" @selected(!$periodus)>Összesen</option>
                <option value="today" @selected($periodus === 'today')>Mai nap</option>
                <option value="yesterday" @selected($periodus === 'yesterday')>Előző nap</option>
                <option value="week" @selected($periodus === 'week')>Ez a hét</option>
                <option value="month" @selected($periodus === 'month')>Ez a hónap</option>
            </select>
        </div>
    </div>
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary btn-sm">Keresés</button>
        <a href="@route('admin.statistics')" class="btn btn-default btn-sm">Alapállapot</a>
    </div>
</form>
<p class="mt-3">
    <a href="@route('api.admin.statistics.export', compact('varos'))" class="text-success">@icon('file-csv') exportálás <code>csv</code>-be</a>
</p>
<div class="row">
    <div class="col-md-6">
        {{ $table }}
    </div>
</div>