<section class="search-header pb-5">

    <div class="container">
        <!-- KERESŐ KÁRTYA -->
        <div class="search-card">

            <form class="row g-3 align-items-center">

                <!-- KERESŐ -->
                <div class="col-lg-5">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text"
                            name="search"
                            value="{{ request()->get('search') }}"
                            placeholder="Keresés (város, név...)"
                            >
                    </div>
                </div>

                <!-- KOROSZTÁLY -->
                <div class="col-lg-3">
                    <div class="search-select">
                        <i class="fas fa-users"></i>
                        <select name="korosztaly">
                            <option value="">Korosztály</option>
                            @foreach($age_groups as $age_group)
                            <option value="{{ $age_group->value }}" @selected($selected_age_group===$age_group->value)>
                                {{ $age_group->translate() }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- GOMB -->
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-orange w-100 rounded-pill">
                        Keresés
                    </button>
                </div>
                <!-- TAG FILTER -->
                <div class="search-tags mt-3">

                    @foreach($tags as $tag)
                    <input type="checkbox"
                        class="group-tag"
                        id="tag-{{ $tag->value }}"
                        value="{{ $tag->value }}"
                        @checked(in_array($tag->value, $selected_tags))
                        hidden
                    >

                    <label for="tag-{{ $tag->value }}" class="tag-pill">
                        {{ $tag->translate() }}
                    </label>
                    @endforeach

                    <input type="hidden" name="tags" value="{{ $filter['tags'] }}">

                </div>
            </form>
        </div>
    </div>
</section>
<div class="row">
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
        <div class="col-md-6 col-lg-4 mb-5">
            @include('portal.partials.kozosseg_card', ['group' => $group])
        </div>
        @endforeach
    </div>
</div>
</div>
<style>
    /* HEADER */
    .search-header {
        /**background: linear-gradient(to bottom, #f8fafc, #ffffff);*/

    }

    /* CÍM */
    .search-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.4rem;
        color: #0f172a;
    }

    .search-subtitle {
        color: #64748b;
    }

    /* KÁRTYA */
    .search-card {
        background: #fff;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
        border: 3px solid #fafafa;
    }

    /* INPUT */
    .search-input {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 999px;
        padding: 10px 14px;
    }

    .search-input i {
        color: #94a3b8;
        margin-right: 8px;
    }

    .search-input input {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
    }

    /* SELECT */
    .search-select {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 999px;
        padding: 10px 14px;
    }

    .search-select i {
        color: #94a3b8;
        margin-right: 8px;
    }

    .search-select select {
        border: none;
        background: transparent;
        width: 100%;
        outline: none;
    }

    /* TAGS */
    .search-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .tag-pill {
        padding: 6px 12px;
        border-radius: 999px;
        background: #f1f5f9;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .group-tag:checked+.tag-pill {
        background: var(--orange);
        color: #fff;
    }

    /* RESULT TEXT */
    .search-results-count {
        color: #64748b;
        font-size: 0.9rem;
    }
</style>