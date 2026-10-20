<mint-extend path="layout/inner.php" :subtitle="API dokumentáció | " :page-title="API dokumentáció">

    <mint-section name="header">
        <meta name="description" content="kozossegek.hu Groups API: közösségek kezelése külső alkalmazásokból OAuth csatlakozással vagy API kulccsal." />
    </mint-section>

    <mint-section name="scripts">
        <script>
            $(() => {
                $(".api-docs__code").each(function () {
                    const $pre = $(this);
                    if ($pre.parent().hasClass("api-docs__code-block")) {
                        return;
                    }

                    const $block = $("<div class='api-docs__code-block'></div>");
                    const $btn = $("<button type='button' class='api-docs__copy'></button>")
                        .attr("title", "Másolás vágólapra")
                        .html("<i class=\"fa fa-clipboard\" aria-hidden=\"true\"></i><span> Másolás</span>");

                    $pre.before($block);
                    $block.append($btn).append($pre);

                    $btn.on("click", function () {
                        const text = $pre.find("code").text() || $pre.text();
                        const restore = function () {
                            setTimeout(function () {
                                $btn.removeClass("is-copied")
                                    .prop("disabled", false)
                                    .html("<i class=\"fa fa-clipboard\" aria-hidden=\"true\"></i><span> Másolás</span>");
                            }, 1800);
                        };
                        const done = function () {
                            $btn.addClass("is-copied")
                                .prop("disabled", true)
                                .html("<i class=\"fa fa-check\" aria-hidden=\"true\"></i><span> Kimásolva</span>");
                            restore();
                        };

                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).then(done).catch(function () {
                                window.prompt("Másold ki a példát:", text);
                            });
                        } else {
                            window.prompt("Másold ki a példát:", text);
                        }
                    });
                });
            });
        </script>
    </mint-section>

    <?php
        $siteUrl = rtrim((string) config('app.site_url'), '/');
        $apiBase = $siteUrl . '/api/v1';
        $oauthAuthorize = $siteUrl . '/oauth/authorize';
        $oauthToken = $siteUrl . '/oauth/token';
    ?>

    <div class="api-docs">
        <p class="api-docs__lead">
            A Groups API lehetővé teszi, hogy külső alkalmazások (például egy templomi honlap)
            a saját felületükről listázzák, létrehozzák, szerkesszék és töröljék a felhasználó közösségeit.
        </p>

        <section class="api-docs__section">
            <h2>Bejelentkezés és hozzáférés</h2>
            <p>
                A védett végpontokhoz Bearer tokent kell küldeni
                (<code>Authorization: Bearer kzh_...</code> formában.
                Erre kétféleképpen juthatsz: böngészős csatlakozással (OAuth), vagy személyes API kulccsal.
                Külső alkalmazásoknak, ahol a felhasználó a saját felületén kattint rá a „Csatlakozás” gombra,
                az OAuth a természetes út. Scriptekhez, cron jobokhoz vagy egyszeri integrációhoz
                praktikusabb a személyes API kulcs.
            </p>

            <h3>Csatlakozás OAuth-nal (böngészőben)</h3>
            <p>
                A felhasználó a külső alkalmazásból átkerül a kozossegek.hu oldalára, belép (ha még nincs
                bejelentkezve), majd egy hozzájárulási képernyőn engedélyezi a kapcsolatot.
                Sikeres engedélyezés után visszaküldjük egy egyszer használatos kóddal a külső alkalmazás
                <code>redirect_uri</code> címére; ezt a kódot a szerveretek cseréli le hosszú életű access tokenre.
            </p>
            <p>
                A folyamat PKCE-t használ (public client, nincs client secret). A kliens oldalon először
                generálj egy véletlen <code>code_verifier</code> értéket, majd abból számold ki az S256
                <code>code_challenge</code>-t. Ezek nélkül a token csere elutasításra kerül.
            </p>
            <ol class="api-docs__steps">
                <li>A külső alkalmazás átirányítja a felhasználót az alábbi authorize URL-re (saját
                    <code>state</code>, <code>redirect_uri</code> és <code>code_challenge</code> értékekkel).</li>
                <li>A felhasználó belép, ha kell, majd az <strong>Engedélyezés</strong> gombbal hozzájárul.
                    Elutasítás esetén <code>error=access_denied</code> kerül a redirectre.</li>
                <li>A megadott <code>redirect_uri</code>-n megérkezik a <code>code</code> és a <code>state</code>.</li>
                <li>Szerveretek a <code>/oauth/token</code> végponton, a <code>code_verifier</code>-rel együtt
                    kicseréli a kódot access tokenre.</li>
            </ol>
            <pre class="api-docs__code"><code>GET {{ $oauthAuthorize }}?response_type=code
  &amp;client_id=kozossegek-partner-sync
  &amp;redirect_uri=https://partner.example/oauth/callback
  &amp;state=RANDOM_STATE
  &amp;code_challenge=BASE64URL_SHA256_OF_VERIFIER
  &amp;code_challenge_method=S256</code></pre>
            <pre class="api-docs__code"><code>curl -X POST "{{ $oauthToken }}" \
  -H "Content-Type: application/json" \
  -d '{
  "grant_type": "authorization_code",
  "code": "AUTH_CODE",
  "redirect_uri": "https://partner.example/oauth/callback",
  "client_id": "kozossegek-partner-sync",
  "code_verifier": "CODE_VERIFIER"
}'</code></pre>
            <pre class="api-docs__code"><code>{
  "access_token": "kzh_...",
  "token_type": "Bearer",
  "expires_in": null
}</code></pre>
            <p class="api-docs__note">
                A beépített public kliens azonosítója: <code>kozossegek-partner-sync</code>.
                A <code>redirect_uri</code>-nak abszolút https címnek kell lennie.
                A tokent a felhasználó az <a href="@route('portal.integrations')">Integrációk</a> oldalon,
                a „Csatlakoztatott alkalmazások” listában is visszavonhatja,
                illetve a külső alkalmazás hívhatja a <code>POST /oauth/revoke</code> végpontot.
            </p>

            <h3>Személyes API kulcs</h3>
            <p>
                Ha nincs szükség böngészős hozzájárulásra, a fiókból is létrehozhatsz API kulcsot.
                Lépj be, nyisd meg az <a href="@route('portal.integrations')">Integrációk</a> oldalt, és az
                <strong>API kulcsok</strong> résznél adj nevet a kulcsnak (például „Szent József templom honlap”).
                A teljes kulcsot csak létrehozáskor mutatjuk meg egyszer; utána biztonságosan tárold.
            </p>
            <pre class="api-docs__code"><code>Authorization: Bearer kzh_...</code></pre>
            <p class="api-docs__note">
                A kulcs bármikor visszavonható a fiókból. Utána a kérések <code>401</code> választ kapnak.
            </p>
        </section>

        <section class="api-docs__section">
            <h2>Alap URL</h2>
            <pre class="api-docs__code"><code>{{ $apiBase }}</code></pre>
            <p class="api-docs__note">
                A kérések és a válaszok JSON formátumúak. Ha törzset küldesz, használd a
                <code>Content-Type: application/json</code> headert.
            </p>
        </section>

        <section class="api-docs__section">
            <h2>Saját közösségek</h2>
            <p class="api-docs__note">
                A példákban a <code>YOUR_TOKEN</code> helyére az OAuth-ból kapott vagy a fiókban létrehozott
                Bearer tokent írd.
            </p>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/me/groups</code></h3>
                <p>
                    Visszaadja a tokenhez tartozó felhasználó közösségeit: a sajátokat és azokat is,
                    amelyeket más nevében kezelhet.
                </p>
                <pre class="api-docs__code"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  {{ $apiBase }}/me/groups</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/me/groups/{id}</code></h3>
                <p>Egy adott közösség részletes adatait adja vissza.</p>
                <pre class="api-docs__code"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  {{ $apiBase }}/me/groups/123</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--post">POST</span> <code>/api/v1/me/groups</code></h3>
                <p>
                    Új közösséget hoz létre. A közösség jóváhagyásra váró állapotban jelenik meg,
                    amíg egy adminisztrátor el nem fogadja.
                </p>
                <p class="api-docs__note">
                    Az intézményt kétféleképpen adhatod meg: vagy egy már létező
                    <code>institute_id</code>-vel, vagy egy új <code>institute</code> objektummal,
                    ha az intézmény még nincs a listában. A kettőt egyszerre ne küldd.
                </p>
                <pre class="api-docs__code"><code>curl -X POST "{{ $apiBase }}/me/groups" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
  "name": "Ifjúsági közösség",
  "institute_id": 285,
  "description": "Rövid leírás...",
  "group_leaders": "Kovács Péter",
  "age_group": ["fiatal_felnott"],
  "occasion_frequency": "hetente",
  "on_days": ["sze"],
  "tags": ["imakor"],
  "join_mode": "egyeni",
  "spiritual_movement_id": null
}'</code></pre>
                <p class="api-docs__note">Új intézmény megadása, ha nincs a listában:</p>
                <pre class="api-docs__code"><code>curl -X POST "{{ $apiBase }}/me/groups" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
  "name": "Ifjúsági közösség",
  "institute": {
    "name": "Szent József templom",
    "leader_name": "Nagy János",
    "city": "Budapest",
    "district": "II.",
    "address": "Fő utca 1."
  },
  "description": "Rövid leírás...",
  "group_leaders": "Kovács Péter",
  "age_group": ["fiatal_felnott"],
  "occasion_frequency": "hetente",
  "on_days": ["sze"],
  "tags": ["imakor"]
}'</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--patch">PATCH</span> <code>/api/v1/me/groups/{id}</code></h3>
                <p>
                    Meglévő közösség részleges frissítése. Elég csak azokat a mezőket elküldeni,
                    amelyeket változtatni szeretnél; a többi érintetlen marad.
                </p>
                <p class="api-docs__note">
                    Írható mezők:
                    <code>name</code>,
                    <code>description</code>,
                    <code>status</code>,
                    <code>institute_id</code>,
                    <code>age_group</code>,
                    <code>occasion_frequency</code>,
                    <code>on_days</code>,
                    <code>spiritual_movement_id</code>,
                    <code>tags</code>,
                    <code>group_leaders</code>,
                    <code>join_mode</code>,
                    valamint base64 kódolt <code>image</code>.
                </p>
                <p class="api-docs__note">
                    Fontos különbség van a hiányzó mező és a szándékos <code>null</code> között:
                </p>
                <ul class="api-docs__list">
                    <li>Ha egy mezőt <strong>nem küldesz el</strong>, az értéke változatlan marad.</li>
                    <li>Ha egy mezőt <code>null</code> értékkel küldesz, azzal <strong>törlöd vagy üresre állítod</strong>
                        (például a lelkiségi mozgalom leválasztása: <code>"spiritual_movement_id": null</code>).</li>
                    <li>Ha konkrét értéket küldesz, azzal <strong>felülírod</strong> a korábbit.</li>
                </ul>
                <pre class="api-docs__code"><code>curl -X PATCH "{{ $apiBase }}/me/groups/123" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
  "name": "Frissített közösségnév",
  "description": "Frissített leírás...",
  "on_days": ["ke", "sze"]
}'</code></pre>
                <p class="api-docs__note">Példa: lelkiségi mozgalom leválasztása a közösségről</p>
                <pre class="api-docs__code"><code>curl -X PATCH "{{ $apiBase }}/me/groups/123" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
  "spiritual_movement_id": null
}'</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--delete">DELETE</span> <code>/api/v1/me/groups/{id}</code></h3>
                <p>
                    A közösséget soft-delete módon törli, ugyanúgy, mint a portál
                    „Közösségeim” oldalán.
                </p>
                <pre class="api-docs__code"><code>curl -X DELETE "{{ $apiBase }}/me/groups/123" \
  -H "Authorization: Bearer YOUR_TOKEN"</code></pre>
            </article>
        </section>

        <section class="api-docs__section">
            <h2>Webhook értesítések</h2>
            <p>
                Ha szeretnéd, hogy a közösség változásai azonnal eljussanak a külső alkalmazásotokhoz,
                regisztrálhatsz egy webhook URL-t. Ez nem kötelező: aki csak listázza vagy szerkeszti
                a közösségeket, nyugodtan kihagyhatja.
            </p>
            <p>
                A webhook mindig az aktuális Bearer tokenhez tartozik. A titkos kulcsot
                (<code>secret</code>) a külső alkalmazás generálja és küldi el; a kozossegek.hu ezzel írja alá
                a kimenő kéréseket, így a fogadó oldal ellenőrizheti, hogy az üzenet tőlünk érkezett.
            </p>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--put">PUT</span> <code>/api/v1/me/webhook</code></h3>
                <p>
                    Beállítja vagy felülírja a tokenhez tartozó webhook címet és a titkos kulcsot.
                    A válaszban a secretet nem adjuk vissza.
                </p>
                <pre class="api-docs__code"><code>curl -X PUT "{{ $apiBase }}/me/webhook" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
  "url": "https://partner.example/hooks/groups",
  "secret": "kzh_whsec_..."
}'</code></pre>
                <pre class="api-docs__code"><code>{
  "success": true,
  "data": { "url": "https://partner.example/hooks/groups" }
}</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/me/webhook</code></h3>
                <p>
                    Visszaadja a regisztrált URL-t és a hostot. A secretet biztonsági okból
                    soha nem küldjük vissza.
                </p>
                <pre class="api-docs__code"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  {{ $apiBase }}/me/webhook</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--delete">DELETE</span> <code>/api/v1/me/webhook</code></h3>
                <p>Eltávolítja a tokenről a webhook URL-t és a hozzá tartozó secretet.</p>
                <pre class="api-docs__code"><code>curl -X DELETE "{{ $apiBase }}/me/webhook" \
  -H "Authorization: Bearer YOUR_TOKEN"</code></pre>
            </article>

            <h3>Mit küldünk a webhookra?</h3>
            <p>
                Ha a tokenhez van beállítva webhook, a felhasználó számára releváns közösség
                létrehozásakor, módosításakor vagy törlésekor egy aláírt POST kérést küldünk
                a megadott címre. A lehetséges események:
            </p>
            <ul class="api-docs__list">
                <li><code>group.created</code> - új közösség jött létre</li>
                <li><code>group.updated</code> - a közösség adatai megváltoztak</li>
                <li><code>group.deleted</code> - a közösséget törölték (legalább az <code>id</code> szerepel a payloadban)</li>
            </ul>
            <p class="api-docs__note">A kérés headerei:</p>
            <pre class="api-docs__code"><code>Content-Type: application/json
X-Kozossegek-Event: group.updated
X-Kozossegek-Delivery: &lt;uuid&gt;
X-Kozossegek-Signature: sha256=&lt;hmac_sha256(raw_body, secret)&gt;</code></pre>
            <pre class="api-docs__code"><code>{
  "event": "group.updated",
  "delivered_at": "2026-10-20T16:00:00+00:00",
  "data": { "id": 123, "name": "...", "...": "..." }
}</code></pre>
            <p class="api-docs__note">
                Az aláírás ellenőrzéséhez számold ki a nyers body HMAC-SHA256 hashét a saját secretetekkel,
                és hasonlítsd össze a headerben kapott értékkel:
            </p>
            <pre class="api-docs__code"><code>$expected = 'sha256=' . hash_hmac('sha256', $rawBody, $secret);
hash_equals($expected, $signatureHeader);</code></pre>
        </section>

        <section class="api-docs__section">
            <h2>Nyilvános segédvégpontok</h2>
            <p class="api-docs__note">
                Ezekhez a végpontokhoz nem kell token. Tipikusan keresőmezőkhöz és publikus listákhoz valók.
            </p>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-institute</code></h3>
                <p>Intézményeket keres név alapján, például a közösség létrehozó vagy szerkesztő űrlaphoz.</p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-institute?search=Szent+Jozsef"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-spiritual-movement</code></h3>
                <p>
                    Lelkiségi mozgalmak és szerzetesközösségek listája.
                    Paraméter nélkül az összes elemet adja vissza; <code>search</code> (vagy <code>term</code>)
                    esetén név szerint szűr.
                </p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-spiritual-movement"</code></pre>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-spiritual-movement?search=Regnum"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-city</code></h3>
                <p>Városokat keres a megadott kifejezés alapján.</p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-city?search=Budapest"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/kozossegek</code></h3>
                <p>
                    Publikus közösséglista. Szűrhető például városra vagy szabad szöveges keresőszóra.
                </p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/kozossegek?varos=Budapest&amp;search=ima"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/miserend/{id}</code></h3>
                <p>
                    Egy intézmény adatait és az oda tartozó aktív közösségeket adja vissza
                    miserend azonosító alapján.
                </p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/miserend/12345"</code></pre>
            </article>
        </section>

        <section class="api-docs__section">
            <h2>Érvényes mezőértékek</h2>
            <div class="api-docs__fields">
                <?php foreach ($fieldValues as $field): ?>
                    <?php
                    $requiredBadge = match ($field['required'] ?? false) {
                        true, 'create' => ['Kötelező létrehozáskor', 'api-docs__badge--required'],
                        'create_alt' => ['Kötelező létrehozáskor', 'api-docs__badge--required'],
                        'update' => ['Csak frissítéskor', 'api-docs__badge--update'],
                        default => ['Opcionális', 'api-docs__badge--optional'],
                    };
                    ?>
                    <div class="api-docs__field">
                        <div class="api-docs__field-head">
                            <h3><code><?= htmlspecialchars($field['name'], ENT_QUOTES, 'UTF-8') ?></code></h3>
                            <code class="api-docs__type"><?= htmlspecialchars($field['type'], ENT_QUOTES, 'UTF-8') ?></code>
                            <span class="api-docs__badge <?= $requiredBadge[1] ?>"><?= $requiredBadge[0] ?></span>
                        </div>
                        <p class="api-docs__field-hint"><?= htmlspecialchars($field['hint'], ENT_QUOTES, 'UTF-8') ?></p>
                        <?php if ($field['values'] !== []): ?>
                            <ul class="api-docs__values">
                                <?php foreach ($field['values'] as $option): ?>
                                    <li>
                                        <code><?= htmlspecialchars($option['value'], ENT_QUOTES, 'UTF-8') ?></code>
                                        <span><?= htmlspecialchars($option['label'], ENT_QUOTES, 'UTF-8') ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="api-docs__section">
            <h2>Hibakódok</h2>
            <ul class="api-docs__list">
                <li><code>401</code> - hiányzik vagy érvénytelen a Bearer token</li>
                <li><code>403</code> - a felhasználónak nincs joga ehhez a közösséghez</li>
                <li><code>404</code> - a kért közösség nem található</li>
                <li><code>422</code> - a kérés érvénytelen (hiányzó mező, rossz fájltípus, vagy elérted a pending limitet)</li>
                <li><code>429</code> - túl sok létrehozás rövid időn belül</li>
            </ul>
        </section>

        <section class="api-docs__section">
            <h2>Sikeres válasz</h2>
            <p class="api-docs__note">A sikeres válaszok általában ilyen szerkezetűek:</p>
            <pre class="api-docs__code"><code>{
  "success": true,
  "data": { ... }
}</code></pre>
        </section>
    </div>

</mint-extend>
