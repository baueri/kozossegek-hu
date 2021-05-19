@title('Vezérlőpult')
@extends('admin')
<div class="row">
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="shadow rounded bg-white p-4 h-100">
            <h6 class="text-dark border-bottom pb-1"><i class="fa fa-comments"></i> Közösségek</h6>
            <div class="row">
                <div class="col-xl-4 col-lg-12 text-center">
                    Ebben a hónapban<br/>
                    <h2>{{ $groups_this_month }}</h2>
                </div>
                <div class="col-xl-4 col-lg-12 text-center">
                    Függőben<br/>
                    <h2>
                        <a href="@route('admin.group.list', ['pending' => '1'])" class="">{{ $pending_groups }}</a>
                    </h2>
                </div>
                <div class="col-xl-4 col-lg-12 text-center">
                    Összesen<br/>
                    <h2>{{ $groups_count }}</h2>
                </div>
            </div>
            <h6 class="text-dark border-bottom pb-1 mt-4"><i class="fa fa-chart-line"></i> Statisztika <small>(ebben a hónapban)</small></h6>
            <div class="row">
                <div class="col-xl-4 col-lg-12 text-center">
                    Keresések<br/>
                    <h2>{{ $search_count_this_month }}</h2>
                </div>
                <div class="col-xl-4 col-lg-12 text-center">
                    Közi megtekintések<br/>
                    <h2>{{ $group_open_count_this_month }}</h2>
                </div>
                <div class="col-xl-4 col-lg-12 text-center">
                    Kapcsolatfelvételek<br/>
                    <h2>{{ $group_contact_count }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="shadow rounded bg-white p-4 h-100">
            <h5><i class="fa fa-plus-circle text-success"></i> Új elem létrehozása</h5>
            <div class="row">
                <div class="col-xl-6 col-lg-12"><a href="@route('admin.page.create')" class="text-center bg-lightblue p-4 rounded-lg mb-2 text-dark d-block">
                    <i class="fa fa-file-alt fs-4 text-success"></i><br/>Új Oldal
                    </a></div>
                <div class="col-xl-6 col-lg-12"><a href="@route('admin.group.create')" class="text-center bg-lightblue p-4 rounded-lg mb-2 text-dark d-block">
                    <i class="fa fa-comments fs-4 text-primary"></i><br/>Új Közösség
                    </a></div>
                <div class="col-xl-6 col-lg-12"><a href="@route('admin.institute.create')" class="text-center bg-lightblue p-4 rounded-lg mb-2 text-dark d-block">
                    <i class="fa fa-church fs-4 text-danger"></i><br/>Új Intézmény
                    </a></div>
                <div class="col-xl-6 col-lg-12"><a href="@route('admin.user.create')" class="text-center bg-lightblue p-4 rounded-lg mb-2 text-dark d-block">
                    <i class="fa fa-user fs-4 text-warning"></i><br/>Új Felhasználó
                    </a></div>
            </div>
        </div>
    </div>
    @if(site_has_error_logs())
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card text-white bg-danger mb-3 shadow">
              <div class="card-header">
                A hibanapló nem üres
              </div>
              <div class="card-body">
                <p class="card-text">
                    <b>Legutóbbi hiba ({{ $last_error['dateTime'] }}):</b>
                    {{ $last_error['error'] }}
                </p>
                <a href="@route('admin.error_log')" class="btn btn-warning">Ugrás a hibákhoz</a>
              </div>
            </div>
        </div>
    @endif
</div>
<p>Vettem borsót. Egyelőre ennyit mondok... Majd később kifejtem.</p>

