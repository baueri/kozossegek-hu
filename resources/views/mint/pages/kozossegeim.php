<mint-extend path="layout/inner.php" :subtitle="Közösségeim | " :page-title="Közösségeim" :inner-container="container-fluid" :inner-class="inner--account">

    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            <mint-include path="partials/user-sidemenu.php" />
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
                <?php echo view('admin.partials.message'); ?>
                <div class="account-page-head">
                    <p class="account-page-head__lead mb-0">Szerkeszd a közösségeid adatlapjait, vagy hozz létre újat.</p>
                    <div class="account-toolbar">
                        <a href="@route('portal.register_group')" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Új közösség</a>
                    </div>
                </div>
                <div class="account-table-wrap">
                    <table class="table table-account">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center" style="width:3rem"></th>
                                <th scope="col">Név</th>
                                <th scope="col">Település</th>
                                <th scope="col">Plébánia / intézmény</th>
                                <th scope="col">Közösségvezető(k)</th>
                                <th scope="col" class="text-center">Jóváhagyva</th>
                                <th scope="col" class="text-center" style="width:3rem"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groups as $group)
                            <tr>
                                <td class="text-center">
                                    <a href="{{ $group->url() }}" class="text-muted" title="Megtekintés"><i class="fa fa-eye"></i></a>
                                </td>
                                <td>
                                    <a href="{{ $group->getEditUrl() }}" title="Szerkesztés"><i class="fa fa-edit"></i> {{ $group->name }}</a>
                                </td>
                                <td>
                                    {{ $group->city }}@if($group->district) <span class="text-muted small">({{ $group->district }})</span>@endif
                                </td>
                                <td>{{ $group->institute_name }}</td>
                                <td>{{ $group->group_leaders }}</td>
                                <td class="text-center">
                                    @if($group->pending)
                                        <i class="fa fa-ban text-muted" title="Függőben"></i>
                                    @else
                                        <i class="fa fa-check text-success" title="Jóváhagyva"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="@route('portal.delete_group', $group)" class="text-danger confirm-action" data-confirm_message="Biztosan törlöd a közösséged?" title="Törlés"><i class="fa fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</mint-extend>
