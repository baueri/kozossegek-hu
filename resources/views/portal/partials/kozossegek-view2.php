<div class="row" id="kozossegek-list">
    @foreach($groups as $i => $group)
    <div class="{{ $grid_class ?? 'col-md-4' }} mb-3">
        <div class="card kozi-box h-100 p-0">
            <div style="background: url({{ $group->getThumbnail() }}) no-repeat center;background-size: cover;" class="card-img">
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $group->name }}</h5>
                <p class="card-text">
                    <strong>település:</strong> <span>{{ $group->city . ($group->district ? ', ' . $group->district . '</span>' : '')  }}<br>
                    <strong>korosztály:</strong> <span>{{ $group->ageGroup() }}</span><br>
                    <strong>alkalmak:</strong> <span>{{ $group->occasionFrequency() }}</span><br>
                </p>
                <p class="card-text text-justify mb-0">
                    {{ $group->excerpt(12) }}
                </p>

                <a href="{{ $group->url() }}" class="btn btn-outline-success btn-sm kozi-more-info">Megnézem</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
<style>
    #kozossegek-list > div {
        padding-left: 1.3em;
        padding-right: 1.3em;
    }
    #kozossegek-list {
        margin-left: -1.3em;
        margin-right: -1.3em;
    }
    #kozossegek-list .card-text {
        font-size: .9rem;
    }
    #kozossegek-list .card-title {
        text-transform: uppercase;
        font-size: .9rem;
        font-weight: bold;
    }

    #kozossegek-list .card-img {
        width:100%;
        height: 135px;
        overflow: hidden;
    }
</style>