<mint-extend path="layout/inner.php" :subtitle="API dokumentáció | " :page-title="API dokumentáció">

    <mint-section name="header">
        <meta name="description" content="kozossegek.hu Groups API: közösségek kezelése külső alkalmazásokból API kulccsal." />
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

    <?php $apiBase = rtrim((string) config('app.site_url'), '/') . '/api/v1'; ?>

    <div class="api-docs">
        <p class="api-docs__lead">
            A Groups API lehetővé teszi, hogy külső alkalmazások (például egy templomi honlap)
            a saját felületükről listázzák, létrehozzák, szerkesszék és töröljék a felhasználó közösségeit.
        </p>

        <section class="api-docs__section">
            <h2>Autentikáció</h2>
            <ol class="api-docs__steps">
                <li>Lépj be a kozossegek.hu fiókodba, és nyisd meg a <a href="@route('portal.my_profile')">profilod</a>.</li>
                <li>Az <strong>API kulcsok</strong> szekcióban hozz létre egy új kulcsot (pl. „Szent József templom honlap”).</li>
                <li>A kulcs <strong>csak létrehozáskor</strong> jelenik meg egyszer. Tárold biztonságosan.</li>
                <li>Minden védett kéréshez küldd el ezt a headert:</li>
            </ol>
            <pre class="api-docs__code"><code>Authorization: Bearer kzh_...</code></pre>
            <p class="api-docs__note">
                A kulcs visszavonható a profilból. Visszavonás után a kérések <code>401</code> választ kapnak.
            </p>
        </section>

        <section class="api-docs__section">
            <h2>Alap URL</h2>
            <pre class="api-docs__code"><code>{{ $apiBase }}</code></pre>
            <p class="api-docs__note">
                A kérések és válaszok JSON formátumúak. Javasolt header:
                <code>Content-Type: application/json</code>
            </p>
        </section>

        <section class="api-docs__section">
            <h2>Végpontok</h2>
            <p class="api-docs__note">A példákban a <code>YOUR_TOKEN</code> helyére a profilban létrehozott API kulcsot írd.</p>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/me/groups</code></h3>
                <p>A tokenhez tartozó felhasználó közösségeinek listája (saját és kezelt közösségek).</p>
                <pre class="api-docs__code"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  {{ $apiBase }}/me/groups</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/me/groups/{id}</code></h3>
                <p>Egy közösség részletes adatai.</p>
                <pre class="api-docs__code"><code>curl -H "Authorization: Bearer YOUR_TOKEN" \
  {{ $apiBase }}/me/groups/123</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--post">POST</span> <code>/api/v1/me/groups</code></h3>
                <p>Új közösség létrehozása. A közösség jóváhagyásra váró állapotban jön létre.</p>
                <p class="api-docs__note">
                    Korlátok: legfeljebb 3 jóváhagyásra váró közösséged lehet egyszerre,
                    és óránként legfeljebb 10 közösséget hozhatsz létre.
                </p>
                <p class="api-docs__note">
                    Intézmény megadása: két megoldás közül választhatsz.
                    Vagy meglévő <code>institute_id</code>-t küldesz, vagy új <code>institute</code> objektumot,
                    ha az intézmény nincs a listában. A kettőt egyszerre ne küldd.
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
                <p>Meglévő közösség részleges frissítése: elég csak azokat a mezőket elküldeni, amiket változtatni szeretnél.</p>
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
                    <code>image</code> (base64).
                </p>
                <p class="api-docs__note">
                    Fontos a hiányzó mező és a <code>null</code> különbsége:
                </p>
                <ul class="api-docs__list">
                    <li>Ha egy mezőt <strong>nem küldesz el</strong>, az értéke változatlan marad.</li>
                    <li>Ha egy mezőt <code>null</code> értékkel küldesz, azzal <strong>törlöd / üresre állítod</strong>
                        (például a lelkiségi mozgalom leválasztása: <code>"spiritual_movement_id": null</code>).</li>
                    <li>Ha értéket küldesz, azzal <strong>felülírod</strong> a korábbit.</li>
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
                <p>Közösség soft-delete törlése (ugyanúgy, mint a portál „Közösségeim” oldalán).</p>
                <pre class="api-docs__code"><code>curl -X DELETE "{{ $apiBase }}/me/groups/123" \
  -H "Authorization: Bearer YOUR_TOKEN"</code></pre>
            </article>
        </section>

        <section class="api-docs__section">
            <h2>Segédvégpontok (auth nélkül)</h2>
            <p class="api-docs__note">Ezekhez a végpontokhoz nem kell API kulcs.</p>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-institute</code></h3>
                <p>Intézménykereső a közösség create/update űrlapokhoz.</p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-institute?search=Szent+Jozsef"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-spiritual-movement</code></h3>
                <p>
                    Lelkiségi mozgalmak és szerzetesközösségek listája.
                    Paraméter nélkül az összes elemet adja vissza; <code>search</code> (vagy <code>term</code>) esetén név szerint szűr.
                </p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-spiritual-movement"</code></pre>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-spiritual-movement?search=Regnum"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/search-city</code></h3>
                <p>Városkereső.</p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/search-city?search=Budapest"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/kozossegek</code></h3>
                <p>Publikus közösséglista. Szűrhető például városra vagy keresőszóra.</p>
                <pre class="api-docs__code"><code>curl "{{ $apiBase }}/kozossegek?varos=Budapest&amp;search=ima"</code></pre>
            </article>

            <article class="api-docs__endpoint">
                <h3><span class="api-docs__method api-docs__method--get">GET</span> <code>/api/v1/miserend/{id}</code></h3>
                <p>Intézmény és hozzá tartozó aktív közösségek miserend azonosító alapján.</p>
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
                <li><code>401</code> - hiányzó vagy érvénytelen API kulcs</li>
                <li><code>403</code> - nincs jog a közösség szerkesztésére</li>
                <li><code>404</code> - közösség nem található</li>
                <li><code>422</code> - validációs hiba (kötelező mezők, fájltípus, pending limit)</li>
                <li><code>429</code> - túl sok létrehozás rövid időn belül (óránként max 10)</li>
            </ul>
        </section>

        <section class="api-docs__section">
            <h2>Sikeres válasz forma</h2>
            <pre class="api-docs__code"><code>{
  "success": true,
  "data": { ... }
}</code></pre>
        </section>
    </div>

</mint-extend>
