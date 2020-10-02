@title('Gépház')
@extends('admin')
<div class="form-group">
    <label>Karbantartás</label>
    <div class="switch">
        <input type="checkbox" id="maintenance" name="maintenance" {{ $maintenance_on ? 'checked' : '' }}>
        <label for="maintenance"></label>
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
<script>
    $(()=>{
        $("#maintenance").change(function(){
            var toggle = $(this).is(":checked");
            $.post("@route('api.admin.maintenance')", {toggle:toggle}, function(response){
                console.log(response);
            });
        });
    });
</script>
