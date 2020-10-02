@title('Gépház')
@extends('admin')
<form>
    <div class="form-group">
        <label>Karbantartás</label>
        <div class="switch">
            <input type="checkbox" id="maintenance" name="maintenance" {{ $maintenance_on ? 'checked' : '' }}>
            <label for="maintenance"></label>
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
