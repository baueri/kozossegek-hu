<div class="row">
    @foreach($groups as $i => $group)
    <div class="col-md-4 mb-3">
        <div class="card kozi-box h-100">
            <div style="width:100%; height: 200px; overflow: hidden; background: url({{ $group->getThumbnail() }}) no-repeat center; background-size: cover;">
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $group->name }}</h3>
                <p class="card-text">
                    <strong>település:</strong> <span>{{ $group->city . ($group->district ? ', ' . $group->district . '</span>' : '')  }}<br>
                    <strong>korosztály:</strong> <span>{{ $group->ageGroup() }}</span><br>
                    <strong>alkalmak:</strong> <span>{{ $group->occasionFrequency() }}</span><br>
                    <p class="text-justify">
                        {{ $group->excerpt() }}
                    </p>
                    <a href="{{ $group->url() }}" class="btn btn-outline-success btn-sm kozi-more-info">Megnézem</a>
                </p>
            </div>
        </div>
    </div>
    @endforeach
</div>