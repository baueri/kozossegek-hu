@extends('portal.group.create-steps.create-wrapper')
<div class="row">
    <div class="col-md-4 offset-4">
        <h2 class="text-center">Felhasználói adatok</h2>
        <form method="post">
            <input type="hidden" name="next_step" value="2">
            <div class="form-group required">
                <label>Neved:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group required">
                <label>Email címed:</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group required">
                <label>Jelszó:</label>
                <input type="password" class="form-control" required>
            </div>
            <div class="form-group required">
                <label>Jelszó még egyszer:</label>
                <input type="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-darkblue">Tovább</button>
        </form>
    </div>
</div>