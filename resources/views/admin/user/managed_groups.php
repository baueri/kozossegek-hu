@section('header')
    @include('asset_groups.select2')
@endsection
@title("#{$user->id} {$user->name} által kezelt csoportok")
@extends('admin')
<div class="btn-group btn-group-sm btn-shadow mb-4">
    <a class="btn btn-default" href="@route('admin.user.edit', $user)">Adatlap</a>
    <a class="btn active btn-primary" href="@route('admin.user.managed_groups', $user)">Kezelt közösségek</a>
</div>
<div class="row">
    <div class="col-md-7">
        <table class="table table-sm">
            @foreach($managedGroups as $group)
            <tr>
                <td>#{{ $group->getId() }}</td>
                <td>
                    {{ $group->name }} <span class="small"><b>({{ $group->city . ', ' . $group->institute_name }})</b></span>
                </td>
                <td><a href="#" onclick="detachGroup("{{ $group->getId() }}"); return false;" title="Eltávolítás"><i class="fa fa-trash-alt text-danger"></i></a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <select id="search-groups" class="form-control"><option></option></select>
        </div>
    </div>
    <div class="col-md-3">
        <button type="button" class="btn btn-primary" id="add-group"><i class="fa fa-plus"></i> Hozzáad</button>
    </div>
</div>
<script>
    const detachGroup = function (group_id) {
        const user_id = "{{ $user->id }}";
        $.post("@route('admin.api.group_managers.remove_group')", { group_id, user_id }, r => {
            window.location.reload();
        });
    }
    $(document).ready(() => {
        $("#search-groups").baseSelect("@route('admin.api.group_managers.search_groups', $user)", {
            placeholder: "Közösség keresése",
        });

        $("#add-group").click(() => {
            const groupId = $("#search-groups").val();
            if (!groupId) {
                return;
            }

            $.post("@route('admin.api.group_managers.add_group')", {
                user_id: "{{ $user->id }}",
                group_id: groupId
            }, () => {
                window.location.reload();
            });
        });
    })
</script>