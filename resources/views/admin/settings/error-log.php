@title('Hibanapló')
@section('title')
    <div class="btn-group btn-shadow ml-3">
        <a class="btn {{ !$level ? 'btn-primary' : 'btn-default' }} btn-sm" href="@route('admin.error_log')">összes</a>
        <a class="btn {{ $level == 'FATAL' ? 'btn-primary' : 'btn-default' }} btn-sm" href="@route('admin.error_log', ['level' => 'FATAL'])">kritikus</a>
        <a class="btn {{ $level == 'WARNING' ? 'btn-primary' : 'btn-default' }} btn-sm" href="@route('admin.error_log', ['level' => 'WARNING'])">figyelmeztetés</a>
        <a class="btn {{ $level == 'NOTICE' ? 'btn-primary' : 'btn-default' }} btn-sm" href="@route('admin.error_log', ['level' => 'NOTICE'])">info</a>
        <a class="btn {{ $level == 'UNDEFINED' ? 'btn-primary' : 'btn-default' }} btn-sm" href="@route('admin.error_log', ['level' => 'UNDEFINED'])">egyéb</a>
    </div>
    <div class="ml-auto mr-3 float-right">
        <a href="@route('admin.clear_error_log')" class="btn btn-danger btn-sm">
            hibanapló törlése
        </a>
    </div>
@endsection
@extends('admin')
<table class="table table-sm">
    <thead>
        <tr><th>Dátum</th><th>Szint</th><th>Hiba</th>
    </thead>
    <tbody>
        @if($errors->isNotEmpty())
            @foreach($errors as $error)
                <tr>
                    <td style="white-space:nowrap;"><b>{{ $error['dateTime'] }}</b></td>
                    <td>
                        <a href="@route('admin.error_log', ['level' => $error['severity']])" class="badge error-level badge-{{ $error['class'] }}">{{ $error['severity'] }}</a>
                    </td>
                    <td>
                        {{ $error['error'] }}
                        <pre style="white-space: pre-wrap;" class="trace">{{ $error['stackTrace'] }}</pre>
                    </td>
            @endforeach
        @else
            <tr>
                <td colspan="3" class="text-center" style="background: #eaeaea">
                    <h5 class="p-2" style="color:#666;"><i>Hurrá, nincs hiba...</i></h5>
                </td>
            </tr>
        @endif
    </tbody>
</table>
<style>

    }
</style>
