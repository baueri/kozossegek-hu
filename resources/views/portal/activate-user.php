@extends('portal2026.portal')
@featuredTitle('Fiók aktiválása')
<div class="container inner">
    @message()
    <form method="post" class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label>Jelszó</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Jelszó még egyszer</label>
                <input type="password" name="new_password_again" class="form-control">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-orange px-4 rounded-pill">Mentés</button>
            </div>
        </div>
    </form>
</div>
