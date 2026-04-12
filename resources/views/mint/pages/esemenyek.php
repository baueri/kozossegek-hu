<mint-extend path="layout/inner.php" :subtitle="Események | " :page-title="Események" :inner-class="inner--bare">

    <mint-section name="header">
        <link rel="canonical" href="@route('event.list')" />
    </mint-section>

    <mint-section name="scripts">
        <script>
        (() => {
            const select  = document.getElementById('date-select');
            const wrapper = document.getElementById('custom-date-wrapper');

            const _date          = '{{ addslashes($date ?? '') }}';
            const _selectedTypes = {{ json_encode($selectedTypes ?? []) }};
            const _isLoggedIn = {{ $is_logged_in ? 'true' : 'false' }};

            if (_date) select.value = _date;
            if (select.value === 'custom') wrapper.style.display = 'block';

            _selectedTypes.forEach(t => {
                const el = document.getElementById('type-' + t);
                if (el) el.checked = true;
            });

            select.addEventListener('change', () => {
                if (select.value === 'custom') {
                    wrapper.style.display = 'block';
                } else {
                    wrapper.style.display = 'none';
                    document.querySelector('[name="date_from"]').value = '';
                    document.querySelector('[name="date_to"]').value = '';
                }
            });

            const fab = document.querySelector('[data-open-login-modal]');
            if (fab && !_isLoggedIn) {
                fab.addEventListener('click', (e) => {
                    e.preventDefault();
                    const mid = fab.getAttribute('data-open-login-modal');
                    document.getElementById(mid)?.classList.add('active');
                });
            }

            document.querySelectorAll('[data-close-login-modal]').forEach(el => {
                el.addEventListener('click', () => {
                    el.closest('[data-mint-modal]')?.classList.remove('active');
                });
            });

            document.querySelectorAll('[data-mint-modal]').forEach(backdrop => {
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) backdrop.classList.remove('active');
                });
            });
        })();
        </script>
    </mint-section>

    <section class="search-header pb-4">
        <div class="search-card">
            <form method="get">
                <div class="row g-2 align-items-center mb-3">
                    <div class="col-lg-5">
                        <div class="search-input">
                            <i class="fas fa-search"></i>
                            <input type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Keresés (név, város...)">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="search-select">
                            <i class="fas fa-calendar-alt"></i>
                            <select name="date" id="date-select">
                                <option value="">Bármikor</option>
                                <option value="this_week">Ezen a héten</option>
                                <option value="weekend">Ezen a hétvégén</option>
                                <option value="next_week">Jövő héten</option>
                                <option value="this_month">Ebben a hónapban</option>
                                <option value="custom">Dátum megadása</option>
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

                <div id="custom-date-wrapper" class="mb-3" style="display:none;">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="search-input">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="date_from" value="{{ $date_from }}" placeholder="Mettől">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="search-input">
                                <i class="fas fa-calendar-alt"></i>
                                <input type="date" name="date_to" value="{{ $date_to }}" placeholder="Meddig">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="search-tags">
                    @foreach($eventTypeLabels as $key => $label)
                    <input type="checkbox"
                        class="event-type"
                        id="type-{{ $key }}"
                        name="types[]"
                        value="{{ $key }}"
                        hidden>
                    <label for="type-{{ $key }}" class="tag-pill">{{ $label }}</label>
                    @endforeach
                </div>
            </form>
        </div>
    </section>

    <a id="new-event-fab"
       href="@route('portal.my_event.create')"
       class="fab-create"
       data-open-login-modal="login-prompt-modal"
       aria-label="Új esemény létrehozása">
        <span class="fab-create__icon"><i class="fas fa-plus"></i></span>
        <span class="fab-create__label">Új esemény</span>
    </a>

    <mod-modal :title="Saját esemény hozzáadása" :icon="calendar-plus" :id="login-prompt-modal" :redirect="{ route('portal.my_event.create') }">
                Az esemény létrehozásához be kell jelentkezned.<br>
                Még nincs fiókod? <a href="@route('portal.register')">Regisztrálj</a>, és add hozzá eseményedet a közösség naptárához!
    </mod-modal>

    <div class="mb-3 text-muted">
        Összes találat: <strong>{{ $total }}</strong>
    </div>

    <div x:if="{ !$total }" class="text-center py-5">
        <h5 class="text-muted"><i>Sajnos nem találtunk ilyen eseményt.</i></h5>
        <p class="text-muted"><i>Próbáld meg más keresési feltételekkel.</i></p>
    </div>

    <div class="row">
        <div x:foreach="{ $events as $event }" class="col-md-6 col-lg-4 mb-5">
            <mod-event-card :event="{ $event }" />
        </div>
    </div>

    <mod-pager :page="{ $events->page() }" :total="{ $total }" :perpage="{ $events->perpage() }" />

</mint-extend>
