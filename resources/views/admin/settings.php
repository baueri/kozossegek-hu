@title('Gépház')
@extends('admin')
<div class="form-group">
    <label>Minta kapcsoló</label>
    <div class="switch">
        <input type="checkbox" id="sample_toggle" name="sample_toggle" checked>
        <label for="sample_toggle"></label>
    </div>
</div>
<form method="post">
    <div class="row">
        <div class="col-md-3">
            <label>Adminisztrációs email cím</label>
            <div class="input-group">
                <input type="text" class="form-control" name="admin_email" value="">
                <div class="input-group-addend">
                    <button type="button" class="btn btn-primary">Mentés</button>
                </div>
            </div>
        </div>
    </div>
</form>
