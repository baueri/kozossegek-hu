@extends('portal')
<div class="container inner">
    <div class="row">
        <div class="col-md-3">
            @include('portal.partials.user-sidemenu')
        </div>
        <div class="col-md-9">
            <h1>Közösségeim</h1>
            <table class="table table-condensed table-striped">
                    <tr>
                        <td><i class="fa fa-eye"></i></td>
                        <td>Név</td>
                        <td>Település</td>
                        <td>Plébánia / intézmény</td>
                        <td>Közösségvezető(k)</td>
                        <td class="text-center">Jóváhagyva</td>
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
                            <i class="fa fa-ban text-danger"></i>
                        @else
                            <i class="fa fa-check text-success"></i>
                        @endif
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>