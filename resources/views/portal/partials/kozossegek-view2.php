<div class="row" id="kozossegek-list">
    @foreach($groups as $i => $group)
    <div class="{{ $grid_class ?? 'col-md-4' }} mb-3">
        <div class="card kozi-box h-100 p-0">
            <a href="{{ $group->url() }}" style="background: url({{ $group->getThumbnail() }}) no-repeat bottom 0 center;background-size: cover;" class="card-img">
                <div>megnézem</div>
            </a>
            <div class="card-body">
                <p class="text-center">
                    @foreach($group->tags as $tag)
                        <span class="tag-img" title="{{ $tag['tag_name'] }}" style="background: url('/images/tag/{{ $tag['tag'] }}.png'); background-size: cover;"></span>
                    @endforeach
                </p>
                <h5 class="card-title">{{ $group->name }}</h5>
                <h6>
                    {{ $group->city . ($group->district ? ', ' . $group->district : '')  }}
                </h6>
                <p class="card-text mb-0">
                    <strong>korosztály:</strong> <span>{{ $group->ageGroup() }}</span><br>
                    <strong>alkalmak:</strong> <span>{{ $group->occasionFrequency() }}</span><br>
                </p>
                <a href="{{ $group->url() }}" class="btn btn-outline-success btn-sm kozi-more-info">Megnézem</a>
            </div>
        </div>
    </div>
    @endforeach
</div>