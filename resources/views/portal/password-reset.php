@extends('portal2026.portal')
@featuredTitle('Új jelszó megadása')
<div class="container">
    @include('admin.partials.message')
    <form method="post" class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label>Új jelszó</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Új jelszó még egyszer</label>
                <input type="password" name="new_password_again" class="form-control">
            </div>
            <div class="mb-3">
                @csrf()
                <button type="submit" class="btn btn-orange px-4 rounded-pill">Új jelszó mentése</button>
            </div>
        </div>
    </form>
</div>