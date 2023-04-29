@extends('portal')
@featuredTitle('Új jelszó megadása')
<div class="container">
    @include('admin.partials.message')
    <form method="post" class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Új jelszó</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="form-group">
                <label>Új jelszó még egyszer</label>
                <input type="password" name="new_password_again" class="form-control">
            </div>
            <div class="form-group">
                @csrf()
                <button type="submit" class="btn btn-primary">Új jelszó mentése</button>
            </div>
        </div>
    </form>
</div>