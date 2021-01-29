@section('title')
    <small><a href="@route('admin.group.list', ['pending' => 1])">@icon('arrow-left') Vissza a listához</a></small> Közösség jóváhagyása
@endsection
@extends('admin')
<div class="row">
    <div class="col-md-12">
        <a href="@route('admin.group.edit', $group)">@icon('edit') Ugrás az adatlapra</a>
    </div>
<div class="col-md-6">
    <table style="width: 100%;">
        <tr><th>Közösség neve</th><td>{{ $group->name }}</td></tr>
        <tr><th>Intézmény</th><td> {{ $group->institute_name }} ({{ $group->city, $group->district ? ", $group->district" : "" }})</td></tr>
        <tr><th>Korosztály</th><td>{{ $group->getAgeGroups()->implode(',') }}</td></tr>
        <tr><th>Alkalmak gyakorisága</th><td>{{ $group->occasionFrequency() }}<br/></td></tr>
        <tr><th>Mely napokon</th><td> {{ $group->getDays()->implode(',') }}<br/></td></tr>
        <tr><th>Lelkiségi mozgalom</th><td> {{ $group->spiritual_movement ?: '-' }}<br/></td></tr>
        <tr><th>Csatlakozás módja</th><td> {{ $group->joinMode() ?: '-' }}<br/></td></tr>
        <tr>
            <th>Közösség jellemzői</th>
            <td>{{ $selected_tags }}</td>
        </tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Bemutatkozás</th><td>{{ $group->description }}</td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr><th>Közösségvezető(k)</th><td> {{ $group->group_leaders }}</td></tr>
        <tr><th>Elérhetőség (telefon)</th><td> {{ $group->group_leader_phone ?: '-' }}</td></tr>
        <tr><th>Elérhetőség (email)</th><td> {{ $group->group_leader_email }}</td></tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr>
            <th>Igazolás</th>
            <td>
                @if($has_document)
                    <a href="{{ $document }}">Igazolás letöltése</a>
                @else
                    nincs igazolás
                @endif
            </td>
        </tr>
    </table>
    <hr>
    <h5>Válassz egyet az alábbi műveletek közül</h5>
    <a href="" class="btn btn-success" title="Az adatokkal minden rendben van">@icon('check') Jóváhagyás</a>
    <a href="#" onclick="showRejectModal()" class="btn btn-warning" title="Hiányos / nem megfelelő adatok">@icon('times') Visszautasítás</a>
    <a href="" class="btn btn-danger" title="Irányelveknek nem megfelelő közösség ">@icon('ban') Törlés</a>
</div>
    <div class="col-md-6">
        @if($image)
        <b>Fotó:</b><br>
        <img src="{{ $image }}" style="width: 300px; height: auto" title="<img src='{{ $image }}' style='width:300px;'>" data-html="true" data-container='.group-register-preview' onload="$(this).tooltip()"/>
        @endif
    </div>
</div>
<style>
    .group-register-preview .tooltip { opacity:1!important; }
    #group-preview {

    }
    #group-preview th {
        text-align: right;
        font-weight: bold;
        padding-right: 15px;
        width: 1px;
        white-space: nowrap;
    }
    #group-preview th, #group-preview td {
        vertical-align: top;
        padding-bottom: 10px;
    }
</style>

<script>
    function showRejectModal()
    {
        $.post("@route('admin.group.reject_modal', $group)", html => {
            dialog.show({
                type: "warning",
                title: "Visszautasítás és üzenet elküldése",
                message: html,
                buttons: [
                    {
                        text: "Visszadobás és üzenet elküldése",
                        cssClass: "btn btn-warning"
                    },
                    {
                        text: "Mégse",
                        cssClass: "btn btn-default",
                        action: modal => { modal.close() }
                    }
                ]
            });
        });

    }
</script>
