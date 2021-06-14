@section('header')
    @include('asset_groups.select2')
    @include('asset_groups.editor')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
@endsection
@extends('portal.group.create-steps.create-wrapper')
<form method="post" id="group-form" enctype="multipart/form-data" action="@route('portal.my_group.create')">
    @alert('warning')
        <i class="fa fa-exclamation-triangle"></i> Fontos számunkra, hogy az oldalon valóban keresztény értékeket közvetítő közösségeket hirdessünk. Mielőtt kitöltenéd a regisztrációs űrlapot, kérjük, hogy mindenképp olvasd el az <a href="/iranyelveink" target="_blank">irányelveinket</a>.
    @endalert
    @if(!is_loggedin())
        <div class="step-container">
            <h4>Felhasználói adatok</h4>
            @alert('info')
                Az itt megadott email címeddel fogsz majd a sikeres regisztráció után belépni az oldalra.
            @endalert
            <div class="row">
                <div class="col-md-4">
                    <p>
                        <a href="@route('login')" id="login-existing-user" onclick="showLoginModal('{{ request()->uri }}'); return false;"><b>
                            <i class="fa fa-key"></i> van már fiókom, belépek
                        </b></a>
                    </p>
                    <div class="form-group required">
                        <label>Neved</label>
                        <input type="text" class="form-control" name="user_name"  value="{{ $user_name }}" data-describedby="validate_user_name">
                        <div id="validate_user_name" class="validate_message"></div>
                    </div>
                    <div class="form-group required">
                        <label>Email címed</label>
                        <input type="email" class="form-control" name="email" value="{{ $email }}" data-describedby="validate_email">
                        <div id="validate_email" class="validate_message"></div>
                    </div>
                    <div class="form-group required">
                        <label>Jelszó <small>(min. 8 karakter)</small></label>
                        <input type="password" class="form-control" name="password" data-describedby="validate_password">
                        <div id="validate_password" class="validate_message"></div>
                    </div>
                    <div class="form-group required">
                        <label>Jelszó még egyszer</label>
                        <input type="password" class="form-control" name="password_again" data-describedby="validate_password_again">
                        <div id="validate_password_again" class="validate_message"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="step-container">
        <h4>Általános adatok</h4>
        <div class="form-group required">
            <label for="name">Közösség neve</label>
            <input type="text" id="name" value='{{ $group->name }}' name="name" class="form-control">
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group required">
                    <label for="institute_id">Intézmény / plébánia</label>
                    <select name="institute_id" style="width:100%" class="form-control" >
                        <option value="{{ $group->institute_id }}">
                            {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                        </option>
                    </select>
                    <small><b><a href="#" onclick="toggleInstituteBox(); return false;">+ nem találom a listában</a></b></small>
                    <div style="display: none;" id="new-institute">
                        <div class="row">
                            <div class="col-lg-10 col-md-12">
                                <div class="p-3 mb-3" style="background: #eee; border: 1px solid #ccc;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group required">
                                                <label>Plébánia / intézmény neve:</label>
                                                <input class="form-control form-control-sm institute-data" type="text" name="institute[name]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group required">
                                                <label>Plébános / intézményvezető neve:</label>
                                                <input class="form-control form-control-sm institute-data" type="text" name="institute[leader_name]">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group required">
                                                <label>Település</label>
                                                <input type="text" class="form-control form-control-sm institute-data" name="institute[city]">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Városrész</label>
                                                <input type="text" class="form-control form-control-sm institute-data" name="institute[district]">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Cím</label>
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
                <div class="form-group required">
                    <label for="age_group">Korosztály <small>(legalább egyet adj meg)</small></label>
                    @age_group_selector($age_group_array)
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group required">
                    <label for="occasion_frequency">Alkalmak gyakorisága</label>
                    @occasion_frequency_selector($group->occasion_frequency ?: 'hetente')
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="on_days">Mely napo(ko)n</label>
                    @on_days_selector($group_days)
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="on_days">Csatlakozási lehetőség módja <i class="fa fa-info-circle"
                        title="<b>Egyéni megbeszélés alapján:</b> Közösségvezetővel egyeztetve történik<br/><b>Folyamatos csatlakozási lehetőség:</b> Az év folyamán bármikor jöhetnek új tagok<br/><b>Időszakos csatlakozás:</b> pl.: Minden félév első hónapja, negyedévente stb"
                        data-html="true"></i></label>
                    @join_mode_selector($group->join_mode)
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="spiritual_movement_id">Lelkiségi mozgalom</label><br/>
            <small>Ha egy nagyobb lelkiségi mozgalomhoz tartoztok, akkor azt adjátok meg itt, így nagyobb eséllyel találnak meg azok, akik ezen mozgalom közösségeit keresik.</small>
            @spiritual_movement_selector($group->spiritual_movement_id)
        </div>
    </div>
    <div class="step-container">
        <h4>A közösség jellemzői<small style="color: red">*</small></h4>
        @alert('info')
            Válassz ki legalább egy, de legfeljebb öt tulajdonságot, ami a közösségedet a legjobban jellemzi.
        @endalert
        <div class="form-group">
            <div>
                @foreach($tags as $tag)
                <label class="mr-2" for="tag-{{ $tag['id'] }}">
                    <input type="checkbox" name="tags[]" id="tag-{{$tag['id']}}" value="{{ $tag['slug'] }}" @if(in_array($tag['slug'], $group_tags)) checked @endif> {{ $tag['tag'] }}
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="step-container required">
        <h4>Bemutatkozás</h4>
        @alert('info')
            Írd le pár mondatban azt, hogy kik vagytok, milyen jellegű közösségi alkalmakat tartotok, illetve bármilyen információt, ami vonzóvá teszi a közösségeteket mások számára.
        @endalert
        <div class="form-group required">
            <textarea name="description" id="description">{{ $group->description }}</textarea>
        </div>
    </div>
    <div class="step-container">
        <h4>Közösségvezető(k) adatai</h4>
        @alert('info')
            A felhasználói fiókon felül azért kérjük ezeket az adatokat, mert előfordulhat, hogy a közösséget menedzselő felhasználó és a közösségvezető személye eltérő. Az itt megadott email címre küldünk levelet akkor, amikor egy látogató kitölti a kapcsolatfelvevő űrlapot a közösség adatlapján.
            <br/><strong>Email címet semmilyen esetben nem jelenítünk meg a honlapon.</strong>
        @endalert
        <div class="row">
            <div class="col-md-12">
                <div class="form-group required">
                    <label for="group_leaders">Közösségvezető(k) neve(i)</label>
                    <input type="text" name="group_leaders" id="group_leaders" class="form-control" value="{{ $group->group_leaders ?: $user->name ?? '' }}" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="group_leader_phone">Elérhetőség (Telefon)</label>
                    <input type="tel" name="group_leader_phone" id="group_leader_phone" value="{{ $group->group_leader_phone }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group required">
                    <label for="group_leader_email">Elérhetőség (Email cím)</label>
                    <input type="email" name="group_leader_email" id="group_leader_email" value="{{ $group->group_leader_email ?: $user->email ?? '' }}" class="form-control" data-describedby="validate_group_leader_email">
                    <div id="validate_group_leader_email" class="validate_message"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="step-container">
        <h4>Fotó a közösségről</h4>
        <div class="row group-images">
            <div class="col-md-12">
                <div class="form-group">
                    @alert('info')
                        <b>Tölts fel egy képet a közösségedről!</b> Alapértelmezett esetben a kiválasztott intézmény fényképe jelenik meg.
                    @endalert
                    <div class="group-image">
                        <img src="{{ $image ? $image : ''}}" id="image" width="300">
                    </div>
                    <label for="image-upload" class="btn btn-primary">
                        <i class="fa fa-upload"></i> Kép kiválasztása
                        <input type="file" onchange="loadFile(event, this);" data-target="temp-image" id="image-upload">
                    </label>
                    <div style="display: none"><img id="temp-image" /></div>
                    <input type="hidden" name="image">
                </div>
            </div>
        </div>
    </div>
    <div class="step-container">
        <h4>Igazolás feltöltése</h4>
        <div class="form-group">
            @alert('info')
                <p>Nem kötelező most azonnal feltölteni, később is megteheted, de kizárólag az intézményvezető által aláírt és lepecsételt igazolással tudjuk jóváhagyni a regisztrációs kérelmet és ezáltal láthatóvá tenni a közösséget.</p>
                <p>Így tudjuk biztosítani azt, hogy a honlapunkon létező, aktív és a keresztény értékrenddel egyező közösségek legyenek.</p>
                <p>Az igazolás mintát innen tudjátok letölteni: <a href="@upload('igazolas.pdf')" download><i class="fa fa-download"></i> Igazolás minta letöltése</a></p>
            @endalert
            <p class="mb-3">
                <small>Microsoft office dokumentum (<b>doc, docx</b>), <b>pdf</b> vagy kép formátum</small><br/>
                <input type="file" name="document">
            </p>
        </div>
    </div>
    <div class="text-center">
        <button type="submit" id="preview-new-group" class="btn btn-lg btn-darkblue">Tovább</button>
    </div>
</form>
<script>
    function validateRequiredInput(selector) {
        var classSelector = selector;
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
            const pw1 = $("[name=password]");
            const pw2 = $("[name=password_again]");

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
                // "[name='age_group[]']",
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

        $("input:not([name=email]):not(.institute-data):not([type=password]):not([name=group_leader_email]), select:not([name=institute_id]), textarea", $(".required", form)).on("focusout input change", function() {
            validateRequiredInput($(this));
        });

        form.submit(async function (e, data) {

            if (typeof data === "undefined" || !data.send_request) {
                e.preventDefault();
                let baseInputsOk = validateRequiredInputs();
                let userNameOk = validateUserName();
                let userEmailOk = await validateEmailAddress($("[name=email]", form));
                let leaderEmailOk = await validateEmailAddress($("[name=group_leader_email]", form), false);
                let instituteOk = validateInstitute();
                let passwordOk = validatePassword();
                if(!baseInputsOk
                    || !userNameOk
                    || !await validateEmailAddress($("[name=email]", form))
                    || !await validateEmailAddress($("[name=group_leader_email]", form), false)
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

                            if (!$("#adatvedelmi-tajekoztato").is(":checked") || !$("#iranyelvek").is(":checked")) {
                                dialog.show("A regisztráció befejezéséhez először el kell fogadnod az adatvédelmi tájékoztatót és az irányelveinket!");
                                return;
                            }

                            form.trigger("submit", [{send_request:true}]);
                        } else {
                            modal.modal("hide");
                        }
                    });
                });
            }
        });

        $("[name=spiritual_movement_id]").select2({
            placeholder: "lelkiségi mozgalom",
            allowClear: true,
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
            if (await validateEmailAddress($(this)) && !$("#group_leader_email", form).val()) {
                $("#group_leader_email").val($(this).val());
            }
        });

        $("[name=group_leader_email]", form).on("change focusout", function () {
            validateEmailAddress($(this), false);
        });

        $("[type=password]").on("change focusout", function(){
            validatePassword();
        });

    });

    function toggleInstituteBox()
    {
        $("#new-institute").slideToggle();
        $("[name=institute_id]").val(null).trigger("change");
    }
</script>