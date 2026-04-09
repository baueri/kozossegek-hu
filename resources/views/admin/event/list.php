@title('Események')

@section('title')
    <div class="btn-group btn-group-sm btn-shadow ml-4">
        <a class="btn {{ $current_page == 'all' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.event.index')">Összes</a>
        <a class="btn {{ $current_page == 'active' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.event.index', ['lifecycle' => 'active'])">@lang('event_life_cycle.active')</a>
        <a class="btn {{ $current_page == 'cancelled' ? 'active btn-primary' : 'btn-default' }}" href="@route('admin.event.index', ['lifecycle' => 'cancelled'])">@lang('event_life_cycle.cancelled')</a>
    </div>
@endsection

@section('header')@include('asset_groups.select2')@endsection
@extends('admin')

<a href="@route('admin.event.create')" class="btn btn-primary btn-sm mb-2">@icon('plus') Új Esemény</a>

<form method="get" id="finder">
    @filter_box()
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" name="search" value="{{ $filter['search'] ?? '' }}" class="form-control" placeholder="keresés névre...">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php $status = $filter['status'] ?? ''; ?>
                <select class="form-control" id="status" name="status" data-placeholder="Állapot">
                    <option></option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($status == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <?php $lifecycle = $filter['lifecycle'] ?? ''; ?>
                <select class="form-control" id="lifecycle" name="lifecycle" data-placeholder="@lang('event_life_cycle.heading')">
                    <option></option>
                    @foreach($lifecycles as $value => $label)
                        <option value="{{ $value }}" @selected($lifecycle == $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <input type="date" name="date_from" value="{{ $filter['date_from'] ?? '' }}" class="form-control" placeholder="mettől">
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="date" name="date_to" value="{{ $filter['date_to'] ?? '' }}" class="form-control" placeholder="meddig">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <button type="submit" class="btn btn-primary btn-sm">Keresés</button>
            <a class="btn btn-default btn-sm" href="@route('admin.event.index')">Alapállapot</a>
        </div>
    </div>
    @endfilter_box
</form>

{{ $table }}

<script>
    $(() => {
        $("#status, #lifecycle").select2({
            allowClear: true,
            placeholder: $(this).data("placeholder")
        });
    });
</script>
