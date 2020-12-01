@extends('portal.group.create-steps.create-wrapper')
<h2>Felhasználói adatok megadása</h2>
<div class="row">
    <div class="col-md-6">
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
            <div class="form-group">
                <label>Hol hallottál rólunk?</label>
                <select class="form-control" name="heard_from">
                    <option>Ismerősömtől</option>
                    <option>Facebook-on</option>
                    <option>Google-ön keresztül</option>
                    <option>Egyéb</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Tovább</button>
        </form>
    </div>
</div>