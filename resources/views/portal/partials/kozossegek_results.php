<div class="row">
    <div class="col-md-12 my-3">
        <form class="row">
            <div class="col-md-3 mb-4">
                <input type="text" class="form-control rounded-pill"
                       placeholder="Keresés" name="search"
                       value="{{ request()->get('search') }}" aria-label="Keresőszó"
                       data-url="@route('api.search_group')"/>
            </div>
            <div class="col-md-3 mb-2">
                <select class="form-control rounded-pill" name="korosztaly" aria-label="@lang('age_group')">
                    <option value="">-- @lang('age_group') --</option>
                    @foreach($age_groups as $age_group)
                    <option value="{{ $age_group->value }}" @selected($selected_age_group === $age_group->value)>{{ $age_group->translate() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 mb-3">
                <label>Közösség jellege</label>
                <div class="text-center text-md-left mt-2 d-flex flex-wrap">
                    @foreach($tags as $i => $tag)
                    <input type="checkbox"
                           class="group-tag"
                           style="display: none;"
                           id="tag-{{ $tag->value }}"
                           value="{{ $tag->value }}"
                           @checked(in_array($tag->value, $selected_tags))
                    style="">
                    <label for="tag-{{ $tag->value }}" aria-label="{{ $tag->translate() }}" class="mr-1 rounded-pill btn-outline-purple btn-sm d-flex align-items-center" style="height: 32px;">
<!--                        <span class="tag-img tag-{{ $tag->value }} tag-sm" aria-label="{{ $tag->translate() }}"></span>-->
                        <span class="align-middle">{{ $tag->translate() }}</span>
                    </label>
                    @endforeach
                    <input type="hidden" name="tags" value="{{ $filter['tags'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-main rounded-pill px-3" aria-label="Keresés indítása">
                    <i class="fa fa-search"></i> Keresés
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-12">
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
                <div class="card kozi-box h-100 p-0 shadow-smooth">
                    <a href="{{ $group['url'] }}" class="card-img">
                        <div>megnézem</div>
                        <img @lazySrc("/images/placeholder_rect.webp")
                             data-src="{{ $group['thumbnail'] }}"
                             data-srcset="{{ $group['thumbnail'] }}"
                             alt="{{ $group['city'] }}"
                             class="lazy">
                    </a>
                    <div class="card-body">
                        <p class="text-center mb-2" style="height: 30px;">
                            @if($group['tags'])
                            @foreach($group['tags'] as $tag_key => $tag)
                            <span class="tag-img tag-{{ $tag_key }}" title="{{ $tag }}"
                                  aria-label="{{ $tag }}" style="scale: .8; transform-origin: top; margin: 0 -1px"></span>
                            @endforeach
                            @endif
                        </p>
                        <b class="card-title">{{ $group['name'] }}</b>
                        <h6 style="color: #aaa;">
                            {{ $group['city'] . ($group['district'] ? ', ' . $group['district'] : '') }}
                        </h6>
                        <p class="card-text mb-0">
                            <strong>@lang('age_group'):</strong> <span>{{ implode(', ', $group['age_group_text']) }}</span><br>
                        </p>
                        <a href="{{ $group['url'] }}"
                           class="btn btn-outline-purple btn-sm kozi-more-info rounded-pill">Megnézem</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
