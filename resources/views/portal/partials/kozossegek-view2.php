<div class="row">
    <div class="col-lg-3 col-md-12">
        <form>
            <div class="mb-3">
                <input type="text" class="form-control"
                       placeholder="" name="search"
                       value="{{ request()->get('search') }}" aria-label="Keresőszó"
                       data-url="@route('api.search_group')"/>
            </div>
            <div class="mb-3">
                <label>@lang('age_group')</label>
                <select class="form-control" name="korosztaly" aria-label="@lang('age_group')">
                    <option value="">-- @lang('age_group') --</option>
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->value }}" @selected($selected_age_group === $age_group->value)>{{ $age_group->translate() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Közösség jellege</label>
                <div>
                    @foreach($tags as $i => $tag)
<!--                        @if($i > 0 && $i % 9 == 0) <br/> @endif-->

                        <label for="tag-{{ $tag->value }}"aria-label="{{ $tag->translate() }}">
                            <input type="checkbox"
                                   class="group-tag"
                                   id="tag-{{ $tag->value }}"
                                   value="{{ $tag->value }}"
                                   @if(in_array($tag->value, $selected_tags)) checked @endif
                            style="">
                            <span class="align-middle">{{ $tag->translate() }}</span>
                        </label>
                    @endforeach
                    <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-altblue px-3 w-100" aria-label="Keresés indítása"><i
                            class="fa fa-search"></i> Keresés
                </button>
            </div>
        </form>
    </div>
    <div class="col-lg-9 col-md-12">
        <p>
            Összes találat: {{ $total }}
        </p>
        @if(!$total)
            <h5 class="text-center text-muted"><i>Sajnos nem találtunk ilyen közösséget.</i></h5>
            <h6 class="text-center text-muted"><i>Próbáld meg más keresési feltételekkel.</i></h6>
        @endif
        <div class="row" id="kozossegek-list">
            @foreach($groups as $i => $group)
            <div class="{{ $grid_class ?? 'col-md-6 col-lg-4' }} mb-3">
                <div class="card kozi-box h-100 p-0">
                    <a href="{{ $group->url() }}" class="card-img">
                        <div>megnézem</div>
                        <img src="/images/placeholder.jpg"
                             data-src="{{ $group->getThumbnail() }}"
                             data-srcset="{{ $group->getThumbnail() }}"
                             alt="{{ $group->city }}"
                             style="object-fit: cover"
                             class="lazy">
                    </a>
                    <div class="card-body">
                        <p class="text-center mb-1" style="height: 25px;">
                            @if($group->tags)
                            @foreach($group->tags as $tag)
                            <span class="tag-img tag-{{ $tag->tag }}" title="{{ $tag->translate() }}"
                                  aria-label="{{ $tag->translate() }}" style="scale: .6; transform-origin: top left; margin: 0 -3px"></span>
                            @endforeach
                            @endif
                        </p>
                        <b class="card-title">{{ $group->name }}</b>
                        <h6 style="color: #aaa;">
                            {{ $group->city . ($group->district ? ', ' . $group->district : '') }}
                        </h6>
                        <p class="card-text mb-0">
                            <strong>@lang('age_group'):</strong> <span>{{ $group->ageGroup() }}</span><br>
                            <strong>@lang('occasions'):</strong> <span>{{ $group->occasionFrequency() }}</span><br>
                        </p>
                        <a href="{{ $group->url() }}"
                           class="btn btn-outline-darkblue btn-sm kozi-more-info">Megnézem</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
