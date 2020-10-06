@title('Lelkiségi mozgalmak')
@section('title')
    <form class="float-right mr-3" id="create_movement">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" name="new_movement_name" placeholder="lelkiségi mozgalom neve..." required>
            <div class="input-group-append">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Új lelkiségi mozgalom</button>
            </div>
        </div>
    </form>
@endsection
@extends('admin')
<div class="row">
    @foreach($movements as $i => $movement)
        <div class="col-md-4 spiritual_movement_col" data-id="{{ $movement['id'] }}">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control form-control-sm spiritual_movement_name" value="{{ $movement['name'] }}" tabindex="{{ $i+1 }}">
                    <div class="input-group-append"><button type="button" class="btn btn-primary btn-sm save-movement">Mentés</button></div>
                    <div class="input-group-append"><button type="button" class="btn btn-danger btn-sm delete-movement"><i class="fa fa-trash"></i></button></div>
                </div>
            </div>
        </div>
        <!-- <div class="row mb-3">
            <div class="col-md-6 col-lg-3">
                <div class="form-group">
                    <input type="text" class="form-control form-control-sm spiritual_movement_name" value="{{ $movement['name'] }}" tabindex="{{ $i+1 }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-sm save-movement">Mentés</button>
                    <button type="button" class="btn btn-danger btn-sm delete-movement"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        </div> -->
    @endforeach
</div>
<script>
    $(() => {
        $(".spiritual_movement_name").keyup(function(e){
            if (e.which == 13) {
                saveMovement($(this).closest(".spiritual_movement_col"));
            }
        });
        $(".save-movement").click(function(){
            saveMovement($(this).closest(".spiritual_movement_col"));
        });
        $(".delete-movement").click(function(){
            var row = $(this).closest(".spiritual_movement_col");
            var id = row.data("id");
            $.post("@route('admin.spiritual_movements.delete')", {id:id}, function(response){
                notify("Sikeres törlés", "warning");
                setTimeout(() => {
                    row.slideUp();
                    setTimeout(() => { row.remove() }, 500);
                }, 1000);
            });
        });

        $("#create_movement").submit(function(e) {
            e.preventDefault();
            var name = $("[name=new_movement_name]").val();
            $.post("@route('admin.spiritual_movements.create')", {name:name}, function(response){
                if(response.success) {
                    window.location.reload();
                } else {
                    notify(response.msg, "danger");
                }
            });
        });

        function saveMovement(row)
        {
            var id = row.data("id");
            var value = $("input", row).val();
            $.post("@route('admin.spiritual_movements.save')", {id:id, value:value}, function(response){
                if(response.success) {
                    notify("Sikeres mentés!", "success");
                } else {
                    notify("Hiba történt mentéskor!", "danger");
                }
            });
        }
    });
</script>
