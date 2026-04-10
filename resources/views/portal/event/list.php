@extends('portal2026.portal')
@featuredTitle('Események')
<div class="container">
<section class="search-header pb-5 mt-5">
    <div class="search-card">
        <form class="row g-3 align-items-center" method="get">

            <div class="col-lg-4">
                <div class="search-input">
                    @icon('search')
                    <input type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Keresés (név, város...)">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="search-select">
                    @icon('calendar-alt')
                    <select name="date" id="date-select">
                        <option value="">Bármikor</option>
                        <option value="today" @selected($date == 'today')>Ma</option>
                        <option value="tomorrow" @selected($date == 'tomorrow')>Holnap</option>
                        <option value="weekend" @selected($date == 'weekend')>Hétvégén</option>
                        <option value="7days" @selected($date == '7days')>Következő 7 nap</option>
                        <option value="30days" @selected($date == '30days')>Következő 30 nap</option>
                        <option value="custom" @selected($date == 'custom')>Dátum megadása</option>
                    </select>
                </div>
            </div>
            <div id="custom-date-wrapper" class="mt-2" style="display:none;">
                <div class="row g-2">

                    <div class="col-md-6">
                        <div class="search-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date"
                                name="date_from"
                                value="{{ $date_from }}"
                                placeholder="Mettől">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="search-input">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="date"
                                name="date_to"
                                value="{{ $date_to }}"
                                placeholder="Meddig">
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-2">
                <button type="submit" class="btn btn-orange w-100 rounded-pill">
                    Keresés
                </button>
            </div>

            <div class="search-tags mt-3">

                @foreach($eventTypeLabels as $key => $label)

                    <input type="checkbox"
                        class="event-type"
                        id="type-{{ $key }}"
                        name="types[]"
                        value="{{ $key }}"
                        @checked(in_array($key, $selectedTypes))
                        hidden>

                    <label for="type-{{ $key }}" class="tag-pill">
                        {{ $label }}
                    </label>

                @endforeach
            </div>

        </form>
    </div>
</section>
    <div class="row">
        <div class="col-md-12">
            <p>
                Összes találat: {{ $total }}
            </p>
            @if(!$total)
            <h5 class="text-center text-muted"><i>Sajnos nem találtunk ilyen eseményt.</i></h5>
            <h6 class="text-center text-muted"><i>Próbáld meg más keresési feltételekkel.</i></h6>
            @endif
            <div class="row">
                @foreach($events as $i => $event)
                <div class="col-md-6 col-lg-4 mb-5">
                    @include('portal.event.event_card', ['event' => $event])
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<style>
    .search-card {
    background: #fff;
    border-radius: 1.5rem;
    padding: 1.5rem;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
    border: 3px solid #f1f5f9;
}

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
.search-select {
    display: flex;
    align-items: center;
    background: #f1f5f9;
    border-radius: 999px;
    padding: 10px 14px;
    position: relative;
}

.search-select i {
    color: #94a3b8;
    margin-right: 8px;
}

/* EZ TESZI SZÉPPÉ */
.search-select select {
    border: none;
    background: transparent;
    width: 100%;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
}

/* custom nyíl */
.search-select::after {
    content: '\f078';
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    right: 14px;
    color: #94a3b8;
    pointer-events: none;
}
</style>
<script>
const select = document.getElementById('date-select');
const wrapper = document.getElementById('custom-date-wrapper');

// initial state on page load
if (select.value === 'custom') {
    wrapper.style.display = 'block';
}

select.addEventListener('change', () => {
    if (select.value === 'custom') {
        wrapper.style.display = 'block';
    } else {
        wrapper.style.display = 'none';

        // reset, hogy ne szóljon bele a szűrésbe
        document.querySelector('[name="date_from"]').value = '';
        document.querySelector('[name="date_to"]').value = '';
    }
});
</script>