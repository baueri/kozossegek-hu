@extends('admin')
<form method="get">
    <div class="row">
        <div class="col-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="tól" id="date_from" name="date_from" value="{{ $date_from }}">
                <span class="input-group-text">-</span>
                <input type="text" class="form-control" placeholder="ig" id="date_to" name="date_to" value="{{ $date_to }}">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Szűrés</button>
        </div>
    </div>
</form>
<p>
    <a href="@route('admin.event_log')">alaphelyzet</a>
</p>
{{ $table }}

<script>
    $(() => {
        $( "#date_from, #date_to").datepicker({
            dateFormat: "yy-mm-dd",
            firstDay: 1,
            maxDate: "+0d",
            showOtherMonths: true,
            showWeek: true,

        });
    });
</script>