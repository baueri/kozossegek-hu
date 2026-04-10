@extends('portal2026.portal')
@featuredTitle('Eseményeim')
<div class="container-fluid inner inner--account">
    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            @include('portal.partials.user-sidemenu')
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
                @message()
                <div class="account-page-head">
                    <p class="account-page-head__lead mb-0">Szerkeszd a saját eseményeidet; jóváhagyás után jelennek meg a nyilvános listán.</p>
                    <div class="account-toolbar">
                        <a href="@route('portal.my_event.create')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Új esemény</a>
                    </div>
                </div>
                @if(empty($items))
                    <div class="account-empty">
                        Még nincs eseményed. Hozz létre egyet a <strong>Új esemény</strong> gombbal.
                    </div>
                @else
                <div class="account-table-wrap">
                    <table class="table table-account">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" style="width:3rem"></th>
                                <th scope="col">Név</th>
                                <th scope="col">Kezdés</th>
                                <th scope="col" class="text-center">Állapot</th>
                                <th scope="col" class="text-center">@lang('event_life_cycle.heading')</th>
                                <th scope="col" class="text-center" style="width:3rem"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $event)
                            <tr>
                                <td class="text-center">
                                    @if($event->isApproved())
                                        <a href="{{ $event->getUrl() }}" class="text-muted" title="Megtekintés" target="_blank" rel="noopener">@icon('eye')</a>
                                    @else
                                        <span class="text-muted" title="Csak jóváhagyás után érhető el nyilvánosan">@icon('eye-slash')</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="@route('portal.my_event.edit', ['id' => $event->id])" title="Szerkesztés">@icon('edit') {{ $event->name }}</a>
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
                                        <i class="fa fa-calendar-alt text-success" title="@lang('event_life_cycle.active')"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="@route('portal.my_event.delete', ['id' => $event->id])" class="text-danger confirm-action" data-confirm_message="Biztosan törlöd az eseményt?" title="Törlés"><i class="fa fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
