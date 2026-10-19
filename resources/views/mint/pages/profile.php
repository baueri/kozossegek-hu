<mint-extend path="layout/inner.php" :subtitle="Profil | " :page-title="Profil" :inner-container="container-fluid" :inner-class="inner--account">

    <mint-section name="scripts">
        <script>
            $(() => {
                $("#delete-profile").confirm({
                    type: "warning",
                    message: function () {
                        return $("<div></div>").load("@route('api.portal.profile.delete_modal')");
                    },
                    isAjax: true,
                    ajaxData() {
                        return {
                            "password": $("#delete-pw").val()
                        }
                    },
                    afterResponse(response) {
                        if (response.success) {
                            dialog.show("Sikeres fióktörlés!", () => {
                                window.location.href = "@route('home')"
                            })
                        } else {
                            dialog.danger({
                                size: "sm",
                                title: "Sikertelen művelet!",
                                message: response.msg ? response.msg : "Váratlan hiba történt!"
                            });
                        }
                    }
                });

                $("#create-api-token-form").on("submit", function (e) {
                    e.preventDefault();
                    const $form = $(this);
                    const $btn = $form.find("button[type=submit]");
                    $btn.prop("disabled", true);

                    $.post($form.attr("action"), $form.serialize())
                        .done(function (response) {
                            if (!response.success) {
                                dialog.danger({
                                    size: "md",
                                    title: "Sikertelen művelet",
                                    message: response.msg || "Nem sikerült létrehozni a kulcsot."
                                });
                                return;
                            }

                            const token = response.plain_token || "";
                            const $tokenCode = $("<code class='api-token-reveal__value user-select-all'></code>").text(token);
                            const $copyBtn = $("<button type='button' class='btn btn-orange rounded-pill api-token-reveal__copy'></button>")
                                .attr({ type: "button", title: "Másolás vágólapra" })
                                .append($("<i class='fa fa-clipboard' aria-hidden='true'></i>"))
                                .append(document.createTextNode(" Másolás"));
                            const $tokenRow = $("<div class='api-token-reveal'></div>")
                                .append($("<div class='api-token-reveal__value-wrap'></div>").append($tokenCode))
                                .append($copyBtn);
                            const $copyFeedback = $("<p class='api-token-reveal__feedback d-none'></p>").text("Vágólapra másolva.");

                            $copyBtn.on("click", function () {
                                const labelDefault = " Másolás";
                                const done = function () {
                                    $copyFeedback.removeClass("d-none");
                                    $copyBtn.addClass("is-copied")
                                        .prop("disabled", true)
                                        .html("<i class=\"fa fa-check\" aria-hidden=\"true\"></i> Kimásolva");
                                    setTimeout(function () {
                                        $copyFeedback.addClass("d-none");
                                        $copyBtn.removeClass("is-copied")
                                            .prop("disabled", false)
                                            .html("<i class=\"fa fa-clipboard\" aria-hidden=\"true\"></i>" + labelDefault);
                                    }, 2000);
                                };
                                if (navigator.clipboard && navigator.clipboard.writeText) {
                                    navigator.clipboard.writeText(token).then(done).catch(function () {
                                        window.prompt("Másold ki a kulcsot:", token);
                                    });
                                } else {
                                    window.prompt("Másold ki a kulcsot:", token);
                                }
                            });

                            const $message = $("<div></div>")
                                .append($("<p class='small text-muted mb-3'></p>").text(
                                    "Kérjük, másold ki és tárold biztonságos helyen. Biztonsági okokból a kulcsot később már nem tudjuk újra megjeleníteni."
                                ))
                                .append($tokenRow)
                                .append($copyFeedback);

                            dialog.show({
                                type: "success",
                                size: "md",
                                title: "API kulcs létrehozva",
                                closable: false,
                                message: $message,
                                okBtn: { text: "Bezárás" },
                                callback: function (modal) {
                                    modal.closeAll();
                                    window.location.reload();
                                }
                            });

                            $form[0].reset();
                        })
                        .fail(function (xhr) {
                            const msg = xhr.responseJSON && xhr.responseJSON.msg
                                ? xhr.responseJSON.msg
                                : "Váratlan hiba történt a kulcs létrehozásakor.";
                            dialog.danger({ size: "md", title: "Sikertelen művelet", message: msg });
                        })
                        .always(function () {
                            $btn.prop("disabled", false);
                        });
                });

                $(document).on("submit", ".revoke-api-token-form", function (e) {
                    e.preventDefault();
                    const $form = $(this);
                    const tokenName = $form.data("token-name") || "ezt a kulcsot";

                    dialog.confirm({
                        type: "warning",
                        size: "md",
                        title: "API kulcs visszavonása",
                        message: $("<div></div>")
                            .append($("<p></p>").text("Biztosan visszavonod a(z) \"" + tokenName + "\" kulcsot?"))
                            .append($("<p class='small text-muted mb-0'></p>").text(
                                "Visszavonás után a kulcs azonnal érvénytelenné válik. A külső alkalmazások, amelyek ezt a kulcsot használják, többé nem tudják kezelni a közösségeidet, amíg új kulcsot nem állítasz be."
                            )),
                        okBtn: { text: "Igen, visszavonás", cssClass: "btn btn-warning" },
                        cancelBtn: { text: "Mégse" }
                    }, function (modal, confirmed) {
                        if (!confirmed) {
                            modal.close();
                            return;
                        }

                        modal.closeAll();

                        $.post($form.attr("action"), $form.serialize())
                            .done(function (response) {
                                if (!response.success) {
                                    dialog.danger({
                                        size: "md",
                                        title: "Sikertelen művelet",
                                        message: response.msg || "Nem sikerült visszavonni a kulcsot."
                                    });
                                    return;
                                }

                                dialog.show({
                                    type: "warning",
                                    size: "md",
                                    title: "API kulcs visszavonva",
                                    message: "A(z) \"" + tokenName + "\" kulcs törlésre került, és már nem használható."
                                }, function (successModal) {
                                    successModal.closeAll();
                                    window.location.reload();
                                });
                            })
                            .fail(function (xhr) {
                                const msg = xhr.responseJSON && xhr.responseJSON.msg
                                    ? xhr.responseJSON.msg
                                    : "Váratlan hiba történt a kulcs visszavonásakor.";
                                dialog.danger({ size: "md", title: "Sikertelen művelet", message: msg });
                            });
                    });
                });
            })
        </script>
    </mint-section>

    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            <mint-include path="partials/user-sidemenu.php" />
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
                <?php echo view('admin.partials.message'); ?>
                <form method="post" action="@route('portal.profile.update')" class="mb-0">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="account-form-section">
                                <h3 class="account-section-title">Személyes adatok</h3>
                                <div class="mb-3">
                                    <label class="form-label">E-mail cím <i class="fa fa-info-circle small text-muted" title="Amikor valaki felszeretné venni a kapcsolatot a közösséggel, erre az email címre küldjük el az érdeklődő üzenetét." aria-hidden="true"></i></label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="profile-name">Név</label>
                                    <input type="text" name="name" id="profile-name" value="{{ $user->name }}" class="form-control"/>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="phone_number">Telefonszám
                                        <i class="fa fa-info-circle small text-muted" title="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is" aria-hidden="true"></i></label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{ $user->phone_number }}" class="form-control">
                                </div>
                            </div>
                            <div class="account-form-section">
                                <h3 class="account-section-title">Jelszócsere</h3>
                                <div class="mb-3">
                                    <label class="form-label" for="old_password">Régi jelszó</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control" autocomplete="off"/>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="new_password">Új jelszó</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="new_password_again">Jelszó még egyszer</label>
                                    <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <div class="account-form-section">
                                <h3 class="account-section-title">Közösségi fiókok</h3>
                                <div class="profile-social-block">
                                    <?php if (empty($socialProfiles) || count($socialProfiles) === 0): ?>
                                        <p class="text-muted small mb-0">Nincs csatolt közösségi fiók.</p>
                                    <?php else: ?>
                                        <?php foreach ($socialProfiles as $profile): ?>
                                        <div class="profile-social-row">
                                            <div class="d-flex align-items-start justify-content-between flex-wrap">
                                                <div>
                                                    <i class="<?php echo htmlspecialchars($profile->icon(), ENT_QUOTES, 'UTF-8'); ?> text-muted me-1"></i>
                                                    <span><?php echo htmlspecialchars($profile->text(), ENT_QUOTES, 'UTF-8'); ?></span>
                                                </div>
                                                <a href="@route('portal.detach_social_profile', ['provider' => $profile->social_provider])" class="small text-danger fw-bold mt-1 mt-sm-0">
                                                    <i class="fa fa-trash-alt" aria-hidden="true"></i> Szétkapcsolás
                                                </a>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                        <button type="submit" class="btn btn-orange px-4 rounded-pill"><i class="fa fa-save" aria-hidden="true"></i> Mentés</button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="account-form-section mt-4">
                    <h3 class="account-section-title">API kulcsok</h3>
                    <p class="text-muted small">
                        Ezekkel a kulcsokkal külső alkalmazások (például egy templomi honlap) kezelhetik a közösségeidet.
                        Részletek: <a href="@route('api.docs')" target="_blank" rel="noopener noreferrer">API dokumentáció</a>.
                    </p>

                    <?php if (empty($apiTokensList) || count($apiTokensList) === 0): ?>
                        <p class="text-muted small mb-3">Még nincs API kulcsod.</p>
                    <?php else: ?>
                        <ul class="list-unstyled mb-3">
                            <?php foreach ($apiTokensList as $apiToken): ?>
                                <li class="d-flex align-items-start justify-content-between gap-2 py-2 border-bottom">
                                    <div class="small">
                                        <strong><?php echo htmlspecialchars($apiToken->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <div class="text-muted">
                                            <?php echo htmlspecialchars($apiToken->token_prefix, ENT_QUOTES, 'UTF-8'); ?>...
                                            · létrehozva: <?php echo htmlspecialchars((string) $apiToken->created_at, ENT_QUOTES, 'UTF-8'); ?>
                                            <?php if ($apiToken->last_used_at): ?>
                                                · utoljára: <?php echo htmlspecialchars((string) $apiToken->last_used_at, ENT_QUOTES, 'UTF-8'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <form method="post"
                                          action="@route('portal.api_tokens.revoke', $apiToken)"
                                          class="mb-0 revoke-api-token-form"
                                          data-token-name="<?php echo htmlspecialchars($apiToken->name, ENT_QUOTES, 'UTF-8'); ?>">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm text-danger p-0">Visszavonás</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <form method="post" action="@route('portal.api_tokens.create')" id="create-api-token-form" class="api-token-create-form">
                        @csrf
                        <label class="form-label" for="api-token-name">Új kulcs neve</label>
                        <div class="api-token-create-form__row">
                            <input type="text" name="name" id="api-token-name" class="form-control" placeholder="pl. Szent József templom honlap" maxlength="100" required>
                            <button type="submit" class="btn btn-orange rounded-pill px-4">
                                <i class="fa fa-plus" aria-hidden="true"></i> Kulcs létrehozása
                            </button>
                        </div>
                    </form>
                </div>

                <hr class="account-danger-zone-separator">

                <div class="account-danger-zone">
                    <h3 class="account-section-title">Veszélyes zóna</h3>
                    <p class="text-muted small mb-3">
                        A fiók törlése végleges. A közösségeid és a kapcsolódó adatok kezelése a törlés szabályai szerint történik.
                    </p>
                    <a href="@route('api.portal.profile.delete')" id="delete-profile" class="btn btn-outline-danger rounded-pill px-4">
                        <i class="fa fa-trash-alt" aria-hidden="true"></i> Fiókom törlése
                    </a>
                </div>
            </div>
        </div>
    </div>

</mint-extend>
