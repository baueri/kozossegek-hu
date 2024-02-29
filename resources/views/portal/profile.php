@extends('portal')
<div class="container-fluid inner">
    @include('portal.partials.user-sidemenu')
    @include('admin.partials.message')
    <form method="post" action="@route('portal.profile.update')" class="mb-4">
        <div class="row">
            <div class="col-lg-5 col-md-12">
                <h3>Profilom</h3>
                <hr/>
                <div class="form-group">
                    <label>E-mail cím @icon('info-circle small', 'Amikor valaki felszeretné venni a kapcsolatot a közösséggel, erre az email címre küldjük el az érdeklődő üzenetét.')</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Név</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
                </div>
                <div class="form-group">
                    <label for="phone_number">Telefonszám @icon('info-circle small', 'Nem kötelező, de a könnyebb kapcsolattartás érdekében megadhatod a telefonszámodat is')</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ $user->phone_number }}" class="form-control">
                </div>
                <h3>Jelszócsere</h3>
                <hr/>
                <div class="form-group">
                    <label>Régi jelszó</label>
                    <input type="password" name="old_password" class="form-control" autocomplete="off"/>
                </div>
                <div class="form-group">
                    <label>Új jelszó</label>
                    <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password"/>
                </div>
                <div class="form-group">
                    <label>Jelszó még egyszer</label>
                    <input type="password" name="new_password_again" id="new_password_again" class="form-control" autocomplete="new-password"/>
                </div>
            </div>
            <div class="col-lg-5 col-md-12">
                <h3>Közösségi fiókok</h3>
                @foreach($socialProfiles as $profile)
                    <p>
                        <i class="{{ $profile->icon() }}"></i> - {{ $profile->text() }}<br/> <a href="@route('portal.detach_social_profile', ['provider' => $profile->social_provider])" class="small" style="color: darkorange; font-size: 12px; font-weight: bold;">@icon('trash-alt') szétkapcsolás</a>
                    </p>
                @endforeach
            </div>
        </div>
        @csrf()
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Mentés</button>
        <a href="@route('api.portal.profile.delete')" id="delete-profile" class="text-danger float-right"><i class="fa fa-trash-alt"></i> Fiókom törlése</a>
    </form>
</div>
@section('scripts')
    <script>
        $(() => {
            $("#delete-profile").confirm({
                type: "warning",
                message: function() {
                    return $("<div></div>").load("@route('api.portal.profile.delete_modal')");
                },
                isAjax: true,
                ajaxData() {
                    return {
                        "password": $("#delete-pw").val()
                    }
                },
                afterResponse(response) {
                    if(response.success) {
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
