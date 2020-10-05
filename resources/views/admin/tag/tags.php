@title('Címkék')
@section('title')
    <form class="float-right mr-3" id="create_tag">
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" name="new_tag_name" placeholder="címke neve...">
            <div class="input-group-append">
                <button class="btn btn-primary"><i class="fa fa-plus"></i> Új címke</button>
            </div>
        </div>
    </form>
@endsection
@extends('admin')
@foreach($tags as $i => $tag)
    <div class="row mb-3" data-id="{{ $tag['id'] }}">
        <div class="col-md-6 col-lg-3">
            <div class="form-group">
                <input type="text" class="form-control form-control-sm spiritual_movement_name" value="{{ $tag['tag'] }}" tabindex="{{ $i+1 }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <button type="button" class="btn btn-primary btn-sm save-movement">Mentés</button>
                <button type="button" class="btn btn-danger btn-sm delete-movement"><i class="fa fa-trash"></i></button>
            </div>
        </div>
    </div>
@endforeach
<script>
    $(() => {
        $(".spiritual_movement_name").keyup(function(e){
            if (e.which == 13) {
                saveMovement($(this).closest(".row"));
            }
        });
        $(".save-movement").click(function(){
            saveMovement($(this).closest(".row"));
        });
        $(".delete-movement").click(function(){
            var row = $(this).closest(".row");
            var id = row.data("id");
            $.post("@route('admin.tags.delete')", {id:id}, function(response){
                notify("Sikeres törlés", "warning");
                setTimeout(() => {
                    row.slideUp();
                    setTimeout(() => { row.remove() }, 500);
                }, 1000);
            });
        });

        $("#create_tag").submit(function(e) {
            e.preventDefault();
            var tag = $("[name=new_tag_name]").val();
            $.post("@route('admin.tags.create')", {tag:tag}, function(response){
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
            $.post("@route('admin.tags.save')", {id:id, value:value}, function(response){
                if(response.success) {
                    notify("Sikeres mentés!", "success");
                } else {
                    notify("Hiba történt mentéskor!", "danger");
                }
            });
        }
    });
</script>
