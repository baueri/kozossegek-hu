@section('header')
    @include('asset_groups.editor')
@endsection
@extends('admin')
<form action="@route('admin.notification.doCreate')" method="post">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Cím</label>
                <input type="text" class="form-control" name="title" value="{{ $notification->title ?? '' }}">
            </div>
            <div class="form-group">
                <label>Értesítés szövege</label>
                <textarea class="form-control" id="message" name="message">{{ $notification->message ?? '' }}</textarea>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Hol jelenjen meg</label>
                <select class="form-control" name="display_for">
                    <option value="PORTAL" @if($notification->display_for ?? null === 'PORTAL')selected@endif>Látogatiói oldalon</option>
                    <option value="ADMIN" @if($notification->display_for ?? null === 'ADMIN')selected@endif>Admin oldalon</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary form-control mb-2">Mentés</button>
            <a href="" class="btn btn-secondary form-control">Vissza</a>
        </div>
    </div>
</form>
<script>
    $(() => {
        initSummernote("#message")
    });
</script>