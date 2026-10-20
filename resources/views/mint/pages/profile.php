<mint-extend path="layout/inner.php" :subtitle="Fiók | " :page-title="Fiók" :inner-container="container-fluid" :inner-class="inner--account">

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
            <div class="account-panel account-panel--profile">
                <?php echo view('admin.partials.message'); ?>

                <header class="account-page-head">
                    <h2 class="account-page-head__title">Fiókbeállítások</h2>
                </header>

                <form method="post" action="@route('portal.profile.update')" class="mb-0">
                    @csrf

                    <section class="account-profile-card">
                        <h3 class="account-section-title">Személyes adatok</h3>
                        <div class="account-form-narrow">
                            <div class="mb-3">
                                <label class="form-label" for="profile-email">
                                    E-mail cím
                                    <i class="fa fa-info-circle small text-muted" title="Amikor valaki fel szeretné venni a kapcsolatot a közösséggel, erre az email címre küldjük el az érdeklődő üzenetét." aria-hidden="true"></i>
                                </label>
                                <input type="email" name="email" id="profile-email" value="{{ $user->email }}" class="form-control"/>
                            </div>
                            <div class="account-form-pair">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label" for="profile-name">Név</label>
                                    <input type="text" name="name" id="profile-name" value="{{ $user->name }}" class="form-control"/>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="phone_number">
                                        Telefonszám
                                        <i class="fa fa-info-circle small text-muted" title="Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is." aria-hidden="true"></i>
                                    </label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{ $user->phone_number }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="account-profile-card">
                        <h3 class="account-section-title">Közösségi fiókok</h3>
                        <div class="profile-social-block">
                            <?php if (empty($socialProfiles) || count($socialProfiles) === 0): ?>
                                <p class="text-muted small mb-0">Nincs csatolt közösségi fiók.</p>
                            <?php else: ?>
                                <?php foreach ($socialProfiles as $profile): ?>
                                <div class="profile-social-row">
                                    <div class="profile-social-row__inner">
                                        <div class="profile-social-row__label">
                                            <i class="<?php echo htmlspecialchars($profile->icon(), ENT_QUOTES, 'UTF-8'); ?>" aria-hidden="true"></i>
                                            <span><?php echo htmlspecialchars($profile->text(), ENT_QUOTES, 'UTF-8'); ?></span>
                                        </div>
                                        <a href="@route('portal.detach_social_profile', ['provider' => $profile->social_provider])" class="profile-social-row__detach">
                                            <i class="fa fa-unlink" aria-hidden="true"></i> Szétkapcsolás
                                        </a>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="account-profile-card">
                        <h3 class="account-section-title">Jelszócsere</h3>
                        <p class="account-section-hint">Csak akkor töltsd ki, ha meg szeretnéd változtatni a jelszavad.</p>
                        <div class="account-form-narrow">
                            <div class="mb-3">
                                <label class="form-label" for="old_password">Régi jelszó</label>
                                <input type="password" name="old_password" id="old_password" class="form-control" autocomplete="current-password"/>
                            </div>
                            <div class="account-form-pair">
                                <div class="mb-3 mb-md-0">
                                    <label class="form-label" for="new_password">Új jelszó</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label" for="new_password_again">Jelszó még egyszer</label>
                                    <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="account-profile-actions">
                        <button type="submit" class="btn btn-orange px-4 rounded-pill">
                            <i class="fa fa-save" aria-hidden="true"></i> Mentés
                        </button>
                    </div>
                </form>

                <section class="account-danger-zone">
                    <h3 class="account-section-title">Veszélyes zóna</h3>
                    <p class="text-muted small mb-3">
                        A fiók törlése végleges. A közösségeid és a kapcsolódó adatok kezelése a törlés szabályai szerint történik.
                    </p>
                    <a href="@route('api.portal.profile.delete')" id="delete-profile" class="btn btn-outline-danger rounded-pill px-4">
                        <i class="fa fa-trash-alt" aria-hidden="true"></i> Fiókom törlése
                    </a>
                </section>
            </div>
        </div>
    </div>

</mint-extend>
