<mint-extend
    path="layout/inner.php"
    :subtitle="Új közösség regisztrálása | "
    :page-title="Új közösség regisztrálása"
    :body-class="{ 'inner-page group-register-page' }"
>

    <mint-section name="header">
        <mint-include path="legacy::asset_groups/select2.php" />
        <mint-include path="legacy::asset_groups/editor.php" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" />
    </mint-section>

    <mint-section name="scripts">
        @if($captchaEnabled)
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif
        <script>
            const validateRequiredInput = function (selector) {
                let classSelector = selector;
                if (selector.next("span").hasClass("select2")) {
                    classSelector = selector.next("span");
                }
                classSelector.inputMessage("dismiss");
                if (selector.val() !== "") {
                    classSelector.inputOk();
                    return true;
                }
                classSelector.inputError();
                return false;
            }

            $(() => {

                const form = $("form#group-form");

                function validateUserName()
                {
                    return validateRequiredInput($("[name=user_name]", form));
                }

                async function validateEmailAddress(item, checkUnique)
                {
                    if (typeof checkUnique === "undefined") {
                        checkUnique = true;
                    }

                    if(item.length === 0) {
                        return true;
                    }

                    if (item.val() === "") {
                        item.inputError("show");
                        return false;

                    } else if (!validate_email(item.val())) {
                        item.inputError("show", "Kérjük valós email címet adj meg.");
                        return false;
                    } else if(checkUnique) {
                        item.inputError("dismiss");
                        var response = await checkEmail(item);
                        if (!response.ok) {
                            item.inputError("show", "Ez az email cím már foglalt!");
                            return false
                        }
                        item.inputError("dismiss").inputOk();
                        return true;
                    } else {
                        item.inputError("dismiss").inputOk();
                        return true;
                    }
                }

                function checkEmail(item) {
                    return $.post("@route('api.check-email')", {email: item.val()});
                }

                var upload = null;
                function initCroppie()
                {
                    upload = $("#image").croppie({
                        enableExif: true,
                        mouseWheelZoom: false,
                        viewport: {
                            width: '250',
                            height: '250',
                            type: 'rectangle'
                        },
                        boundary: {
                            width: '300',
                            height: '300'
                        }
                    });
                }

                $("#temp-image").on("load", function () {
                    var newImg = $($(this).closest("div").html());
                    $(".group-image").html(newImg);
                    newImg.attr("id", "image").show();
                    initCroppie();
                });

                function validateInstitute()
                {
                    if ($("#new-institute").is(":visible")) {
                        let ok = true;
                        let toValidate = [
                            "[name='institute[name]']",
                            "[name='institute[city]']",
                        ];
                        $(toValidate.join(", "), $(".required", form)).each(function() {
                            if (!validateRequiredInput($(this))) {
                                ok = false;
                            }
                        });

                        return ok;
                    }

                    const selector = $("[name=institute_id]");

                    return validateRequiredInput(selector);
                }

                function validatePassword()
                {
                    const pw1 = $("[name=password]", form);
                    const pw2 = $("[name=password_again]", form);

                    if (pw1.length === 0 && pw2.length === 0) {
                        return true;
                    }

                    pw1.inputError("dismiss");
                    pw2.inputError("dismiss");

                    const pw1V = pw1.val();
                    const pw2V = pw2.val();

                    if (!pw1V) {
                        pw1.inputError("show", "Nem adtál meg jelszót!");
                    } else if (pw1V.length < 8) {
                        pw1.inputError("show", "Túl rövid jelszó!");
                    } else if (pw1V !== pw2V) {
                        pw1.inputError("show");
                        pw2.inputError("show", "A két jelszó nem egyezik!");
                    } else {
                        pw1.inputOk();
                        pw2.inputOk();
                        return true;
                    }

                    return false;
                }

                function validateRequiredInputs()
                {
                    let inputOk = true;

                    let toValidate = [
                        "[name=user_name]",
                        "[name=email]",
                        "[name=name]",
                        "[name=occasion_frequency]",
                        "[name='tags[]']",
                        "[name=description]",
                        "[name=group_leaders]",
                    ];

                    $(toValidate.join(", "), $(".required", form)).each(function() {
                        if (!validateRequiredInput($(this))) {
                            inputOk = false;
                        }
                    });

                    return inputOk;
                }

                async function setupImageData() {
                    if (upload) {
                        upload.croppie("result", {type: "base64", format: "jpeg", size: {width: 510, height: 510}}).then(function (base64) {
                            $("[name=image]").val(base64);
                        });
                    }
                }

                $("input:not([name=email]):not(.institute-data):not([type=password]), select:not([name=institute_id]), textarea", $(".required", form)).on("focusout input change", function() {
                    validateRequiredInput($(this));
                });

                form.submit(async function (e, data) {

                    if (typeof data === "undefined" || !data.send_request) {
                        e.preventDefault();
                        let baseInputsOk = validateRequiredInputs();
                        let userNameOk = validateUserName();
                        let userEmailOk = await validateEmailAddress($("[name=email]", form));
                        let instituteOk = validateInstitute();
                        let passwordOk = validatePassword();
                        if(!baseInputsOk
                            || !userNameOk
                            || !await validateEmailAddress($("[name=email]", form))
                            || !validateInstitute()
                            || !validatePassword()) {
                            return dialog.danger("Kérjük ellenőrizd az adataidat! A csillaggal jelölt mezők kitöltése kötelező!");
                        }

                        await setupImageData();

                        const formData = $("form#group-form").serialize();

                        $.post("@route('api.preview_group_register')", formData, (response) => {
                            dialog.confirm({
                                title: "Adatok ellenőrzése, regisztráció befejezése",
                                message: response,
                                cancelBtn: { text: "Adatok szerkesztése"},
                                okBtn: { text: "Közösség regisztrálása"},
                                cssClass: "group-register-preview"
                            }, function (modal, confirm) {
                                if (confirm) {
                                    if (!$("#adatkezelesi-tajekoztato").is(":checked") || !$("#iranyelvek").is(":checked")) {
                                        dialog.show("A regisztráció befejezéséhez először el kell fogadnod az adatvédelmi tájékoztatót és az irányelveinket!");
                                        return;
                                    }
                                    const api = "@route('portal.my_group.create')";
                                    const serializedData = $("form#group-form").serialize();

                                    $.post(api, serializedData, "json").done(function (response) {
                                        if (response.success) {
                                            window.location.href = response.redirect;
                                        } else {
                                            dialog.danger(response.message);
                                        }
                                    }).fail(function (response) {
                                        if (response.responseJSON.err_code === 'captcha_failed') {
                                            dialog.danger(response.responseJSON.message, () => {
                                                dialog.closeAll();
                                                turnstile.reset(cf_wid)
                                            });
                                        } else {
                                            dialog.danger(response.responseJSON.message);
                                        }
                                    });
                                } else {
                                    modal.modal("hide");
                                }
                            });
                        });
                    }
                });

                $("[name=institute_id]").instituteSelect();

                initSummernote('[name=description]', {
                    height: 200,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                    ]
                });

                $("[name=user_name]").on("change, focusout", function () {
                    if (validateUserName($(this) && !$("#group_leaders", form).val())) {
                        $("#group_leaders").val($(this).val());
                    }
                });

                $("[name=email]", form).on("change focusout", async function () {
                    validateEmailAddress($(this));
                });

                $("[type=password]").on("change focusout", function(){
                    validatePassword();
                });

                const $existingImg = $("#image");
                if ($existingImg.length && $existingImg.attr("src")) {
                    initCroppie();
                }

            });

            function toggleInstituteBox()
            {
                $("#new-institute").slideToggle();
                $("[name=institute_id]").val(null).trigger("change");
            }
        </script>
        <script>
            (() => {
                const openEl = document.getElementById("login-existing-user");
                const backdrop = document.getElementById("group-register-login-modal");
                if (!openEl || !backdrop) {
                    return;
                }
                openEl.addEventListener("click", (e) => {
                    e.preventDefault();
                    backdrop.classList.add("active");
                    const email = document.getElementById("group-register-login-modal-email");
                    email?.focus();
                });
                backdrop.querySelectorAll("[data-close-login-modal]").forEach((el) => {
                    el.addEventListener("click", () => backdrop.classList.remove("active"));
                });
                backdrop.addEventListener("click", (e) => {
                    if (e.target === backdrop) {
                        backdrop.classList.remove("active");
                    }
                });
            })();
        </script>
    </mint-section>

    <div id="create-group" class="group-register">
        <mint-include path="legacy::admin/partials/message.php" />

        <div>
            <form method="post" id="group-form" class="group-register__form" enctype="multipart/form-data">
                <mod-alert :level="warning">
                    <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                    <span>Fontos számunkra, hogy az oldalon valóban keresztény értékeket közvetítő közösségeket hirdessünk. Mielőtt kitöltenéd a regisztrációs űrlapot, kérjük, hogy mindenképp olvasd el az <a href="@route('portal.page', 'iranyelveink')" target="_blank" rel="noopener noreferrer">irányelveinket</a>.</span>
                </mod-alert>
                @if(!is_loggedin())
                <div class="group-register-card">
                    <h2 class="group-register-card__title">Felhasználói adatok</h2>
                    <p class="mb-3">
                        <a href="@route('login')" id="login-existing-user" class="group-register-login-hint" role="button">
                            <i class="fas fa-key" aria-hidden="true"></i>
                            <span>Van már fiókom — belépek</span>
                        </a>
                    </p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 required">
                                <label class="form-label">Neved</label>
                                <input type="text" class="form-control" name="user_name" value="{{ $user_name }}" data-describedby="validate_user_name">
                                <div id="validate_user_name" class="validate_message"></div>
                            </div>
                            <div class="mb-3 required">
                                <label class="form-label">Email címed</label>
                                <input type="email" class="form-control" name="email" value="{{ $email }}" data-describedby="validate_email">
                                <div id="validate_email" class="validate_message"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="phone_number">Telefonszám <i class="fas fa-info-circle small text-muted" title="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is"></i></label>
                                <input type="tel" name="phone_number" id="phone_number" value="{{ $phone_number }}" class="form-control">
                            </div>
                            <div class="mb-3 required">
                                <label class="form-label">Jelszó <small class="text-muted fw-normal">(min. 8 karakter)</small></label>
                                <input type="password" class="form-control" name="password" data-describedby="validate_password">
                                <div id="validate_password" class="validate_message"></div>
                            </div>
                            <div class="mb-3 required">
                                <label class="form-label">Jelszó még egyszer</label>
                                <input type="password" class="form-control" name="password_again" data-describedby="validate_password_again">
                                <div id="validate_password_again" class="validate_message"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <mod-modal :title="Belépés" :icon="key" :id="group-register-login-modal" :redirect="{ route('portal.register_group') }" />
                @endif
                <div class="group-register-card">
                    <h2 class="group-register-card__title">Általános adatok</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3 required">
                                <label class="form-label" for="name">Közösség neve</label>
                                <input type="text" id="name" value="{{ $group->name }}" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 required">
                                <label class="form-label" for="group_leaders">Közösségvezető(k) neve(i)</label>
                                <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders ?: (($user !== null ? $user->name : null) ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3 required">
                                <label class="form-label" for="institute_id">Intézmény / plébánia</label>
                                <select name="institute_id" style="width:100%" class="form-select">
                                    <option value="{{ $group->institute_id }}">
                                        {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                                    </option>
                                </select>
                                <p class="group-register-card__toggle mb-2"><a href="#" onclick="toggleInstituteBox(); return false;">+ Nem találom a listában — új intézmény megadása</a></p>
                                <div style="display: none;" id="new-institute">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-12">
                                            <div class="group-register-card__nested mb-3">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 required">
                                                            <label class="form-label">Plébánia / intézmény neve</label>
                                                            <input class="form-control form-control-sm institute-data" type="text" name="institute[name]">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 required">
                                                            <label class="form-label">Plébános / intézményvezető neve</label>
                                                            <input class="form-control form-control-sm institute-data" type="text" name="institute[leader_name]">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="mb-3 required">
                                                            <label class="form-label">Település</label>
                                                            <input type="text" class="form-control form-control-sm institute-data" name="institute[city]">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="mb-3">
                                                            <label class="form-label">Városrész</label>
                                                            <input type="text" class="form-control form-control-sm institute-data" name="institute[district]">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Cím</label>
                                                            <input class="form-control form-control-sm institute-data" type="text" name="institute[address]">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 required">
                                <label class="form-label" for="age_group">Korosztály <small class="text-muted fw-normal">(legalább egyet adj meg)</small></label>
                                <?= (new \App\Http\Components\Selectors\AgeGroupSelector($age_group_array))->render() ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 required">
                                <label class="form-label" for="occasion_frequency">Alkalmak gyakorisága</label>
                                <?= (new \App\Http\Components\Selectors\OccasionFrequencySelector())->render($group->occasion_frequency ?: 'hetente') ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="on_days">Mely napo(ko)n</label>
                                <?= (new \App\Http\Components\Selectors\OnDaysSelector($group_days))->render() ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label" for="join_mode">Csatlakozási lehetőség módja <i class="fa fa-info-circle text-muted"
                                    title="Egyéni megbeszélés alapján: Közösségvezetővel egyeztetve történik&#10;Folyamatos csatlakozási lehetőség: Az év folyamán bármikor jöhetnek új tagok&#10;Időszakos csatlakozás: pl.: Minden félév első hónapja, negyedévente stb"></i></label>
                                <?= (new \App\Http\Components\Selectors\JoinModeSelector())->render($group->join_mode) ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="spiritual_movement_id">Lelkiségi mozgalom</label>
                        <p class="small text-muted mb-2">Ha egy nagyobb lelkiségi mozgalomhoz tartoztok, akkor azt adjátok meg itt, így nagyobb eséllyel találnak meg azok, akik ezen mozgalom közösségeit keresik.</p>
                        <?= (new \App\Http\Components\Selectors\SpiritualMovementSelector())->render($group->spiritual_movement_id) ?>
                        <mod-honeypot :id="group-data" />
                    </div>
                </div>
                <div class="group-register-card">
                    <h2 class="group-register-card__title">A közösség jellemzői <span class="text-danger">*</span></h2>
                    <div class="alert group-register__alert group-register__alert--info" role="status">
                        <i class="fas fa-tags" aria-hidden="true"></i>
                        <span>Válassz ki legalább egy, de legfeljebb öt tulajdonságot, ami a közösségedet a legjobban jellemzi.</span>
                    </div>
                    <div class="mb-3">
                        <div class="group-register-tags">
                            @foreach($tags as $tag)
                                <label class="group-register-tag" for="tag-{{ $tag->value }}">
                                    <input type="checkbox" name="tags[]" id="tag-{{ $tag->value }}" value="{{ $tag->value }}"<?= in_array($tag->value, $group_tags, true) ? ' checked' : '' ?>> {{ $tag->translate() }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <h3 class="group-register-card__subhead mt-4">Bemutatkozás <span class="text-danger">*</span></h3>
                    <div class="alert group-register__alert group-register__alert--info" role="status">
                        <i class="fas fa-pen-fancy" aria-hidden="true"></i>
                        <span>Írd le pár mondatban, hogy kik vagytok, milyen alkalmakat tartotok, és mi teszi vonzóvá a közösségeteket.</span>
                    </div>
                    <div class="mb-3 required">
                        <textarea name="description" id="description" class="form-control">{{ $group->description }}</textarea>
                    </div>
                    <h3 class="group-register-card__subhead">Fotó a közösségről</h3>
                    <div class="row group-images">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="group-register-upload">
                                    <div class="group-image group-register-photo" role="region" aria-label="Közösség kép előnézet">
                                        @if($image)
                                        <img src="{{ $image }}" id="image" width="300" alt="Közösség előnézeti képe">
                                        @else
                                        <span class="group-register-photo__placeholder" id="image-placeholder" role="img" aria-label="Még nincs kép">
                                            <i class="fas fa-image group-register-photo__placeholder-icon" aria-hidden="true"></i>
                                        </span>
                                        @endif
                                    </div>
                                    <label for="image-upload" class="btn btn-primary mb-0 group-register-upload__pick">
                                        <i class="fas fa-folder-open" aria-hidden="true"></i> Kép kiválasztása
                                        <input type="file" accept="image/*" onchange="loadFile(event, this);" data-target="temp-image" id="image-upload" class="d-none">
                                    </label>
                                </div>
                                <div class="d-none"><img id="temp-image" alt="" /></div>
                                <input type="hidden" name="image">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="group-register-card">
                    <h2 class="group-register-card__title">Igazolás feltöltése</h2>
                    <div class="mb-3">
                        <mod-alert :level="'info'">
                            <i class="fas fa-file-signature" aria-hidden="true"></i>
                            <span>
                                <p>Nem kötelező most feltölteni; később is megteheted. A közösség jóváhagyásához az intézményvezető által aláírt és lepecsételt igazolás szükséges.</p>
                                <p>Így biztosítjuk, hogy a portálon valóban aktív, keresztény értékrendű közösségek jelenjenek meg.</p>
                                <p class="mb-0">Minta: <a href="/storage/uploads/igazolas.pdf" download class="fw-semibold"><i class="fas fa-download" aria-hidden="true"></i> Igazolás minta letöltése</a></p>
                            </span>
                        </mod-alert>
                        <div class="group-register-file">
                            <label class="form-label" for="document-upload">Dokumentum</label>
                            <p class="small text-muted mb-2">Elfogadott: <strong>doc, docx</strong>, <strong>pdf</strong> vagy kép.</p>
                            <input type="file" name="document" id="document-upload" class="form-control">
                        </div>
                    </div>
                </div>
                @csrf
                <div class="group-register-card group-register-card--compact">
                    <mod-replay-attack :name="groupreg" />
                    @if($captchaEnabled)
                    <div class="mt-3">
                        <mod-captcha />
                    </div>
                    @endif
                </div>
                <div class="group-register-submit text-center">
                    <button type="submit" id="preview-new-group" class="btn btn-lg btn-orange rounded-pill px-5 shadow-sm">
                        <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>Tovább az ellenőrzéshez
                    </button>
                </div>
            </form>
        </div>
    </div>

</mint-extend>
