@section('subtitle', $spiritualMovement->name . ' - ')
@extends('portal')
@featuredTitle($title)
<div class="container inner">
    {{ $spiritualMovement->getBreadcrumb() }}
    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{ $spiritualMovement->image_url }}" alt="{{ $spiritualMovement->name }}" style="width: 350px" class="p-5 p-md-0">
        </div>
        <div class="px-3 col-md-9">
            {{ $spiritualMovement->description }}
            <p>
                <b>Weboldal: </b><a href="{{ $spiritualMovement->website }}" target="_blank">{{ $spiritualMovement->website }} @icon('external-link-alt')</a>
            </p>
            @if($groups && $groups->isNotEmpty())
            <hr/>
            <h5 class="my-5 text-center">A(z) <b>{{ $spiritualMovement->name }}</b> nálunk regisztrált kisközösségei:</h5>
            <div class="row" id="kozossegek-list">
            @foreach($groups as $i => $group)
            <div class="col-xl-4 col-md-6 mb-3">
                <div class="card kozi-box h-100 p-0">
                    <a href="{{ $group->url() }}" style="background: url({{ $group->getThumbnail() }}) no-repeat bottom 0 center;background-size: cover; height: 185px" class="card-img">
                        <div>megnézem</div>
                    </a>
                    <div class="card-body">
                        <p class="text-center">
                            @foreach($group->tags as $tag)
                            <span class="tag-img tag-{{ $tag->tag }}" title="{{ $tag->translate() }}" aria-label="{{ $tag->translate() }}"></span>
                            @endforeach
                        </p>
                        <div>{{ $group->name }}</div>
                        <div class="city">
                            {{ $group->city . ($group->district ? ', ' . $group->district : '')  }}
                        </div>
                        <p class="card-text mb-0">
                            <strong>@lang('age_group'):</strong> <span>{{ $group->ageGroup() }}</span><br>
                            <strong>@lang('occasions'):</strong> <span>{{ $group->occasionFrequency() }}</span><br>
                        </p>
                        <a href="{{ $group->url() }}" class="btn btn-outline-success btn-sm kozi-more-info">Megnézem</a>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
