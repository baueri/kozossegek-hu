<div class="row">
    @foreach($groups as $i => $group)
    <div class="col-lg-6 col-md-12 kozi-adatlap">
        <div class="card mb-3 kozi-box">
            <div class="row no-gutters">
                <div class="col-md-4">
                    <img src="{{ $group->getThumbnail() }}" class="card-img" alt="...">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h6 class="card-title">{{ $group->name }}</h6>
                        <p class="card-text">
                            <i class="fa fa-map-marker-alt text-danger" title="város, település"></i> <span>{{ $group->city . ($group->district ? ', ' . $group->district . '</span>' : '')  }}<br>
                            <i class="fa fa-user-tie text-success" title="korosztály"></i> <span>{{ $group->ageGroup() }}</span><br>
                            <i class="fa fa-calendar-alt text-primary" title="alkalmak gyakorisága"></i> <span>{{ $group->occasionFrequency() }}</span><br>
                            <a href="{{ $group->url() }}" class="btn btn-outline-danger btn-sm kozi-more-info">Megnézem</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>