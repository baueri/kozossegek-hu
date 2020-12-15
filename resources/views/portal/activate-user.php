@section('header_content')
    @featuredTitle('Fiók aktiválása')
@endsection
@extends('portal')
<div class="container inner">
    @include('admin.partials.message')
    <form method="post" class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Jelszó</label>
                <input type="password" name="new_password" class="form-control">
            </div>

            <div class="form-group">
                <label>Jelszó még egyszer</label>
                <input type="password" name="new_password_again" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Mentés</button>
            </div>
        </div>
    </form>
</div>