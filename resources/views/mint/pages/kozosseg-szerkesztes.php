<mint-extend
    path="layout/inner.php"
    :subtitle="Közösség módosítása | "
    :page-title="Közösség módosítása"
    :inner-container="container-fluid"
    :inner-class="inner--account"
    :body-class="{ 'inner-page group-register-page' }"
>

    <mint-section name="header">
        <mint-include path="legacy::asset_groups/select2.php" />
        <mint-include path="legacy::asset_groups/editor.php" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css"/>
    </mint-section>

    <mint-section name="scripts">
<script>
    $(() => {

        var image_val;

        var upload = null;

        function initCroppie() {
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

        $("form#group-form").submit(function (e) {
            if (upload) {
                upload.croppie("result", {
                    type: "base64",
                    format: "jpeg",
                    size: {width: 510, height: 510}
                }).then(function (base64) {
                    image_val = base64;
                    $("[name=image]").val(base64);
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

        $("#age_group").select2();
    });
</script>
    </mint-section>

    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            <mint-include path="legacy::portal/partials/user-sidemenu.php" />
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
            <div id="create-group" class="group-register">
                <mint-include path="legacy::admin/partials/message.php" />

                <form method="post" id="group-form" class="group-register__form" action="@route('portal.my_group.update', $group)" enctype="multipart/form-data">
                <div class="group-register-card">
                    <h2 class="group-register-card__title">Általános adatok</h2>

                    <p x:if="{$group->exists()}" class="mb-3">
                        <a href="{{ $group->url() }}" class="fw-medium" target="_blank" rel="noopener noreferrer"><i class="fas fa-eye" aria-hidden="true"></i> Megtekintés nyilvános oldal</a>
                    </p>

                    @if($group->pending == 1)
                        <mod-alert :level="'warning'">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <span><b>A közösséged még függőben van.</b> Amíg nincs jóváhagyva, addig nem jelenítjük meg a közösségek között.</span>
                        </mod-alert>
                    @elseif($group->isRejected())
                        <mod-alert :level="'warning'">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <span><b>A közösséged vissza lett dobva.</b> Nézd meg az adataidat, hogy minden rendben van-e, majd kattints a mentés gombra.</span>
                        </mod-alert>
                    @elseif($group->status == App\Enums\GroupStatus::inactive)
                        <mod-alert :level="'warning'">
                            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
                            <span><b>A közösséged jelenleg inaktív.</b> Nem jelenik meg a keresési találatok között, és az adatlapját sem lehet megtekinteni.</span>
                        </mod-alert>
                    @endif

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label" for="status">Állapot</label>
                                <select id="status" name="status" class="form-select">
                                    @foreach($statuses as $status => $name)
                                        <option value="{{ $status }}"<?= $group->status == $status ? ' selected' : '' ?>>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
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
                                <input type="text" name="group_leaders" id="group_leaders" class="form-control"
                                       value="{{ $group->group_leaders ?: $user->name }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3 required">
                                <label class="form-label" for="institute_id">Intézmény / plébánia</label>
                                <select name="institute_id" id="institute_id" style="width:100%" class="form-select" required>
                                    <option value="{{ $group->institute_id }}">
                                        {{ $group->institute_id ? $group->institute_name . ' (' . $group->city . ')' : 'intézmény' }}
                                        @if($institute && $institute->approved == 0)
                                            - függőben levő intézmény
                                        @endif
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3 required">
                                <label class="form-label" for="age_group">Korosztály <small class="text-muted fw-normal">(legalább egyet adj meg)</small></label>
                                <select class="form-select" name="age_group[]" multiple="multiple" id="age_group" required>
                                    @foreach($age_groups as $age_group)
                                    <option value="{{ $age_group->value }}"<?= in_array($age_group->name, $age_group_array) ? ' selected' : '' ?>>
                                        {{ $age_group->translate() }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3 required">
                                <label class="form-label" for="occasion_frequency">Alkalmak gyakorisága</label>
                                <select class="form-select" id="occasion_frequency" name="occasion_frequency" required>
                                    @foreach($occasion_frequencies as $occasion_frequency)
                                    <option value="{{ $occasion_frequency->value }}"<?= $group->occasion_frequency == $occasion_frequency->value ? ' selected' : '' ?>>
                                        {{ $occasion_frequency->translate() }}
                                    </option>
                                    @endforeach
                                </select>
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
                                <label class="form-label">Csatlakozási lehetőség módja <i class="fas fa-info-circle text-muted"
                                     title="Egyéni megbeszélés alapján: Közösségvezetővel egyeztetve történik&#10;Folyamatos csatlakozási lehetőség: Az év folyamán bármikor jöhetnek új tagok&#10;Időszakos csatlakozás: pl.: Minden félév első hónapja, negyedévente stb"
                                     data-html="false"></i></label>
                                <?= (new \App\Http\Components\Selectors\JoinModeSelector())->render($group->join_mode) ?>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="spiritual_movement_id">Lelkiségi mozgalom</label>
                        <p class="small text-muted mb-2">Ha egy nagyobb lelkiségi mozgalomhoz tartoztok, akkor azt adjátok meg itt, így nagyobb eséllyel találnak meg azok, akik ezen mozgalom közösségeit keresik.</p>
                        <?= (new \App\Http\Components\Selectors\SpiritualMovementSelector())->render($group->spiritual_movement_id) ?>
                    </div>
                </div>

                <div class="group-register-card">
                    <h2 class="group-register-card__title">A közösség jellemzői <span class="text-danger">*</span></h2>
                    <mod-alert :level="'info'">
                        <i class="fas fa-tags" aria-hidden="true"></i>
                        <span>Válassz ki legalább egy, de legfeljebb öt tulajdonságot, ami a közösségedet a legjobban jellemzi.</span>
                    </mod-alert>
                    <div class="mb-3">
                        <div class="group-register-tags">
                            @foreach($tags as $tag)
                            <label class="group-register-tag" for="tag-{{ $tag->value }}">
                                <input type="checkbox"
                                       name="tags[]"
                                       id="tag-{{ $tag->value }}"
                                       value="{{ $tag->value }}"
                                       <?= in_array($tag->value, $group_tags) ? ' checked' : '' ?>
                                > {{ $tag->translate() }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <h3 class="group-register-card__subhead mt-4">Bemutatkozás <span class="text-danger">*</span></h3>
                    <mod-alert :level="'info'">
                        <i class="fas fa-pen-fancy" aria-hidden="true"></i>
                        <span>Írd le pár mondatban, hogy kik vagytok, milyen alkalmakat tartotok, és mi teszi vonzóvá a közösségeteket.</span>
                    </mod-alert>
                    <div class="mb-3 required">
                        <textarea name="description" id="description" class="form-control">{{ $group->description }}</textarea>
                    </div>

                    <h3 class="group-register-card__subhead">Fotó a közösségről</h3>
                    <p class="small text-muted mb-2">Ha nem adsz meg új képet, a jelenlegi marad meg. Ha nem töltasz fel képet, az intézmény fotója jelenhet meg a listában.</p>
                    <div class="row group-images">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="group-register-upload">
                                    <div class="group-image group-register-photo" role="region" aria-label="Közösség kép előnézet">
                                        <img src="{{ $group->getThumbnail() . '?' . time() }}" id="image" width="300" alt="Közösség előnézeti képe">
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
                                <p class="mb-2"><strong>Jelenlegi dokumentum:</strong>
                                    @if($group->document)
                                        <a href="{{ $group->getDocumentUrl() }}" download class="fw-semibold"><i class="fas fa-download" aria-hidden="true"></i> {{ $group->document }}</a>
                                    @else
                                        Még nincs feltöltve.
                                    @endif
                                </p>
                                <p class="mb-2">Új fájl feltöltése opcionális; elfogadott: <strong>doc, docx</strong>, <strong>pdf</strong> vagy kép.</p>
                                <p class="mb-0">Minta: <a href="/storage/uploads/igazolas.pdf" download class="fw-semibold"><i class="fas fa-download" aria-hidden="true"></i> Igazolás minta letöltése</a></p>
                            </span>
                        </mod-alert>
                        <div class="group-register-file">
                            <label class="form-label" for="document-upload">Dokumentum cseréje</label>
                            <input type="file" name="document" id="document-upload" class="form-control">
                        </div>
                    </div>
                </div>

                @csrf
                <div class="group-register-submit text-center">
                    <button type="submit" class="btn btn-lg btn-orange rounded-pill px-5 shadow-sm">
                        <i class="fas fa-save me-2" aria-hidden="true"></i>Mentés
                    </button>
                    <div class="mt-3">
                        <a href="@route('portal.delete_group', $group)" class="text-danger confirm-action account-danger-link fw-semibold">Közösségem törlése</a>
                    </div>
                </div>
            </form>
            </div>
            </div>
        </div>
    </div>

</mint-extend>
