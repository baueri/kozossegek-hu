@extends('portal')
@featuredTitle('Eseményeim')
<div class="container inner">
    <div class="row">
        <div class="col-md-3">
            @include('portal.partials.user-sidemenu')
        </div>
        <div class="col-md-9">
            @message()
            <div class="mb-4">
                <a href="@route('portal.my_event.create')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Új esemény</a>
            </div>
            <table class="table table-condensed table-striped table-responsive-md">
                <tr>
                    <td><i class="fa fa-eye"></i></td>
                    <td>Név</td>
                    <td>Kezdés</td>
                    <td class="text-center">Állapot</td>
                    <td class="text-center">@lang('event_life_cycle.heading')</td>
                    <td class="text-center"><i class="fa fa-trash-alt"></i></td>
                </tr>
                @if(empty($items))
                <tr>
                    <td colspan="6" class="text-center text-muted">Még nincs eseményed.</td>
                </tr>
                @else
                @foreach($items as $event)
                <tr>
                    <td>
                        @if($event->isApproved() && $event->lifecycle === 'active')
                            <a href="{{ $event->getUrl() }}" title="megtekintés" target="_blank" rel="noopener">@icon('eye')</a>
                        @else
                            <span class="text-muted" title="Csak jóváhagyás után érhető el nyilvánosan">@icon('eye-slash')</span>
                        @endif
                    </td>
                    <td>
                        <a href="@route('portal.my_event.edit', ['id' => $event->id])" title="szerkesztés">@icon('edit') {{ $event->name }}</a>
                    </td>
                    <td>{{ $event->getScheduleRangeLabel() }}</td>
                    <td class="text-center">
                        @if($event->status === 'approved')
                            <i class="fa fa-check text-success" title="@lang('event_status.approved')"></i>
                        @elseif($event->status === 'pending')
                            <i class="fa fa-clock-o text-muted" title="@lang('event_status.pending')"></i>
                        @elseif($event->status === 'rejected')
                            <i class="fa fa-times text-danger" title="@lang('event_status.rejected')"></i>
                        @else
                            <span class="text-muted small">@lang('event_status.' . $event->status)</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($event->lifecycle === 'cancelled')
                            <i class="fa fa-times-circle text-warning" title="@lang('event_life_cycle.cancelled')"></i>
                        @else
                            <i class="fa fa-calendar text-success" title="@lang('event_life_cycle.active')"></i>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="@route('portal.my_event.delete', ['id' => $event->id])" class="text-danger confirm-action" data-confirm_message="Biztosan törlöd az eseményt?" title="törlés"><i class="fa fa-trash-alt"></i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </table>
        </div>
    </div>
</div>
