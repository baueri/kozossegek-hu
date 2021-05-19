@extends('portal')
<div class="container inner">
    @include('portal.partials.user-sidemenu')
    @include('admin.partials.message')
    <h1 class="h3">Profilom</h1>
    <form method="post" action="@route('portal.profile.update')" class="mb-4">
        <div class="row">
            <div class="col-lg-5 col-md-12">
                <div class="form-group">
                    <label>E-mail cím</label>
                    <input type="email" name="email" value="{{ $user->email }}" class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Név</label>
                    <input type="text" name="name" value="{{ $user->name }}" class="form-control"/>
                </div>
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
        </div>
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
