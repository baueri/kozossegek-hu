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
                <p class="account-page-head__lead mb-4">Személyes adatok és jelszó. A közösségi bejelentkezések itt kezelhetők.</p>
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
                    <hr class="my-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <button type="submit" class="btn btn-orange px-4 rounded-pill"><i class="fa fa-save" aria-hidden="true"></i> Mentés</button>
                        <a href="@route('api.portal.profile.delete')" id="delete-profile" class="account-danger-link text-danger"><i class="fa fa-trash-alt" aria-hidden="true"></i> Fiókom törlése</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</mint-extend>
