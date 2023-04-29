@section('header_content')
    @featuredTitle('Új fiók létrehozása')
@endsection
@extends('portal')
<div class="container inner">
    @alert('info')
        Kérjük, hogy csak abban az esetben hozz létre új fiókot, ha közösséget hirdetsz.
    @endalert
    @message()
    <form method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group required">
                    <label>Neved</label>
                    <input type="text" class="form-control" name="name"  value="{{ $name }}" data-describedby="validate_user_name" required>
                    <div id="validate_user_name" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Email címed</label>
                    <input type="email" class="form-control" name="email" value="{{ $email }}"  data-describedby="validate_email" required>
                    <div id="validate_email" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó <small>(min. 8 karakter)</small></label>
                    <input type="password" class="form-control" name="password" data-describedby="validate_password" required>
                    <div id="validate_password" class="validate_message"></div>
                </div>
                <div class="form-group required">
                    <label>Jelszó még egyszer</label>
                    <input type="password" class="form-control" name="password_again" data-describedby="validate_password_again" required>
                    <div id="validate_password_again" class="validate_message"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                @honeypot('register')
                <p>
                    @component('aszf')<br/>
                </p>
                <div class="form-group">
                    <button type="submit" class="btn btn-darkblue">Regisztráció</button>
                    <p class="mt-2">
                        <a href="@route('login')" id="login-existing-user" onclick="showLoginModal('{{ request()->uri }}'); return false;"><b>
                            <i class="fa fa-key"></i> van már fiókom, belépek
                        </b></a>
                    </p>
                </div>
            </div>
        </div>
        @csrf()
    </form>
</div>
