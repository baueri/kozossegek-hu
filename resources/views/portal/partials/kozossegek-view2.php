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
<style>
    .tag-img {
        display: inline-block;
        width: 35px;
        height: 35px;
        background: #eaeaea;
        border-radius: 50%;
        margin: 0 5px;
        transition: box-shadow ease-in .1s;
    }

    .tag-img:hover {
        box-shadow: 0 0 6px #555;
    }

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
        font-weight: bold;
    }

    #kozossegek-list h6 {
        color:#aaa;
    }

    #kozossegek-list h6:after {
        content: " ";
        display: block;
        border-bottom: 1px solid #ccc;
        margin-top: 1em;
        width: 100px;
    }

    #kozossegek-list .card-img {
        width:100%;
        height: 257px;
        overflow: hidden;
        position: relative;
    }

    #kozossegek-list .card-img div {
        opacity: 0;
        margin: 0;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        color: #fff;
        text-align: center;
        padding: 1em 2em;
        font-weight: bold;
        border: 3px solid #fff;
        transition: opacity ease-in .2s;
    }
    #kozossegek-list .card-img:before {
        opacity: 0;
        content: " ";
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.4);
        transition: opacity ease-in .1s;
    }

    #kozossegek-list .card-img:hover:before {
        opacity: 1;
    }

    #kozossegek-list .card-img:hover div {
        opacity: 1;
    }
</style>