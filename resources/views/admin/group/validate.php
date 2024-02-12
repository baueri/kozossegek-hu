@section('title')
    <small><a href="@route('admin.group.list', ['pending' => 1])">@icon('arrow-left') Vissza a listához</a></small> Közösség jóváhagyása
@endsection
@extends('admin')
@if($group->pending == 0)
    @alert('danger')
        <b>Figyelem!</b><br/>
        Ez a közösség már jóvá lett hagyva!<br/>
        Csak abban az esetben módosítsd az állapotát, ha az valóban indokolt, például jelentette valaki, vagy ti vettetek észre hibát az adataiban!
    @endalert
@endif
@if(!$user->activated_at)
    @include('admin.group.partials.validation-warning')
@endif
<div class="row" id="group-preview">
    <div class="col-md-12">
        <a href="@route('admin.group.edit', $group)">@icon('edit') Ugrás az adatlapra</a>
    </div>
    <div class="col-md-6">
        <table style="width: 100%;">
            <tr><th>Közösség neve</th><td>{{ $group->name }}</td></tr>
            <tr><th>Intézmény</th><td> {{ $group->institute_name }} ({{ $group->city, $group->district ? ", $group->district" : "" }})</td></tr>
            <tr><th>Korosztály</th><td>{{ $group->allAgeGroupsAsString() }}</td></tr>
            <tr><th>Alkalmak gyakorisága</th><td>{{ $group->occasionFrequency() }}<br/></td></tr>
            <tr><th>Mely napokon</th><td> {{ $group->getDaysAsString() }}<br/></td></tr>
            <tr><th>Lelkiségi mozgalom</th><td> {{ $group->spiritual_movement ?: '-' }}<br/></td></tr>
            <tr><th>Csatlakozás módja</th><td> {{ $group->joinModeText() ?: '-' }}<br/></td></tr>
            <tr>
                <th>Közösség jellemzői</th>
                <td>{{ $selected_tags }}</td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr><th>Bemutatkozás</th><td>{{ $group->description }}</td></tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr><th>Közösségvezető(k)</th><td> {{ $group->group_leaders }}</td></tr>
            <tr><th>Elérhetőség (telefon)</th><td> {{ $user?->phone_number ?: '-' }}</td></tr>
            <tr><th>Elérhetőség (email)</th><td> {{ $user?->email }}</td></tr>
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
        @if($user->activated_at)
            <a href="" onclick="approveGroup(); return false;"  class="btn btn-success" title="Az adatokkal minden rendben van">@icon('check') Jóváhagyás</a>
        @else
            <a href="#" onclick="return false;" class="btn btn-success" style="opacity: .5; cursor:default;" title="Nem megerősített fiók közösségét nem lehet jóváhagyni!">@icon('check') Jóváhagyás</a>
        @endif
        <a href="#" onclick="showRejectModal(); return false;" class="btn btn-warning" title="Hiányos / nem megfelelő adatok">@icon('times') Visszautasítás</a>
        <a href="" onclick="showDeleteModal(); return false;" class="btn btn-danger" title="Irányelveknek nem megfelelő közösség ">@icon('trash-alt') Törlés</a>
    </div>
    <div class="col-md-6">
        @if($image)
        <b>Fotó:</b><br>
        <img src="{{ $image }}" style="width: 300px; height: auto"/>
        @endif
    </div>
</div>
<style>
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
    function approveGroup() {
        dialog.confirm({
            message: "Sikeres jóváhagyás után a közösség láthatóvá válik a látogatók számára.<br/> <b>A közösségvezető emailben értesítve lesz a jóváhagyásról.</b>",
            type: "warning",
            size: "md"
        }, function(modal, ok){
            if (ok) {
                $.post("@route('admin.api.group.approve', $group)", response => {
                    if (response.success) {
                        dialog.success("A közösség jóváhagyásra került.<br/> A közösségvezetőnek elküldtük az értesítést a jóváhagyásról", () => {
                            window.location.href = "@route('admin.group.list', ['pending' => 1])";
                        });
                    } else {
                        dialog.danger({
                            size: "sm",
                            message: response.msg
                        });
                    }
                });
            } else {
                modal.close();
            }
        });
    }
    function showRejectModal()
    {
        $.post("@route('admin.group.reject_modal', $group)", html => {
            dialog.show({
                cssClass: "group-reject-modal",
                type: "warning",
                title: "Visszautasítás és üzenet elküldése",
                message: html,
                buttons: [
                    {
                        text: "Visszadobás és üzenet elküldése",
                        cssClass: "btn btn-warning",
                        action(modal) {
                            $.post("@route('admin.api.group.reject_group', $group)", {
                                name: $("[name=name]", modal).val(),
                                email: $("[name=email]", modal).val(),
                                message: $("[name=message]", modal).val(),
                                subject: $("[name=subject]", modal).val(),
                            }, response => {
                                if (response.success) {
                                    dialog.show({
                                        size: "sm",
                                        message: "Közösség jóváhagyás visszadobva.",
                                    }, modal => {
                                        window.location.href="@route('admin.group.list', ['pending' => 1])";
                                    });
                                } else {
                                    dialog.danger({
                                        size: "sm",
                                        message: response.msg,
                                        draggable: true
                                    });
                                }
                            });
                        }
                    },
                    {
                        text: "Mégse",
                        cssClass: "btn btn-default",
                        action(modal) { modal.close() }
                    }
                ]
            });
        });
    }

    function showDeleteModal()
    {
        dialog.confirm({
            title: "Közösség törlése",
            message() {
                return $("<div></div>").load("@route('admin.group.delete_modal', $group)")
            },
            type: "danger",
        }, function(modal, confirm){
            if (confirm) {
                $.post("@route('admin.api.group.delete_by_validation', $group)", {
                    name: $("[name=name]", modal).val(),
                    email: $("[name=email]", modal).val(),
                    message: $("[name=message]", modal).val(),
                    subject: $("[name=subject]", modal).val(),
                }, response => {
                    if (response.success) {
                        dialog.success({
                            size: "sm",
                            message: "A közösség törölve lett, a közösségvezetőnek kiküldtük az értesítést."
                        }, () => {
                            window.location.href="@route('admin.group.list', ['pending' => 1])";
                        });
                    } else {
                        dialog.danger(response.msg);
                    }
                })
            }
        })
    }
</script>
