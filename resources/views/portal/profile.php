@extends('portal2026.portal')
@featuredTitle('Profil')
<div class="container-fluid inner inner--account">
    <div class="row account-layout">
        <aside class="col-lg-3 col-md-4 mb-4 mb-md-0">
            @include('portal.partials.user-sidemenu')
        </aside>
        <div class="col-lg-9 col-md-8 account-main">
            <div class="account-panel">
                @include('admin.partials.message')
                <p class="account-page-head__lead mb-4">Személyes adatok és jelszó. A közösségi bejelentkezések itt kezelhetők.</p>
                <form method="post" action="@route('portal.profile.update')" class="mb-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="account-form-section">
                                <h3 class="account-section-title">Személyes adatok</h3>
                                <div class="mb-3">
                                    <label>E-mail cím @icon('info-circle small', 'Amikor valaki felszeretné venni a kapcsolatot a közösséggel, erre az email címre küldjük el az érdeklődő üzenetét.')</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
                                </div>
                                <div class="mb-3">
                                    <label>Név</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
                                </div>
                                <div class="mb-0">
                                    <label for="phone_number">Telefonszám
                                        @icon('info-circle small', 'Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is')</label>
                                    <input type="tel" name="phone_number" id="phone_number" value="{{ $user->phone_number }}" class="form-control">
                                </div>
                            </div>
                            <div class="account-form-section">
                                <h3 class="account-section-title">Jelszócsere</h3>
                                <div class="mb-3">
                                    <label>Régi jelszó</label>
                                    <input type="password" name="old_password" class="form-control" autocomplete="off"/>
                                </div>
                                <div class="mb-3">
                                    <label>Új jelszó</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
                                </div>
                                <div class="mb-0">
                                    <label>Jelszó még egyszer</label>
                                    <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-4 mt-lg-0">
                            <div class="account-form-section">
                                <h3 class="account-section-title">Közösségi fiókok</h3>
                                <div class="profile-social-block">
                                    @if(empty($socialProfiles) || count($socialProfiles) === 0)
                                        <p class="text-muted small mb-0">Nincs csatolt közösségi fiók.</p>
                                    @else
                                        @foreach($socialProfiles as $profile)
                                        <div class="profile-social-row">
                                            <div class="d-flex align-items-start justify-content-between flex-wrap">
                                                <div>
                                                    <i class="{{ $profile->icon() }} text-muted me-1"></i>
                                                    <span>{{ $profile->text() }}</span>
                                                </div>
                                                <a href="@route('portal.detach_social_profile', ['provider' => $profile->social_provider])" class="small text-danger fw-bold mt-1 mt-sm-0">
                                                    @icon('trash-alt') Szétkapcsolás
                                                </a>
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                        @csrf()
                        <button type="submit" class="btn btn-orange px-4 rounded-pill"><i class="fa fa-save"></i> Mentés</button>
                        <a href="@route('api.portal.profile.delete')" id="delete-profile" class="account-danger-link text-danger"><i class="fa fa-trash-alt"></i> Fiókom törlése</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@section('scripts')
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
@endsection
