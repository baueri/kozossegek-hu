@extends('portal.group.create-steps.create-wrapper')
<div class="step-container">
    <div class="row">
        <div class="col-md-4 offset-4">
            <h3 class="text-center">Felhasználói adatok</h3>
            <form method="post">
                <input type="hidden" name="next_step" value="group_data">
                <div class="form-group required">
                    <label>Neved:</label>
                    <input type="text" class="form-control form-control-sm" name="user_name" required value="{{ $user_name }}">
                </div>
                <div class="form-group required">
                    <label>Email címed:</label>
                    <input type="email" class="form-control form-control-sm" name="email" value="{{ $email }}" required>
                </div>
                <div class="form-group required">
                    <label>Jelszó:</label>
                    <input type="password" name="password" class="form-control form-control-sm" required>
                </div>
                <div class="form-group required">
                    <label>Jelszó még egyszer:</label>
                    <input type="password" name="password_again" class="form-control form-control-sm" required>
                </div>
                <button type="submit" class="btn btn-darkblue">Tovább</button>
            </form>
        </div>
    </div>
</div>