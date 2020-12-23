@extends('portal')
<div class="container inner">
    <div class="row">
        <div class="col-md-3">
            @include('portal.partials.user-sidemenu')
        </div>
        <div class="col-md-9">
            @message()
            <h2>Közösségeim <a href="@route('portal.register_group')" class="float-right btn btn-success btn-sm"><i class="fa fa-plus"></i> Új közösség</a></h2>
            <table class="table table-condensed table-striped">
                    <tr>
                        <td><i class="fa fa-eye"></i></td>
                        <td>Név</td>
                        <td>Település</td>
                        <td>Plébánia / intézmény</td>
                        <td>Közösségvezető(k)</td>
                        <td class="text-center">Jóváhagyva</td>
                        <td class="text-center"><i class="fa fa-trash-alt"></td>
                    </tr>
                @foreach($groups as $group)
                <tr>
                    <td><a href="{{ $group->url() }}" title="megekintés"><i class="fa fa-eye"></i></a></td>
                    <td><a href="{{ $group->getEditUrl() }}" title="szerkesztés">{{ $group->name }}</a></td>
                    <td>{{ $group->city }} {{ $group->district ? "<span class='text-muted small'>(" . $group->district .")</span>" : "" }}</td>
                    <td>{{ $group->institute_name }}</td>
                    <td>{{ $group->group_leaders }}</td>
                    <td class="text-center">
                        @if($group->pending)
                            <i class="fa fa-ban text-muted"></i>
                        @else
                            <i class="fa fa-check text-success"></i>
                        @endif
                    </td>
                    <td>
                        <a href="@route('portal.delete_group', $group)" class="text-danger" title="közösség törlése"><i class="fa fa-trash-alt"></i></a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
