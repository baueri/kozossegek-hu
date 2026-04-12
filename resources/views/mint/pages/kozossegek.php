<mint-extend path="layout/inner.php" :subtitle="Közösségek | " :page-title="Közösségek" :breadcrumb="{$breadcrumb}" :inner-class="inner--bare">

    <mint-section name="header">
        <link rel="canonical" href="@route('portal.groups')" />
        <meta name="description" content="Közösséget keresek, keresés, jellemzők, katolikus" />
        <mod-og-image />
    </mint-section>

    <mint-section name="scripts">
        <script>
        $(() => {
            const _ageGroup = '{{ addslashes($selected_age_group ?? '') }}';
            const _tags = {{ json_encode($selected_tags ?? []) }};

            if (_ageGroup) $('[name=korosztaly]').val(_ageGroup);
            _tags.forEach(tag => $('#tag-' + tag).prop('checked', true));
            $("[name=tags]").val(_tags.join(','));

            $(".group-tag").change(function () {
                $("[name=tags]").val(
                    $(".group-tag:checked").map(function () { return $(this).val(); }).get().join(',')
                );
            });
        });
        </script>
    </mint-section>

    <section class="search-header pb-4">
        <div class="search-card">
            <form>
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-lg-6">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text"
                                name="search"
                                value="{{ $filter->get('search') }}"
                                placeholder="Keresés (város, név...)">
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="search-select">
                            <i class="fas fa-users"></i>
                            <select name="korosztaly">
                                <option value="">Korosztály</option>
                                @foreach($age_groups as $age_group)
                                <option value="{{ $age_group->value }}">{{ $age_group->translate() }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down ms-auto" style="font-size:.75rem;color:#94a3b8;pointer-events:none;"></i>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-orange w-100 rounded-pill">
                            <i class="fas fa-search me-1"></i> Keresés
                        </button>
                    </div>
                </div>
                <div class="search-tags">
                    @foreach($tags as $tag)
                    <input type="checkbox"
                        class="group-tag"
                        id="tag-{{ $tag->value }}"
                        value="{{ $tag->value }}"
                        hidden>
                    <label for="tag-{{ $tag->value }}" class="tag-pill">{{ $tag->translate() }}</label>
                    @endforeach
                    <input type="hidden" name="tags" value="{{ $filter->get('tags') }}">
                </div>
            </form>
        </div>
    </section>

    <a id="new-kozosseg-fab"
       href="@route('portal.register_group')"
       class="fab-create"
       aria-label="Új közösség regisztrálása">
        <span class="fab-create__icon"><i class="fas fa-plus"></i></span>
        <span class="fab-create__label">Új közösség</span>
    </a>

    <div class="mb-3 text-muted">
        Összes találat: <strong>{{ $total }}</strong>
    </div>

    <div x:if="{ !$total }" class="text-center py-5">
        <h5 class="text-muted"><i>Sajnos nem találtunk ilyen közösséget.</i></h5>
        <p class="text-muted"><i>Próbáld meg más keresési feltételekkel.</i></p>
    </div>

    <div class="row" id="kozossegek-list">
        <div x:foreach="{ $groups as $group }" class="col-md-6 col-lg-4 mb-5">
            <mod-kozosseg-card :group="{ $group }" />
        </div>
    </div>

    <mod-pager :page="{ $page }" :total="{ $total }" :perpage="{ $perpage }" />

</mint-extend>
