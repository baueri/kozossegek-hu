@header()
    @og_image($spiritualMovement->image_url)
@endheader
@section('subtitle', $spiritualMovement->name . ' - ')
@extends('portal')
@featuredTitle()
    {{ $spiritualMovement->getBreadcrumb() }}
    <h3 class="py-3 mb-0">{{ $title }}</h3>
@endfeaturedTitle
<div class="container inner">

    <div class="row">
        <div class="col-md-3 text-center">
            <img src="{{ $spiritualMovement->image_url }}" alt="{{ $spiritualMovement->name }}" style="width: 350px" class="p-5 p-md-0">
        </div>
        <div class="px-3 col-md-9">
            {{ $spiritualMovement->description }}
            @if($spiritualMovement->website)
                <p>
                    <b>Weboldal: </b><a href="{{ $spiritualMovement->website }}" target="_blank">{{ $spiritualMovement->website }} @icon('external-link-alt')</a>
                </p>
            @endif
            @if($groups && $groups->isNotEmpty())
            <h5 class="my-5 text-center">A(z) <b>{{ $spiritualMovement->name }}</b> nálunk regisztrált kisközösségei:</h5>
            <div class="row" id="kozossegek-list">
            @foreach($groups as $i => $group)
            <div class="col-xl-4 col-md-6 mb-3">
                <div class="card kozi-box h-100 p-0 shadow-smooth">
                    <a href="{{ $group->url() }}" class="card-img">
                        <div>megnézem</div>
                        <img @lazySrc()
                             data-src="{{ $group->getThumbnail() }}"
                             data-srcset="{{ $group->getThumbnail() }}"
                             alt="{{ $group->name }}"
                             style="object-fit: cover"
                             class="lazy">
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
                        <a href="{{ $group->url() }}" class="btn btn-outline-purple btn-sm kozi-more-info rounded-pill">Megnézem</a>
                    </div>
                </div>
            </div>
            @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
