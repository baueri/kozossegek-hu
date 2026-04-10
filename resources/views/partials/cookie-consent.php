<div id="cookie-consent-config"
     class="d-none"
     aria-hidden="true"
     @if(is_prod())
     data-ga-id="UA-43190044-6"
     @endif
     @if(is_prod() && env('FACEBOOK_APP_ID'))
     data-fb-app-id="{{ env('FACEBOOK_APP_ID') }}"
     data-fb-locale="hu_HU"
     @endif
></div>

<div id="cookie-consent-banner"
     class="cookiealert cookie-consent-banner"
     role="region"
     aria-labelledby="cookie-consent-title"
     aria-hidden="true"
     hidden>
    <div class="container py-3">
        <h2 id="cookie-consent-title" class="h6 mb-2 text-white">Sütik és adatvédelmi beállítások</h2>
        <p class="mb-3 cookie-consent-intro">
            Weboldalunk sütiket használ. A <strong>szükséges</strong> sütik az oldal működéséhez kellenek.
            A <strong>statisztikai</strong> és <strong>közösségi</strong> jellegű funkciókat csak az Ön kifejezett hozzájárulásával kapcsoljuk be.
            A választását bármikor módosíthatja (lásd a lábléc „Cookie beállítások” linkjét).
            Részletek: <a href="@route('portal.page', 'cookie-tajekoztato')" target="_blank" rel="noopener noreferrer">Cookie tájékoztató</a>.
        </p>
        <div class="cookie-consent-actions mb-2">
            <button type="button" class="btn btn-outline-light btn-sm cookie-consent-reject">Csak a szükséges</button>
            <button type="button" class="btn btn-outline-light btn-sm cookie-consent-toggle-details">Beállítások</button>
            <button type="button" class="btn btn-altblue btn-sm cookie-consent-accept-all">Összes elfogadása</button>
        </div>
        <div class="cookie-consent-details text-start" hidden>
            <div class="cookie-consent-category mb-3 pb-3 border-bottom border-secondary">
                <div class="d-flex justify-content-between align-items-start gap-2">
                    <strong class="text-white">Szükséges</strong>
                    <span class="cookie-consent-badge">Mindig aktív</span>
                </div>
                <p class="small mb-0 mt-1 cookie-consent-muted">Az oldal alapműködéséhez, biztonsághoz és a süti-beállítás tárolásához szükségesek. Ezek nélkül a szolgáltatás nem használható megfelelően.</p>
            </div>
            <div class="cookie-consent-category cookie-consent-row-analytics mb-3 pb-3 border-bottom border-secondary" hidden>
                <div class="form-check">
                    <input class="form-check-input cookie-consent-checkbox-analytics" type="checkbox" value="1" id="cookie-consent-analytics">
                    <label class="form-check-label text-white" for="cookie-consent-analytics">Statisztika (Google Analytics)</label>
                </div>
                <p class="small mb-0 mt-1 cookie-consent-muted cookie-consent-indent">Segít megérteni, hogyan használják látogatóink az oldalt. Csak hozzájárulással töltődik be.</p>
            </div>
            <div class="cookie-consent-category cookie-consent-row-marketing mb-3" hidden>
                <div class="form-check">
                    <input class="form-check-input cookie-consent-checkbox-marketing" type="checkbox" value="1" id="cookie-consent-marketing">
                    <label class="form-check-label text-white" for="cookie-consent-marketing">Közösségi / marketing (Facebook beépülők)</label>
                </div>
                <p class="small mb-0 mt-1 cookie-consent-muted cookie-consent-indent">A Facebook megosztás és kapcsolódó modulok működéséhez. Csak hozzájárulással töltődik be.</p>
            </div>
            <div class="cookie-consent-actions">
                <button type="button" class="btn btn-altblue btn-sm cookie-consent-save">Választás mentése</button>
            </div>
        </div>
    </div>
</div>
